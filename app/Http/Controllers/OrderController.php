<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\InventoryService;
use App\Services\QrisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Menunggu Pembayaran',
            'processing' => 'Diproses',
            'shipped' => 'Sedang dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($status),
        };
    }

    protected function statusType(string $status): string
    {
        return match ($status) {
            'shipped' => 'shipping',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => 'pending',
        };
    }

    public function cancel(Request $request, string $orderNumber, InventoryService $inventoryService): RedirectResponse
    {
        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $cancelled = $inventoryService->cancelOrder(
            $order,
            ['status' => 'cancelled'],
            customerInitiated: true,
        );

        if (!$cancelled) {
            return redirect()->route('profil', ['tab' => 'pesanan'])
                ->with('error', 'Pesanan ini sudah dibayar, dikirim, selesai, atau sebelumnya dibatalkan sehingga tidak bisa dibatalkan lagi.');
        }

        return redirect()->route('profil', ['tab' => 'pesanan'])
            ->with('success', 'Pesanan berhasil dibatalkan dan stok sudah dikembalikan.');
    }

    public function complete(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($order->status !== 'shipped') {
            return redirect()->route('pesanan.detail', $orderNumber)
                ->with('error', 'Pesanan hanya bisa ditandai diterima setelah berstatus dikirim.');
        }

        $order->update(['status' => 'completed']);

        return redirect()->route('pesanan.detail', $orderNumber)
            ->with('success', 'Terima kasih! Pesanan ditandai selesai dan kamu sudah bisa memberi ulasan.');
    }

    public function show(Request $request, $orderNumber, QrisService $qris)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Tagihan QRIS: siapkan QR kalau belum ada, batalkan kalau lewat batas bayar
        try {
            $order = $qris->syncOrder($order)->load('items');
        } catch (\Throwable $e) {
            Log::warning('QRIS order sync failed.', [
                'order_number' => $order->order_number,
                'message' => $e->getMessage(),
            ]);
        }

        $localCreatedAt = $order->created_at->copy()->setTimezone('Asia/Jakarta');
        $localUpdatedAt = $order->updated_at->copy()->setTimezone('Asia/Jakarta');
        $steps = ['Diproses', 'Dikirim', 'Selesai'];
        $stepMap = ['pending' => 0, 'processing' => 0, 'shipped' => 1, 'completed' => 2, 'cancelled' => 0];
        $currentStep = $stepMap[$order->status] ?? 0;

        $progress = $order->status === 'cancelled' ? [] : collect($steps)->map(function ($label, $idx) use ($order, $currentStep, $localCreatedAt, $localUpdatedAt) {
            return [
                'label' => $label,
                'date' => $idx === 0
                    ? $localCreatedAt->translatedFormat('d M, H:i')
                    : ($idx <= $currentStep ? $localUpdatedAt->translatedFormat('d M, H:i') : 'Menunggu'),
                'completed' => $idx <= $currentStep,
            ];
        })->toArray();

        $latestUpdate = match ($order->status) {
            'shipped' => ['text' => 'Paket sedang dalam perjalanan menuju alamatmu', 'time' => $localUpdatedAt->translatedFormat('d M Y, H:i') . ' WIB'],
            'completed' => ['text' => 'Paket telah diterima', 'time' => $localUpdatedAt->translatedFormat('d M Y, H:i') . ' WIB'],
            'cancelled' => ['text' => 'Pesanan dibatalkan', 'time' => $localUpdatedAt->translatedFormat('d M Y, H:i') . ' WIB'],
            default => [
                'text' => match ($order->payment_status) {
                    'paid'      => 'Pesanan dikonfirmasi dan sedang diproses',
                    'verifying' => 'Bukti bayar diterima, menunggu konfirmasi admin',
                    default     => 'Pesanan dibuat dan menunggu pembayaran',
                },
                'time' => $localCreatedAt->translatedFormat('d M Y, H:i') . ' WIB',
            ],
        };

        if ($order->status === 'cancelled') {
            $timeline[] = [
                'date' => $localUpdatedAt->translatedFormat('d M Y'),
                'events' => [
                    ['time' => $localUpdatedAt->format('H:i'), 'text' => 'Pesanan dibatalkan'],
                ],
            ];
        } else {
            $timeline = [[
                'date' => $localCreatedAt->translatedFormat('d M Y'),
                'events' => [[
                    'time' => $localCreatedAt->format('H:i'),
                    'text' => 'Pesanan dibuat',
                ]],
            ]];

            if (!$localUpdatedAt->eq($localCreatedAt)) {
                $timeline[] = [
                    'date' => $localUpdatedAt->translatedFormat('d M Y'),
                    'events' => [[
                        'time' => $localUpdatedAt->format('H:i'),
                        'text' => $latestUpdate['text'],
                    ]],
                ];
            }
        }

        $headline = match ($order->status) {
            'shipped' => 'Paket cantikmu sedang menuju rumah',
            'completed' => 'Pesananmu telah sampai dengan selamat',
            'cancelled' => 'Pesanan ini telah dibatalkan',
            default => 'Pesananmu sedang kami siapkan',
        };

        $subtitle = match ($order->status) {
            'completed' => 'Terima kasih sudah berbelanja di Sweet Dreams. Semoga kamu menyukainya!',
            'cancelled' => $order->payment_status === 'expired'
                ? 'Batas waktu pembayaran QRIS sudah lewat, jadi pesanan otomatis dibatalkan. Silakan checkout ulang ya.'
                : 'Kalau ini bukan kamu yang batalkan, hubungi tim kami ya.',
            default => 'Kami akan menjaga setiap langkah perjalanan pesananmu tetap jelas dan tenang.',
        };

        $data = [
            'order_id' => '#' . $order->order_number,
            'status_raw' => $order->status,
            'headline' => $headline,
            'subtitle' => $subtitle,
            'status' => ($order->payment_status === 'verifying' ? 'Menunggu Verifikasi' : $this->statusLabel($order->status)),
            'status_type' => $this->statusType($order->status),
            'courier' => [
                'name' => $order->shipping_courier ?? '-',
                'resi' => $order->tracking_number ?? 'Belum tersedia',
                'service' => $order->shipping_service ?? '-',
            ],
            'progress' => $progress,
            'current_step' => $currentStep,
            'latest_update' => $latestUpdate,
            'timeline' => array_reverse($timeline),
            'products' => $order->items->map(function ($item) {
                return [
                    'title' => $item->product_title,
                    'variant' => "{$item->color} · {$item->size} · {$item->quantity} item",
                    'price' => 'Rp ' . number_format($item->subtotal, 0, ',', '.'),
                    'image' => $item->product_image,
                ];
            })->toArray(),
            'summary' => [
                'subtotal' => 'Rp ' . number_format($order->subtotal, 0, ',', '.'),
                'shipping' => 'Rp ' . number_format($order->shipping_cost, 0, ',', '.'),
                'total' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            ],
            'raw_order_number' => $order->order_number,
            'payment_status'   => $order->payment_status,
            'payment_method'   => $order->payment_method,
            'qris' => [
                'payload'      => $order->qr_string,
                'amount'       => (int) ($order->payment_amount ?: $order->total),
                'amount_label' => 'Rp ' . number_format((int) ($order->payment_amount ?: $order->total), 0, ',', '.'),
                'unique_code'  => (int) $order->payment_unique_code,
                'expires_at'   => $order->payment_expired_at?->toIso8601String(),
                'expires_label' => $order->payment_expired_at
                    ? $order->payment_expired_at->copy()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB'
                    : null,
                'merchant'     => $qris->merchantInfo(),
                'has_proof'    => !empty($order->payment_proof),
                'proof_url'    => $order->payment_proof ? route('pesanan.proof.show', $order->order_number) : null,
                'proof_uploaded_at' => $order->payment_proof_uploaded_at
                    ? $order->payment_proof_uploaded_at->copy()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') . ' WIB'
                    : null,
                'note'         => $order->payment_note,
            ],
        ];

        return view('pesanan-detail', ['order' => $data]);
    }
}