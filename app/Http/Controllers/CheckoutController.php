<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Voucher;
use App\Services\InventoryService;
use App\Services\QrisService;

class CheckoutController extends Controller
{
    // Ongkir preset (fallback kalau tarif live RajaOngkir tidak dikirim)
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

    /** Metode bayar checkout: QRIS statis toko (nominal + kode unik ditanam per pesanan). */
    public const PAYMENT_METHODS = [
        'qris' => ['name' => 'QRIS (Scan & Bayar)', 'desc' => 'Bayar pakai GoPay, OVO, DANA, ShopeePay, LinkAja, atau m-banking apa pun.', 'badges' => 'QRIS GOPAY OVO DANA'],
    ];

    protected function paymentMethods(): array
    {
        $out = [];
        foreach (array_keys(self::PAYMENT_METHODS) as $i => $id) {
            $out[] = ['id' => $id] + self::PAYMENT_METHODS[$id] + ['active' => $i === 0];
        }
        return $out;
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
                'product_id' => $item->product_id,
                'title' => $item->product->title,
                'variant' => "{$item->color} · Size {$item->size} · Qty: {$item->quantity}",
                'price' => $item->product->price,
                'qty' => $item->quantity,
                'weight' => (int) ($item->product->weight ?? 250),
                'image' => $item->product->image,
            ];
        })->toArray();

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);
        $shippingCost = 0;

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
                    'subdistrict' => $addr->subdistrict,
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
            'subdistrict'      => 'nullable|string|max:255',
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
            return response()->json([
                'message' => 'Pilihan produk checkout sudah berubah atau tidak tersedia. Kembali ke keranjang, pilih produk lagi, lalu lanjutkan checkout.',
                'redirect_url' => route('keranjang'),
            ], 409);
        }

        $payment = collect($this->paymentMethods())->firstWhere('id', $data['payment_id']);

        if (!$payment) {
            return response()->json(['message' => 'Metode pembayaran tidak valid.'], 422);
        }

        if (!app(QrisService::class)->isConfigured()) {
            return response()->json(['message' => 'Pembayaran QRIS toko sedang tidak tersedia. Silakan hubungi admin.'], 503);
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
            $lockedCartItems = $user->cartItems()
                ->whereIn('id', $cartItems->modelKeys())
                ->with('product')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            if ($lockedCartItems->count() !== $cartItems->count()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'items' => 'Isi keranjang berubah. Muat ulang keranjang lalu coba checkout kembali.',
                ]);
            }

            $lockedById = $lockedCartItems->keyBy('id');
            foreach ($cartItems as $snapshot) {
                if (($lockedById->get($snapshot->id)?->quantity ?? null) !== $snapshot->quantity) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => 'Jumlah produk di keranjang berubah. Muat ulang keranjang lalu coba checkout kembali.',
                    ]);
                }
            }

            app(InventoryService::class)->reserveForCartItems($lockedCartItems);

            $newOrder = Order::create([
                'order_number' => 'SD-' . now()->format('ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'shipping_recipient_name' => $data['name'],
                'shipping_phone' => $data['phone'],
                // Kecamatan digabung ke alamat (kolom khusus butuh migration baru)
                'shipping_address' => trim($data['address'] . (!empty($data['subdistrict']) ? ', Kec. ' . $data['subdistrict'] : '')),
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
                'inventory_deducted' => true,
            ]);

            foreach ($lockedCartItems as $item) {
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

            return $newOrder;
        });

        // Buat tagihan QRIS (nominal + kode unik tertanam di QR)
        try {
            $order = app(QrisService::class)->prepareOrder($order);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('QRIS prepare failed: ' . $e->getMessage(), ['order' => $order->order_number]);
            app(InventoryService::class)->cancelOrder($order, [
                'payment_status' => 'failed',
                'status' => 'cancelled',
            ]);

            return response()->json([
                'message' => 'Tagihan QRIS gagal dibuat. Stok sudah dikembalikan dan isi keranjang tetap tersedia; silakan coba lagi atau hubungi admin.',
            ], 502);
        }

        DB::transaction(function () use ($user, $cartItems) {
            $currentItems = $user->cartItems()
                ->whereIn('id', $cartItems->modelKeys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($cartItems as $snapshot) {
                $currentItem = $currentItems->get($snapshot->id);
                if ($currentItem && $currentItem->quantity === $snapshot->quantity) {
                    $currentItem->delete();
                }
            }
        });

        if ($voucher) {
            $voucher->increment('used_count');
        }

        return response()->json([
            'message'        => 'Pesanan berhasil dibuat.',
            'order_number'   => $order->order_number,
            'total'          => $order->total,
            'payment_amount' => $order->payment_amount,
            'payment_method' => $payment['name'],
            'payment_status' => $order->payment_status,
            'redirect_url'   => route('pesanan.detail', $order->order_number),
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