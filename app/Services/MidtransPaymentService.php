<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransPaymentService
{
    protected $serverKey;
    protected $isProduction;
    protected $baseUrl;

    public function __construct()
    {
        $this->serverKey = config('services.midtrans.server_key');
        $this->isProduction = config('services.midtrans.is_production');
        $this->baseUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    public function createPayment(Order $order): array
    {
        $customerName  = $order->shipping_recipient_name ?: ($order->user->name ?? 'Pelanggan Sweet Dreams');
        $customerEmail = $order->user->email ?? 'customer@sweetdreams.test';
        $customerPhone = $order->shipping_phone ?: ($order->user->phone ?: '081234567890');

        $itemDetails = [];
        foreach ($order->items as $item) {
            $itemDetails[] = [
                'id'       => (string) $item->id,
                'price'    => (int) ($item->price ?: $item->subtotal),
                'quantity' => (int) ($item->quantity ?: 1),
                'name'     => (string) ($item->product_title ?: 'Produk Sweet Dreams'),
            ];
        }

        if (empty($itemDetails)) {
            $itemDetails[] = [
                'id'       => (string) $order->id,
                'price'    => (int) $order->total,
                'quantity' => 1,
                'name'     => 'Pesanan ' . $order->order_number,
            ];
        } else {
            // Midtrans requires gross_amount to strictly equal the sum of (price * quantity) of all item_details.
            // We must add shipping cost as an item.
            if ($order->shipping_cost > 0) {
                $itemDetails[] = [
                    'id'       => 'SHIPPING',
                    'price'    => (int) $order->shipping_cost,
                    'quantity' => 1,
                    'name'     => 'Biaya Pengiriman',
                ];
            }
            
            // We must add discount as an item with negative price.
            if ($order->discount > 0) {
                $itemDetails[] = [
                    'id'       => 'DISCOUNT',
                    'price'    => -((int) $order->discount),
                    'quantity' => 1,
                    'name'     => 'Diskon',
                ];
            }
        }

        $transactionDetails = [
            'order_id'     => $order->order_number . '-' . time(),
            'gross_amount' => (int) $order->total,
        ];

        $customerDetails = [
            'first_name' => $customerName,
            'email'      => $customerEmail,
            'phone'      => $customerPhone,
        ];

        $params = [
            'transaction_details' => $transactionDetails,
            'item_details'        => $itemDetails,
            'customer_details'    => $customerDetails,
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($this->baseUrl, $params);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'payment_url' => $response->json('redirect_url'),
                    'payment_reference' => $transactionDetails['order_id'],
                ];
            }

            Log::error('Midtrans API Error: ' . $response->body());
            
            return [
                'success' => false,
                'message' => 'Payment Gateway Error: ' . $response->json('error_messages.0', 'Unknown error'),
            ];
        } catch (\Throwable $e) {
            Log::error('Midtrans Create Payment Exception: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getPaymentStatus(string $orderId): array
    {
        $statusUrl = $this->isProduction 
            ? "https://api.midtrans.com/v2/{$orderId}/status" 
            : "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->get($statusUrl);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status'  => $response->json('transaction_status'),
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Status Gateway Error',
            ];
        } catch (\Throwable $e) {
            Log::error('Midtrans Status Exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
