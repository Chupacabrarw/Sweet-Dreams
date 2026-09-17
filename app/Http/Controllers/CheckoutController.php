<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Voucher;

class CheckoutController extends Controller
{
    // Ongkir & metode bayar masih dummy dulu, nanti diganti RajaOngkir & Midtrans
    protected function shippingMethods(): array
    {
        return [
            ['id' => 'jne', 'name' => 'JNE Regular', 'desc' => 'Estimasi pengiriman 3–5 hari kerja', 'cost' => 15000, 'active' => false],
            ['id' => 'sicepat', 'name' => 'SiCepat Express', 'desc' => 'Estimasi pengiriman 1–2 hari kerja', 'cost' => 25000, 'active' => true],
            ['id' => 'gosend', 'name' => 'GoSend Same Day', 'desc' => 'Estimasi tiba dalam 2–4 jam', 'cost' => 35000, 'active' => false],
        ];
    }

    protected function paymentMethods(): array
    {
        return [
            ['id' => 'qris', 'name' => 'Qris', 'desc' => 'Mendukung pembayaran dari berbagai aplikasi', 'badges' => 'BCA MANDIRI BNI', 'active' => true],
            ['id' => 'ewallet', 'name' => 'E-Wallet', 'desc' => 'Bayar instan pakai GoPay, OVO, atau DANA', 'badges' => 'GOPAY OVO DANA', 'active' => false],
            ['id' => 'transfer', 'name' => 'Transfer Bank', 'desc' => 'Transfer via BCA, Mandiri, atau BNI', 'badges' => '', 'active' => false],
        ];
    }

    protected function voucherDiscount(?string $code, int $subtotal): int
    {
        return match (strtoupper((string) $code)) {
            'SWEETDREAM10' => (int) round($subtotal * 0.10),
            'SWEETDREAM50' => 50000,
            default => 0,
        };
    }

    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()->with('product')->get();
       
        $checkoutItems = $cartItems->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->product->title,
                'variant' => "{$item->color} · Size {$item->size} · Qty: {$item->quantity}",
                'price' => $item->product->price,
                'qty' => $item->quantity,
                'image' => $item->product->image,
            ];
        })->toArray();

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $shippingCost = 25000; // default: SiCepat (yang aktif duluan)

        $addresses = $request->user()->addresses()
            ->orderByDesc('is_primary')
            ->get()
            ->map(function ($addr) {
                return [
                    'id' => $addr->id,
                    'name' => $addr->recipient_name,
                    'phone' => $addr->phone,
                    'address' => $addr->address,
                    'city' => $addr->city,
                    'province' => $addr->province,
                    'postal_code' => $addr->postal_code,
                    'is_primary' => $addr->is_primary,
                ];
            })->toArray();

        return view('checkout', [
            'checkoutItems' => $checkoutItems,
            'shippingMethods' => $this->shippingMethods(),
            'paymentMethods' => $this->paymentMethods(),
            'addresses' => $addresses,
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'discount' => 0,
            'total' => $subtotal + $shippingCost,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'shipping_id' => 'required|string',
            'payment_id' => 'required|string',
            'voucher' => 'nullable|string',
        ]);

        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang belanja masih kosong.'], 422);
        }

        $shipping = collect($this->shippingMethods())->firstWhere('id', $data['shipping_id']);
        $payment = collect($this->paymentMethods())->firstWhere('id', $data['payment_id']);

        if (!$shipping || !$payment) {
            return response()->json(['message' => 'Metode pengiriman/pembayaran tidak valid.'], 422);
        }

                $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        $voucher = null;
        $discount = 0;
        if (!empty($data['voucher'])) {
            $voucher = Voucher::where('code', strtoupper($data['voucher']))->first();
            if ($voucher && $voucher->isValidFor($subtotal)) {
                $discount = $voucher->calculateDiscount($subtotal);
            } else {
                $voucher = null;
            }
        }

        $total = max(0, $subtotal + $shipping['cost'] - $discount);

        $order = DB::transaction(function () use ($user, $data, $cartItems, $shipping, $payment, $subtotal, $discount, $total, $voucher) {
            $newOrder = Order::create([
                'order_number' => 'SD-' . now()->format('ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'shipping_recipient_name' => $data['name'],
                'shipping_phone' => $data['phone'],
                'shipping_address' => $data['address'],
                'shipping_city' => $data['city'],
                'shipping_province' => $data['province'],
                'shipping_postal_code' => $data['postal_code'],
                'shipping_courier' => $shipping['name'],
                'shipping_service' => $shipping['id'],
                'shipping_cost' => $shipping['cost'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'voucher_code' => $voucher?->code,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $payment['id'],
                'payment_status' => 'unpaid',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item->product_id,
                    'product_title' => $item->product->title,
                    'product_image' => $item->product->image,
                    'size' => $item->size,
                    'color' => $item->color,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
            }

            $user->cartItems()->delete();

            return $newOrder;
        });
                    if ($voucher) {
                $voucher->increment('used_count');
            }
        return response()->json([
            'message' => 'Pesanan berhasil dibuat.',
            'order_number' => $order->order_number,
            'total' => $order->total,
        ], 201);
    }

        public function validateVoucher(Request $request)
    {
        $data = $request->validate(['code' => 'required|string', 'subtotal' => 'required|integer']);

        $voucher = Voucher::where('code', strtoupper($data['code']))->first();

        if (!$voucher || !$voucher->isValidFor($data['subtotal'])) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid atau tidak memenuhi syarat.']);
        }

        return response()->json([
            'valid' => true,
            'discount' => $voucher->calculateDiscount($data['subtotal']),
            'label' => $voucher->discount_type === 'percentage' ? "{$voucher->code} ({$voucher->discount_value}%)" : $voucher->code,
        ]);
    }
}