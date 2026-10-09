<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    protected function defaults(): array
    {
        return [
            'banner_homepage' => [
                'title' => "Sweet Dreams\nStart Here",
                'subtitle' => 'Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu.',
                'button_text' => 'Shop Now',
                'link' => '/katalog',
                'image' => 'images/hero-banner.jpg',
            ],
            'info_toko' => [
                'body' => 'Sweet Dreams adalah brand sleepwear wanita yang menghadirkan kenyamanan premium sejak 2019.',
            ],
            'kebijakan_retur' => [
                'body' => 'Produk dapat diretur maksimal 7 hari setelah diterima, dengan tag dan kemasan utuh.',
            ],
            'panduan_ukuran' => [
                'body' => 'Ukur lingkar dada, pinggang, dan pinggul. Cocokkan hasil dengan tabel ukuran kami.',
            ],
        ];
    }

    protected function getOrCreate(string $key): SiteContent
    {
        $defaults = $this->defaults()[$key] ?? [];
        return SiteContent::firstOrCreate(['key' => $key], $defaults);
    }

    public function index()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        $paidSales = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotNull('order_items.product_id')
            ->selectRaw('order_items.product_id as product_id, SUM(order_items.quantity) as total_sold')
            ->groupBy('order_items.product_id')
            ->pluck('total_sold', 'product_id');

        $products = Product::orderBy('title')->get()
            ->each(function (Product $product) use ($paidSales) {
                $product->paid_sales_count = (int) $paidSales->get($product->id, 0);
            })
            ->sortByDesc('paid_sales_count')
            ->values();

        $featuredCategories = json_decode(
            SiteContent::where('key', 'home_categories')->value('body') ?? '[]', true
        ) ?: [];

        $featuredProducts = json_decode(
            SiteContent::where('key', 'home_products')->value('body') ?? '[]', true
        ) ?: [];

        return view('admin.content', [
            'banner'             => $this->getOrCreate('banner_homepage'),
            'categories'         => $categories,
            'products'           => $products,
            'featuredCategories' => $featuredCategories,
            'featuredProducts'   => $featuredProducts,
            'couriers'           => \App\Http\Controllers\ShippingController::COURIERS,
            'enabledCouriers'    => \App\Http\Controllers\ShippingController::enabledCouriers(),
        ]);
    }

    public function updateFeatured(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'products'   => 'nullable|array',
        ]);

        $this->getOrCreate('home_categories')->update([
            'body' => json_encode($request->input('categories', [])),
        ]);

        $this->getOrCreate('home_products')->update([
            'body' => json_encode($request->input('products', [])),
        ]);

        return redirect()->route('admin.content')
            ->with('success', 'Pengaturan landing page berhasil disimpan.');
    }

    public function updateBanner(Request $request)
    {
        $data = $request->validate([
    'title'       => 'required|string|max:255',
    'subtitle'    => 'nullable|string|max:255',
    'button_text' => 'nullable|string|max:100',
    'link'        => 'nullable|string|max:255',
    'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=1920,min_height=800',
], [
    'image.dimensions' => 'Gambar banner minimal 1920×800 px (disarankan 2400×1000 px, landscape).',
]);

        $banner = $this->getOrCreate('banner_homepage');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['image'] = 'images/' . $filename;
        }

        $banner->update($data);

        return redirect()->route('admin.content')->with('success', 'Banner homepage berhasil diperbarui.');
    }

    public function updateCouriers(Request $request)
    {
        $data = $request->validate([
            'couriers'   => 'required|array|min:1',
            'couriers.*' => 'in:' . implode(',', array_keys(\App\Http\Controllers\ShippingController::COURIERS)),
        ], [
            'couriers.min' => 'Pilih minimal 1 ekspedisi agar checkout tidak kosong.',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'shipping_couriers'],
            ['body' => json_encode(array_values($data['couriers']))]
        );

        // Tarif di-cache 24 jam per kota -> bersihkan agar pilihan baru langsung berlaku
        \Illuminate\Support\Facades\Cache::flush();

        return redirect()->route('admin.content')
            ->with('success', 'Ekspedisi yang tampil di checkout berhasil disimpan.');
    }

    public function updateText(Request $request, string $key)
    {
        $request->validate(['body' => 'required|string']);

        $content = $this->getOrCreate($key);
        $content->update(['body' => $request->body]);

        return redirect()->route('admin.content')->with('success', 'Konten berhasil diperbarui.');
    }
}