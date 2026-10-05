<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\KomercePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected KomercePaymentService $paymentService;

    public function __construct(KomercePaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
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

        // Jika sudah lunas, langsung return
        if ($order->payment_status === 'paid') {
            return response()->json([
                'success'        => true,
                'payment_status' => 'paid',
                'order_status'   => $order->status,
                'message'        => 'Pembayaran sudah lunas.',
                'order_number'   => $order->order_number,
            ]);
        }

        // Jika ada payment_reference, cek ke Komerce
        if (!empty($order->payment_reference)) {
            $res = $this->paymentService->getPaymentStatus($order->payment_reference);

            if ($res['success']) {
                $remoteStatus = strtoupper($res['status'] ?? '');

                if (in_array($remoteStatus, ['PAID', 'SETTLED', 'SUCCESS', 'COMPLETED'])) {
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
                } elseif (in_array($remoteStatus, ['EXPIRED', 'FAILED', 'CANCELED', 'CANCELLED'])) {
                    $order->update([
                        'payment_status' => 'expired',
                        'status'         => 'cancelled',
                    ]);

                    return response()->json([
                        'success'        => true,
                        'payment_status' => 'expired',
                        'order_status'   => 'cancelled',
                        'message'        => 'Tagihan pembayaran sudah kedaluwarsa.',
                        'order_number'   => $order->order_number,
                    ]);
                }
            }
        }

        return response()->json([
            'success'        => true,
            'payment_status' => $order->payment_status,
            'order_status'   => $order->status,
            'payment_url'    => $order->payment_url,
            'va_number'      => $order->va_number,
            'order_number'   => $order->order_number,
            'total'          => $order->total,
        ]);
    }

    /**
     * Webhook notifikasi pembayaran dari Komerce
     * POST /api/payment/webhook
     */
    public function handleWebhook(Request $request)
    {
        $rawPayload = $request->getContent();
        $signature  = $request->header('X-Callback-Api-Key');
        $callbackKey = config('services.komerce_payment.key');

        Log::info('Komerce Webhook received', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        // Verifikasi signature jika callback key dikonfigurasi
        if (!empty($signature) && !empty($callbackKey)) {
            $isValid = $this->paymentService->verifyWebhookSignature($rawPayload, $signature, $callbackKey);
            if (!$isValid) {
                Log::warning('Komerce Webhook signature mismatch', [
                    'signature' => $signature,
                ]);
                return response()->json(['message' => 'Invalid signature'], 401);
            }
        }

        $data = $request->all();
        $orderId = $data['order_id'] ?? ($data['data']['order_id'] ?? null);
        $status  = strtoupper($data['status'] ?? ($data['data']['status'] ?? ''));

        if (!empty($orderId)) {
            $order = Order::where('order_number', $orderId)->first();

            if ($order) {
                if (in_array($status, ['PAID', 'SETTLED', 'SUCCESS', 'COMPLETED'])) {
                    $order->update([
                        'payment_status' => 'paid',
                        'status'         => 'processing',
                    ]);
                    Log::info("Order #{$orderId} payment confirmed via webhook.");
                } elseif (in_array($status, ['EXPIRED', 'FAILED', 'CANCELED', 'CANCELLED'])) {
                    $order->update([
                        'payment_status' => 'expired',
                        'status'         => 'cancelled',
                    ]);
                    Log::info("Order #{$orderId} payment expired/failed via webhook.");
                }
            }
        }

        return response()->json(['status' => 'ok', 'message' => 'Webhook processed successfully']);
    }
}
