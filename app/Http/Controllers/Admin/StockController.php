<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    protected function indicator(int $stock): array
    {
        if ($stock === 0) return ['label' => 'Habis', 'class' => 'habis'];
        if ($stock <= 5) return ['label' => 'Kritis', 'class' => 'kritis'];
        if ($stock <= 15) return ['label' => 'Menipis', 'class' => 'menipis'];
        return ['label' => 'Aman', 'class' => 'aman'];
    }

    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        // Produk lama/hasil import punya sizes & colors tapi belum punya baris varian,
        // sehingga tidak muncul di sini dan stoknya tidak bisa diisi.
        Product::doesntHave('variants')->get()->each(fn (Product $product) => $product->syncVariants());

        // Statistik seluruh varian (tidak ikut filter pencarian)
        $totalUnit = ProductVariant::sum('stock');
        $habis = ProductVariant::where('stock', 0)->count();
        $menipis = ProductVariant::whereBetween('stock', [1, 15])->count();
        $lastUpdated = ProductVariant::max('updated_at');

        // Satu kartu per produk; stok varian dikelola di halaman detail produk
        $products = Product::with(['category', 'variants'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($query) => $query->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('title')
            ->get()
            ->map(function (Product $p) {
                $total = (int) $p->variants->sum('stock');
                $ind = $this->indicator($total);

                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'image' => $p->image,
                    'category' => $p->category->name ?? '-',
                    'total' => $total,
                    'variant_count' => $p->variants->count(),
                    'empty_variants' => $p->variants->where('stock', 0)->count(),
                    'indicator_label' => $ind['label'],
                    'indicator_class' => $ind['class'],
                ];
            });

        return view('admin.stock', [
            'products' => $products,
            'search' => $search,
            'totalUnit' => $totalUnit,
            'menipis' => $menipis,
            'habis' => $habis,
            'lastUpdated' => $lastUpdated ? \Carbon\Carbon::parse($lastUpdated)->diffForHumans() : null,
        ]);
    }

    public function show(Product $product)
    {
        if (!$product->variants()->exists()) {
            $product->syncVariants();
        }

        $variants = $product->variants()->get();
        // Urutan ukuran & warna mengikuti data produk, varian lama di luar daftar ditaruh di akhir
        $sizes = collect($product->sizes ?? [])->merge($variants->pluck('size'))->unique(fn ($s) => strtolower($s))->values();
        $colors = collect($product->colors ?? [])->merge($variants->pluck('color'))->unique(fn ($c) => strtolower($c))->values();

        $grid = [];
        foreach ($variants as $v) {
            $grid[strtolower($v->size)][strtolower($v->color)] = $v;
        }

        return view('admin.stock-product', [
            'product' => $product->load('category'),
            'sizes' => $sizes,
            'colors' => $colors,
            'grid' => $grid,
            'total' => (int) $variants->sum('stock'),
        ]);
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'stock' => 'required|array',
            'stock.*' => 'required|integer|min:0|max:100000',
        ]);

        DB::transaction(function () use ($product, $data) {
            $locked = Product::query()->lockForUpdate()->findOrFail($product->id);
            $variants = $locked->variants()->lockForUpdate()->get()->keyBy('id');

            foreach ($data['stock'] as $variantId => $stock) {
                $variant = $variants->get((int) $variantId);
                if ($variant && $variant->stock !== (int) $stock) {
                    $variant->update(['stock' => (int) $stock]);
                }
            }

            $locked->update(['stock' => $locked->variants()->sum('stock')]);
        });

        return redirect()->route('admin.stock.product', $product)->with('success', 'Stok ' . $product->title . ' berhasil disimpan.');
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate(['stock' => 'required|integer|min:0']);

        DB::transaction(function () use ($variant, $data) {
            $product = Product::query()->lockForUpdate()->findOrFail($variant->product_id);
            $lockedVariant = ProductVariant::query()->lockForUpdate()->findOrFail($variant->id);
            $lockedVariant->update(['stock' => $data['stock']]);
            $product->update(['stock' => $product->variants()->sum('stock')]);
        });

        return redirect()->route('admin.stock')->with('success', 'Stok berhasil diperbarui.');
    }
}
