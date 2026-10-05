<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KomercePaymentService
{
    protected string $apiKey;
    protected string $baseUrl;
    protected string $env;

    public function __construct()
    {
        $this->apiKey  = (string) config('services.komerce_payment.key', env('KOMERCE_PAYMENT_API_KEY', ''));
        $this->env     = (string) config('services.komerce_payment.env', env('KOMERCE_PAYMENT_ENV', 'sandbox'));
        $this->baseUrl = (string) config('services.komerce_payment.base_url', env('KOMERCE_PAYMENT_BASE_URL', 'https://api-sandbox.collaborator.komerce.id/user'));
    }

    /**
     * Buat transaksi pembayaran ke Komerce Payment Gateway
     */
    public function createPayment(Order $order, string $paymentMethod): array
    {
        $method = strtolower(trim($paymentMethod));

        // Tentukan payment_type dan channel_code
        if ($method === 'qris' || str_contains($method, 'ewallet') || str_contains($method, 'qris')) {
            $paymentType = 'qris';
            $channelCode = 'QRIS';
        } else {
            $paymentType = 'bank_transfer';
            $channelCode = match ($method) {
                'bca'      => 'BCA',
                'bni'      => 'BNI',
                'bri'      => 'BRI',
                'mandiri'  => 'MANDIRI',
                'permata'  => 'PERMATA',
                default    => 'BCA',
            };
        }

        $customerName  = $order->shipping_recipient_name ?: ($order->user->name ?? 'Pelanggan Sweet Dreams');
        $customerEmail = $order->user->email ?? 'customer@sweetdreams.test';
        $customerPhone = $order->shipping_phone ?: ($order->user->phone ?: '081234567890');

        // Format items pesanan
        $items = [];
        foreach ($order->items as $item) {
            $items[] = [
                'name'     => (string) ($item->product_title ?: 'Produk Sweet Dreams'),
                'quantity' => (int) ($item->quantity ?: 1),
                'price'    => (int) ($item->price ?: $item->subtotal),
            ];
        }

        // Jika items kosong atau total berbeda signifikan, fallback item ringkasan
        if (empty($items)) {
            $items[] = [
                'name'     => 'Pesanan ' . $order->order_number,
                'quantity' => 1,
                'price'    => (int) $order->total,
            ];
        }

        $payload = [
            'order_id'        => (string) $order->order_number,
            'payment_type'    => $paymentType,
            'channel_code'    => $channelCode,
            'amount'          => (int) $order->total,
            'customer'        => [
                'name'  => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ],
            'items'           => $items,
            'expiry_duration' => 86400, // 24 jam
        ];

        try {
            $endpoint = rtrim($this->baseUrl, '/') . '/api/v1/user/payment/create';

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-api-key'    => $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->timeout(15)
                ->post($endpoint, $payload);

            $body = $response->json();

            if ($response->successful() && !empty($body['data'])) {
                $data = $body['data'];

                return [
                    'success'            => true,
                    'payment_id'         => $data['payment_id'] ?? null,
                    'external_id'        => $data['external_id'] ?? null,
                    'payment_url'        => $data['payment_url'] ?? null,
                    'va_number'          => $data['va_number'] ?? null,
                    'qr_string'          => $data['qr_string'] ?? null,
                    'bank_code'          => $data['bank_code'] ?? $channelCode,
                    'bank_name'          => $data['bank_name'] ?? '',
                    'amount'             => (int) ($data['amount'] ?? $order->total),
                    'status'             => $data['status'] ?? 'PENDING',
                    'expired_at'         => $data['expired_at'] ?? null,
                    'raw'                => $data,
                ];
            }

            Log::warning('Komerce Payment create failed', [
                'status'  => $response->status(),
                'body'    => $body,
                'payload' => $payload,
            ]);

            return [
                'success' => false,
                'message' => $body['meta']['message'] ?? 'Gagal membuat tagihan pembayaran.',
            ];
        } catch (\Throwable $e) {
            Log::error('Komerce Payment exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kendala saat menghubungkan ke payment gateway.',
            ];
        }
    }

    /**
     * Cek status pembayaran ke Komerce
     */
    public function getPaymentStatus(string $paymentId): array
    {
        try {
            $endpoint = rtrim($this->baseUrl, '/') . '/api/v1/user/payment/status/' . urlencode($paymentId);

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'Accept'    => 'application/json',
                ])
                ->timeout(10)
                ->get($endpoint);

            $body = $response->json();

            if ($response->successful() && !empty($body['data'])) {
                return [
                    'success' => true,
                    'data'    => $body['data'],
                    'status'  => strtoupper($body['data']['status'] ?? 'PENDING'),
                ];
            }

            return [
                'success' => false,
                'message' => $body['meta']['message'] ?? 'Status pembayaran tidak ditemukan.',
            ];
        } catch (\Throwable $e) {
            Log::error('Komerce Payment getStatus error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verifikasi signature webhook (HMAC-SHA256)
     */
    public function verifyWebhookSignature(string $rawBody, ?string $signature, string $callbackKey): bool
    {
        if (empty($signature) || empty($callbackKey)) {
            return false;
        }

        $expected = hash_hmac('sha256', $rawBody, $callbackKey);
        return hash_equals($expected, $signature);
    }
}
