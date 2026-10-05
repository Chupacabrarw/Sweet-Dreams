<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

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
            default => 'pending',
        };
    }

    public function show(Request $request, $orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        // Jika pesanan masih unpaid tapi ada payment_reference, auto-sync dengan Komerce
        if ($order->payment_status !== 'paid' && !empty($order->payment_reference)) {
            try {
                $statusCheck = app(\App\Services\KomercePaymentService::class)->getPaymentStatus($order->payment_reference);
                if (!empty($statusCheck['success'])) {
                    $remoteStatus = strtoupper($statusCheck['status'] ?? '');
                    if (in_array($remoteStatus, ['PAID', 'SETTLED', 'SUCCESS', 'COMPLETED'])) {
                        $order->update(['payment_status' => 'paid', 'status' => 'processing']);
                        $order->refresh();
                    } elseif (in_array($remoteStatus, ['EXPIRED', 'FAILED', 'CANCELED', 'CANCELLED'])) {
                        $order->update(['payment_status' => 'expired', 'status' => 'cancelled']);
                        $order->refresh();
                    }
                }
            } catch (\Throwable $e) {
                // Jangan gagalkan halaman tracking jika API status sedang timeout
            }
        }

        $steps = ['Diproses', 'Dikirim', 'Selesai'];
        $stepMap = ['pending' => 0, 'processing' => 0, 'shipped' => 1, 'completed' => 2, 'cancelled' => 0];
        $currentStep = $stepMap[$order->status] ?? 0;

        $progress = collect($steps)->map(function ($label, $idx) use ($order, $currentStep) {
            return [
                'label' => $label,
                'date' => $idx === 0
                    ? $order->created_at->translatedFormat('d M, H:i')
                    : ($idx <= $currentStep ? $order->updated_at->translatedFormat('d M, H:i') : 'Menunggu'),
                'completed' => $idx <= $currentStep,
            ];
        })->toArray();

        $latestUpdate = match ($order->status) {
            'shipped' => ['text' => 'Paket sedang dalam perjalanan menuju alamatmu', 'time' => $order->updated_at->translatedFormat('d M Y, H:i') . ' WIB'],
            'completed' => ['text' => 'Paket telah diterima', 'time' => $order->updated_at->translatedFormat('d M Y, H:i') . ' WIB'],
            'cancelled' => ['text' => 'Pesanan dibatalkan', 'time' => $order->updated_at->translatedFormat('d M Y, H:i') . ' WIB'],
            default => ['text' => 'Pesanan dikonfirmasi dan sedang diproses', 'time' => $order->created_at->translatedFormat('d M Y, H:i') . ' WIB'],
        };

        $timeline = [[
            'date' => $order->created_at->translatedFormat('d M Y'),
            'events' => [
                ['time' => $order->created_at->format('H:i'), 'text' => 'Pesanan dikonfirmasi dan sedang diproses'],
            ],
        ]];

        if (!$order->updated_at->eq($order->created_at)) {
            $timeline[] = [
                'date' => $order->updated_at->translatedFormat('d M Y'),
                'events' => [
                    ['time' => $order->updated_at->format('H:i'), 'text' => $latestUpdate['text']],
                ],
            ];
        }

        $headline = match ($order->status) {
            'shipped' => 'Paket cantikmu sedang menuju rumah',
            'completed' => 'Pesananmu telah sampai dengan selamat',
            'cancelled' => 'Pesanan ini telah dibatalkan',
            default => 'Pesananmu sedang kami siapkan',
        };

        $subtitle = match ($order->status) {
            'completed' => 'Terima kasih sudah berbelanja di Sweet Dreams. Semoga kamu menyukainya!',
            'cancelled' => 'Kalau ini bukan kamu yang batalkan, hubungi tim kami ya.',
            default => 'Kami akan menjaga setiap langkah perjalanan pesananmu tetap jelas dan tenang.',
        };

        $data = [
            'order_id' => '#' . $order->order_number,
            'headline' => $headline,
            'subtitle' => $subtitle,
            'status' => $this->statusLabel($order->status),
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
            'payment_url'      => $order->payment_url,
            'va_number'        => $order->va_number,
        ];

        return view('pesanan-detail', ['order' => $data]);
    }
}