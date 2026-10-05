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
            ['id' => 'jnt', 'name' => 'J&T Express EZ', 'desc' => 'Estimasi pengiriman 2–3 hari kerja', 'cost' => 16000, 'active' => true],
            ['id' => 'jne', 'name' => 'JNE Regular', 'desc' => 'Estimasi pengiriman 2–3 hari kerja', 'cost' => 17000, 'active' => false],
            ['id' => 'spx', 'name' => 'Shopee Express (SPX)', 'desc' => 'Estimasi pengiriman 2–3 hari kerja', 'cost' => 16000, 'active' => false],
            ['id' => 'sicepat', 'name' => 'SiCepat Express', 'desc' => 'Estimasi pengiriman 1–2 hari kerja', 'cost' => 20000, 'active' => false],
            ['id' => 'pos', 'name' => 'POS Indonesia Reguler', 'desc' => 'Estimasi pengiriman 2–4 hari kerja', 'cost' => 18000, 'active' => false],
            ['id' => 'gosend', 'name' => 'GoSend Instant / Same Day', 'desc' => 'Estimasi tiba dalam 2–4 jam (Area Terpilih)', 'cost' => 35000, 'active' => false],
        ];
    }

    protected function paymentMethods(): array
    {
        return [
            ['id' => 'qris', 'name' => 'QRIS (Scan & Bayar Instan)', 'desc' => 'Mendukung GoPay, OVO, DANA, ShopeePay, BCA Mobile, dll.', 'badges' => 'QRIS GOPAY OVO DANA', 'active' => true],
            ['id' => 'bca', 'name' => 'BCA Virtual Account', 'desc' => 'Transfer instan via BCA Mobile / KlikBCA / myBCA / ATM', 'badges' => 'BCA VA', 'active' => false],
            ['id' => 'bni', 'name' => 'BNI Virtual Account', 'desc' => 'Transfer instan via BNI Mobile Banking / ATM', 'badges' => 'BNI VA', 'active' => false],
            ['id' => 'bri', 'name' => 'BRI Virtual Account (BRIVA)', 'desc' => 'Transfer instan via BRImo / ATM BRI', 'badges' => 'BRIVA', 'active' => false],
            ['id' => 'mandiri', 'name' => 'Mandiri Virtual Account', 'desc' => 'Transfer instan via Livin by Mandiri / ATM Mandiri', 'badges' => 'MANDIRI', 'active' => false],
            ['id' => 'permata', 'name' => 'Permata Virtual Account', 'desc' => 'Transfer instan via PermataMobile X / ATM', 'badges' => 'PERMATA', 'active' => false],
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
        $cartQuery = $request->user()->cartItems()->with('product');
        if ($request->has('items')) {
            $selectedIds = array_filter(explode(',', $request->query('items')));
            if (!empty($selectedIds)) {
                $cartQuery->whereIn('id', $selectedIds);
            }
        }
        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('keranjang');
        }
       
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
        $shippingCost = 16000; // default: J&T EZ (yang aktif duluan)

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
                    'city_id' => $addr->city_id,
                    'province' => $addr->province,
                    'province_id' => $addr->province_id,
                    'postal_code' => $addr->postal_code,
                    'is_primary' => $addr->is_primary,
                ];
            })->toArray();

        return view('checkout', [
            'checkoutItems' => $checkoutItems,
            'shippingMethods' => $this->shippingMethods(),
            'paymentMethods' => $this->paymentMethods(),
            'addresses' => $addresses,
            'provinces' => app(\App\Http\Controllers\ShippingController::class)->getProvincesList(),
            'subtotal' => $subtotal,
            'shippingCost' => $shippingCost,
            'discount' => 0,
            'total' => $subtotal + $shippingCost,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string',
            'city'             => 'required|string|max:255',
            'city_id'          => 'nullable',
            'province'         => 'required|string|max:255',
            'province_id'      => 'nullable',
            'postal_code'      => 'required|string|max:10',
            'shipping_id'      => 'required|string',
            'shipping_courier' => 'nullable|string',
            'shipping_cost'    => 'nullable|numeric',
            'payment_id'       => 'required|string',
            'voucher'          => 'nullable|string',
            'item_ids'         => 'nullable|array',
        ]);

        $user = $request->user();
        $cartQuery = $user->cartItems()->with('product');
        if (!empty($data['item_ids'])) {
            $cartQuery->whereIn('id', $data['item_ids']);
        }
        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Keranjang belanja masih kosong atau tidak ada produk yang dipilih.'], 422);
        }

        $payment = collect($this->paymentMethods())->firstWhere('id', $data['payment_id']);

        if (!$payment) {
            return response()->json(['message' => 'Metode pembayaran tidak valid.'], 422);
        }

        // Resolusi tarif ongkir dan nama kurir (bisa dari live RajaOngkir atau preset)
        $presetShipping = collect($this->shippingMethods())->firstWhere('id', $data['shipping_id']);
        $shippingCourier = !empty($data['shipping_courier']) 
            ? $data['shipping_courier'] 
            : ($presetShipping['name'] ?? 'JNE Regular');
        $shippingCost = isset($data['shipping_cost']) 
            ? (int) $data['shipping_cost'] 
            : ($presetShipping['cost'] ?? 15000);
        $shippingService = $data['shipping_id'];

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

        $total = max(0, $subtotal + $shippingCost - $discount);

        $order = DB::transaction(function () use ($user, $data, $cartItems, $shippingCourier, $shippingService, $shippingCost, $payment, $subtotal, $discount, $total, $voucher) {
            $newOrder = Order::create([
                'order_number' => 'SD-' . now()->format('ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'shipping_recipient_name' => $data['name'],
                'shipping_phone' => $data['phone'],
                'shipping_address' => $data['address'],
                'shipping_city' => $data['city'],
                'shipping_province' => $data['province'],
                'shipping_postal_code' => $data['postal_code'],
                'shipping_courier' => $shippingCourier,
                'shipping_service' => $shippingService,
                'shipping_cost' => $shippingCost,
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

            // Hapus hanya item yang di-checkout
            foreach ($cartItems as $item) {
                $item->delete();
            }

            return $newOrder;
        });

        if ($voucher) {
            $voucher->increment('used_count');
        }

        // Buat tagihan pembayaran via Komerce Payment Gateway
        $paymentResult = app(\App\Services\KomercePaymentService::class)->createPayment($order, $payment['id']);

        if (!empty($paymentResult['success'])) {
            $order->update([
                'payment_reference'  => $paymentResult['payment_id'] ?? null,
                'payment_url'        => $paymentResult['payment_url'] ?? null,
                'va_number'          => $paymentResult['va_number'] ?? null,
                'qr_string'          => $paymentResult['qr_string'] ?? null,
                'payment_expired_at' => $paymentResult['expired_at'] ?? null,
            ]);
        }

        return response()->json([
            'message'        => 'Pesanan berhasil dibuat.',
            'order_number'   => $order->order_number,
            'total'          => $order->total,
            'payment_url'    => $order->payment_url,
            'va_number'      => $order->va_number,
            'qr_string'      => $order->qr_string,
            'payment_id'     => $order->payment_reference,
            'payment_method' => $payment['name'],
            'bank_code'      => $paymentResult['bank_code'] ?? null,
            'payment_status' => $order->payment_status,
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