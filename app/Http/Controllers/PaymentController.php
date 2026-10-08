<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryService;
use App\Services\MidtransPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected MidtransPaymentService $paymentService;
    protected InventoryService $inventoryService;

    public function __construct(MidtransPaymentService $paymentService, InventoryService $inventoryService)
    {
        $this->paymentService = $paymentService;
        $this->inventoryService = $inventoryService;
    }

    /**
     * Cek status pembayaran on-demand
     * GET /api/payment/status/{orderNumber}
     */
    public function checkStatus(Request $request, $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan.'], 404);
        }

        if ($order->payment_status === 'paid') {
            return response()->json([
                'success'        => true,
                'payment_status' => 'paid',
                'order_status'   => $order->status,
                'message'        => 'Pembayaran sudah lunas.',
                'order_number'   => $order->order_number,
            ]);
        }

        if (!empty($order->payment_reference)) {
            $res = $this->paymentService->getPaymentStatus($order->payment_reference);

            if ($res['success']) {
                $remoteStatus = strtolower($res['status'] ?? '');

                if (in_array($remoteStatus, ['capture', 'settlement'])) {
                    if ($order->status === 'cancelled') {
                        Log::warning("Payment confirmed after order {$order->order_number} was cancelled.");
                        return response()->json([
                            'success' => false,
                            'payment_status' => $order->payment_status,
                            'order_status' => $order->status,
                            'message' => 'Pesanan sudah dibatalkan. Hubungi admin untuk bantuan pembayaran.',
                            'order_number' => $order->order_number,
                        ], 409);
                    }

                    $order->update([
                        'payment_status' => 'paid',
                        'status'         => 'processing',
                    ]);

                    return response()->json([
                        'success'        => true,
                        'payment_status' => 'paid',
                        'order_status'   => 'processing',
                        'message'        => 'Pembayaran berhasil dikonfirmasi!',
                        'order_number'   => $order->order_number,
                    ]);
                } elseif (in_array($remoteStatus, ['expire', 'cancel', 'deny'])) {
                    $cancelled = $this->inventoryService->cancelOrder($order, [
                        'payment_status' => 'expired',
                        'status'         => 'cancelled',
                    ], true);
                    $order->refresh();

                    return response()->json([
                        'success'        => $cancelled,
                        'payment_status' => $order->payment_status,
                        'order_status'   => $order->status,
                        'message'        => $cancelled ? 'Tagihan pembayaran sudah kedaluwarsa.' : 'Pembayaran pesanan ini sudah tercatat.',
                        'order_number'   => $order->order_number,
                    ], $cancelled ? 200 : 409);
                }
            }
        }

        return response()->json([
            'success'        => true,
            'payment_status' => $order->payment_status,
            'order_status'   => $order->status,
            'payment_url'    => $order->payment_url,
            'order_number'   => $order->order_number,
            'total'          => $order->total,
        ]);
    }

    /**
     * Webhook notifikasi pembayaran dari Midtrans
     * POST /api/payment/webhook
     */
    public function handleWebhook(Request $request)
    {
        try {
            $notification = new \Midtrans\Notification();
            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;

            // Extract order_number
            $parts = explode('-', $orderId);
            $orderNumber = $parts[0];

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order) {
                if (
                    in_array($transaction, ['capture', 'settlement'], true)
                    && $order->status === 'cancelled'
                ) {
                    Log::warning("Payment confirmed after order {$order->order_number} was cancelled.");
                } elseif ($transaction == 'capture') {
                    if ($type == 'credit_card') {
                        if ($fraud == 'challenge') {
                            $order->update(['payment_status' => 'pending']);
                        } else {
                            $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                        }
                    }
                } elseif ($transaction == 'settlement') {
                    $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                } elseif ($transaction == 'pending') {
                    $order->update(['payment_status' => 'pending']);
                } elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                    $this->inventoryService->cancelOrder($order, [
                        'payment_status' => 'expired',
                        'status' => 'cancelled',
                    ], true);
                }
                
                Log::info("Order #{$orderNumber} payment webhook processed: status = {$transaction}");
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
