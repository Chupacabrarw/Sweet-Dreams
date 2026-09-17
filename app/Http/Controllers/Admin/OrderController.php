<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected function statusLabel(string $status): string
    {
        return match ($status) {
            'pending' => 'Baru',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($status),
        };
    }

    public function index(Request $request)
    {
        $filter = $request->get('status');

        $orders = Order::with(['items', 'user'])
            ->when($filter, fn ($q) => $q->where('status', $filter))
            ->latest()
            ->get();

        $counts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];

        $rows = $orders->map(function ($o) {
            return [
                'id' => $o->id,
                'order_number' => $o->order_number,
                'customer' => $o->user->name,
                'date' => $o->created_at->translatedFormat('d M, H:i'),
                'status' => $o->status,
                'status_label' => $this->statusLabel($o->status),
                'total' => 'Rp ' . number_format($o->total, 0, ',', '.'),
            ];
        });

        return view('admin.orders', [
            'rows' => $rows,
            'counts' => $counts,
            'activeFilter' => $filter ?? 'all',
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return response()->json([
            'order_number' => $order->order_number,
            'payment_status' => $order->payment_status,
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
            'items' => $order->items->map(fn ($i) => [
                'title' => $i->product_title,
                'variant' => "{$i->color} / {$i->size}",
                'qty' => $i->quantity,
                'price' => 'Rp ' . number_format($i->price, 0, ',', '.'),
            ]),
            'subtotal' => 'Rp ' . number_format($order->subtotal, 0, ',', '.'),
            'discount' => 'Rp ' . number_format($order->discount, 0, ',', '.'),
            'total' => 'Rp ' . number_format($order->total, 0, ',', '.'),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->update($data);

        return redirect()->route('admin.orders')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}