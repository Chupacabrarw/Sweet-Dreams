<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    protected function formatRupiah(?int $amount): ?string
    {
        return $amount === null ? null : 'Rp ' . number_format($amount, 0, ',', '.');
    }

    protected function mapForCatalog(Product $product): array
    {
        return [
            'id' => $product->id,
            'slug' => $product->slug,
            'title' => $product->title,
            'category' => $product->category->slug,
            'category_name' => $product->category->name,
            'collection' => $product->collection,
            'price' => $this->formatRupiah($product->price),
            'price_raw' => $product->price,
            'original_price' => $this->formatRupiah($product->original_price),
            'discount' => $product->discount,
            'rating' => $product->rating,
            'review_count' => $product->review_count,
            'sizes' => $product->sizes,
            'colors' => $product->colors,
            'badge' => $product->badge,
            'short_desc' => $product->short_desc,
            'image' => $product->image,
            'created_at' => optional($product->created_at)->format('Y-m-d'),
            'sales_count' => $product->sales_count,
        ];
    }

    public function index(Request $request, $category = null)
    {
        $validCategories = ['baju-tidur', 'lingerie', 'kimono', 'pakaian-dalam'];
        $activeCategory = in_array($category, $validCategories) ? $category : null;

        $products = Product::with('category')
            ->where('is_active', true)
            ->get()
            ->map(fn ($p) => $this->mapForCatalog($p))
            ->toArray();

                $wishlistIds = $request->user()
            ? $request->user()->wishlists()->pluck('product_id')->toArray()
            : [];

        return view('katalog', [
            'products' => $products,
            'activeCategory' => $activeCategory,
            'wishlistIds' => $wishlistIds,
        ]);
    }

    public function show(Request $request, $slug)
    {
        $product = Product::with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();

        $hexMap = [
            'pink' => '#e8a0b0', 'gold' => '#d4a854', 'white' => '#f5f0ec',
            'cream' => '#eedfc8', 'grey' => '#6a6a7a',
        ];

        $colors = collect($product->colors)->values()->map(function ($c, $i) use ($hexMap) {
            return ['name' => ucfirst($c), 'hex' => $hexMap[$c] ?? '#e8a0b0', 'active' => $i === 0];
        })->toArray();

        $gallery = $product->gallery ?: [$product->image, $product->image, $product->image];
                $isWishlisted = $request->user()
            ? $request->user()->wishlists()->where('product_id', $product->id)->exists()
            : false;
        $data = [
             'id' => $product->id,
            'slug' => $product->slug,
            'title' => $product->title,
            'category' => $product->category->name,
            'category_slug' => $product->category->slug, 
            'collection' => $product->collection,
            'price' => $this->formatRupiah($product->price),
            'price_raw' => $product->price,
            'original_price' => $this->formatRupiah($product->original_price),
            'discount' => $product->discount,
            'rating' => $product->rating,
            'review_count' => (string) $product->review_count,
            'short_desc' => $product->short_desc,
            'badge' => $product->badge,
            'main_image' => $product->image,
            'gallery' => $gallery,
            'colors' => $colors,
            'sizes' => $product->sizes,
            'default_size' => $product->sizes[0] ?? 'M',
            'long_desc_title' => $product->long_desc_title ?: 'Kemewahan & Kenyamanan Terbaik',
            'long_desc' => $product->long_desc ?: ($product->short_desc . ' Dirancang dengan material terpilih berstandar internasional yang menjamin kenyamanan maksimal saat Anda beristirahat di rumah.'),
            'features' => $product->features ?: [
                'Bahan adem, lembut dan sangat ramah di kulit',
                'Jahitan presisi dan kuat untuk daya tahan pemakaian harian',
                'Warna tahan luntur meski dicuci berulang kali',
                'Hypoallergenic dan aman untuk kulit sensitif',
            ],
            'is_wishlisted' => $isWishlisted,
        ];

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(function ($p) {
                return [
                    'slug' => $p->slug,
                    'name' => $p->title,
                    'image' => $p->image,
                    'discount' => $p->discount,
                    'price' => $this->formatRupiah($p->price),
                    'original_price' => $this->formatRupiah($p->original_price),
                ];
            })->toArray();

        return view('produk-detail', [
            'product' => $data,
            'relatedProducts' => $relatedProducts,
        ]);
        
    }
       
}