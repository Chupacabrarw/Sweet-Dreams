<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                'date' => $o->created_at->copy()->setTimezone('Asia/Jakarta')->translatedFormat('d M, H:i'),
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
        $order->load(['items', 'user']);

        return response()->json([
            'order_number'   => $order->order_number,
            'created_at'     => $order->created_at->copy()->setTimezone('Asia/Jakarta')->translatedFormat('d M Y, H:i'),

            // Customer
            'customer_name'  => $order->user?->name,
            'customer_email' => $order->user?->email,

            // Shipping info
            'shipping_recipient_name' => $order->shipping_recipient_name,
            'shipping_phone'          => $order->shipping_phone,
            'shipping_address'        => $order->shipping_address,
            'shipping_city'           => $order->shipping_city,
            'shipping_province'       => $order->shipping_province,
            'shipping_postal_code'    => $order->shipping_postal_code,
            'shipping_courier'        => $order->shipping_courier,
            'shipping_cost'           => 'Rp ' . number_format($order->shipping_cost, 0, ',', '.'),

            // Payment
            'payment_method'    => $order->payment_method,
            'payment_status'    => $order->payment_status,
            'payment_reference' => $order->payment_reference,

            // Status & tracking
            'status'           => $order->status,
            'status_label'     => $this->statusLabel($order->status),
            'tracking_number'  => $order->tracking_number,
            'voucher_code'     => $order->voucher_code,

            // Items
            'items' => $order->items->map(fn ($i) => [
                'title'    => $i->product_title,
                'image'    => $i->product_image,
                'color'    => $i->color,
                'size'     => $i->size,
                'variant'  => trim("{$i->color} / {$i->size}", ' / '),
                'qty'      => $i->quantity,
                'price'    => 'Rp ' . number_format($i->price, 0, ',', '.'),
                'subtotal' => 'Rp ' . number_format($i->price * $i->quantity, 0, ',', '.'),
            ]),

            // Totals
            'subtotal'    => 'Rp ' . number_format($order->subtotal, 0, ',', '.'),
            'discount'    => 'Rp ' . number_format($order->discount, 0, ',', '.'),
            'total'       => 'Rp ' . number_format($order->total, 0, ',', '.'),
        ]);
    }

    public function update(Request $request, Order $order, InventoryService $inventoryService)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled',
            // Resi wajib saat paket dinyatakan dikirim
            'tracking_number' => [
                Rule::requiredIf($request->input('status') === 'shipped'),
                'nullable', 'string', 'max:100',
            ],
        ]);

        if ($order->status === 'cancelled') {
            return redirect()->route('admin.orders')
                ->with('error', 'Pesanan yang sudah dibatalkan tidak dapat diubah lagi.');
        }

        if ($data['status'] === 'cancelled') {
            if (!$inventoryService->cancelOrder($order, $data)) {
                return redirect()->route('admin.orders')
                    ->with('error', 'Pesanan ini sudah berada pada status akhir dan tidak dapat dibatalkan lagi.');
            }
        } else {
            $order->update($data);
        }

        return redirect()->route('admin.orders')->with('success', 'Status pesanan berhasil diperbarui.');
    }
}