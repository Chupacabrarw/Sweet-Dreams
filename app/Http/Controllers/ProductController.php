<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    protected function formatRupiah(?int $amount): ?string
    {
        return $amount === null ? null : 'Rp ' . number_format($amount, 0, ',', '.');
    }

    protected function topSellerIds(int $limit = 3): array
    {
        // BEST SELLER otomatis: maksimal 3 produk terlaris per kategori.
        // - Hanya produk yang sudah laku (sales_count > 0) yang dinilai.
        // - Kategori yang semua produknya 0 penjualan = tanpa badge.
        // - Dinamis mengikuti sales_count: kalau F menyalip C, C lepas, F dapat.
        $ids = [];
        foreach (Category::pluck('id') as $categoryId) {
            $top = Product::where('is_active', true)
                ->where('category_id', $categoryId)
                ->where('sales_count', '>', 0)
                ->orderByDesc('sales_count')
                ->limit($limit)
                ->pluck('id');
            foreach ($top as $id) {
                $ids[$id] = true;
            }
        }
        return $ids;
    }

    protected function mapForCatalog(Product $product, array $wishlistIds = [], array $colorHexBySlug = [], array $topSellerIds = []): array
    {
        $configuredVariants = $product->variants->map(fn ($variant) => [
            'size' => $variant->size,
            'color' => strtolower(trim($variant->color)),
            'color_name' => $variant->color,
            'color_hex' => $colorHexBySlug[Str::slug($variant->color)] ?? '#B58D97',
            'stock' => (int) $variant->stock,
        ]);
        $availableVariants = $configuredVariants->where('stock', '>', 0);

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
            'rating' => number_format((float) ($product->reviews_avg_rating ?? 0), 1),
            'review_count' => (int) ($product->reviews_count ?? 0),
            'sizes' => $product->sizes ?? [],
            'colors' => $product->colors ?? [],
            'configured_variants' => $configuredVariants->values(),
            'variants' => $availableVariants->values(),
            // Pil hitam = nama kategori. BEST SELLER jadi ribbon miring (is_best_seller).
            'badge' => $product->category->name,
            'is_best_seller' => isset($topSellerIds[$product->id]),
            'short_desc' => $product->short_desc,
            'image' => $product->image,
            'created_at' => optional($product->created_at)->format('Y-m-d'),
            'sales_count' => $product->sales_count,
            'stock' => $product->variants->sum('stock'),
            'is_wishlisted' => in_array($product->id, $wishlistIds),
        ];
    }

    protected function normalizeGallery(mixed $gallery): array
    {
        if (!is_array($gallery)) {
            return ['general' => [], 'colors' => []];
        }

        if (array_is_list($gallery)) {
            return [
                'general' => array_values(array_filter($gallery, 'is_string')),
                'colors' => [],
            ];
        }

        $general = is_array($gallery['general'] ?? null) ? $gallery['general'] : [];
        $colors = is_array($gallery['colors'] ?? null) ? $gallery['colors'] : [];

        return [
            'general' => array_values(array_filter($general, 'is_string')),
            'colors' => collect($colors)
                ->filter(fn ($paths) => is_array($paths))
                ->map(fn (array $paths) => array_values(array_filter($paths, 'is_string')))
                ->all(),
        ];
    }

    public function index(Request $request, $category = null)
    {
        $categories = Category::query()->orderBy('name')->get(['name', 'slug']);
        $activeCategory = $categories->contains('slug', $category) ? $category : null;

        $wishlistIds = $request->user()
            ? $request->user()->wishlists()->pluck('product_id')->map(fn($id) => (int)$id)->toArray()
            : [];
        $colorHexBySlug = ProductColor::query()->get(['name', 'hex'])
            ->mapWithKeys(fn (ProductColor $color) => [Str::slug($color->name) => $color->hex])
            ->all();

        $products = Product::with(['category', 'variants'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('is_active', true)
            ->get()
            ->map(fn ($p) => $this->mapForCatalog($p, $wishlistIds, $colorHexBySlug, $this->topSellerIds()))
            ->toArray();

        return view('katalog', [
            'products' => $products,
            'activeCategory' => $activeCategory,
            'categories' => $categories,
            'wishlistIds' => $wishlistIds,
        ]);
    }

    public function show(Request $request, $slug)
    {
        $product = Product::with(['category', 'variants'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $variants = $product->variants->map(fn ($variant) => [
            'size' => $variant->size,
            'color' => $variant->color,
            'stock' => $variant->stock,
        ])->values();
        $availableColorKeys = $product->variants
            ->where('stock', '>', 0)
            ->map(fn ($variant) => strtolower(trim($variant->color)))
            ->unique()
            ->values();
        $colorHexByName = ProductColor::query()->get(['name', 'hex'])
            ->mapWithKeys(fn (ProductColor $color) => [strtolower(trim($color->name)) => $color->hex]);
        $reviews = $product->reviews()->with('user:id,name')->latest()->get();
        $userReview = $request->user()
            ? $product->reviews()->where('user_id', $request->user()->id)->first()
            : null;
        $canReview = $request->user()
            ? $request->user()->orders()
                ->where(function ($query) use ($product) {
                    // completed = bisa ulasan; atau shipped > 7 hari (pengaman
                    // bila pembeli lupa klik "diterima", tanpa cron)
                    $query->where(function ($query) use ($product) {
                        $query->where('status', 'completed')
                            ->whereHas('items', fn ($query) => $query->where('product_id', $product->id));
                    })->orWhere(function ($query) use ($product) {
                        $query->where('status', 'shipped')
                            ->where('updated_at', '<=', now()->subDays(7))
                            ->whereHas('items', fn ($query) => $query->where('product_id', $product->id));
                    });
                })
                ->exists()
            : false;
        $storedGallery = $this->normalizeGallery($product->gallery);
        $generalGallery = array_values(array_unique(array_filter([
            ...$storedGallery['general'],
            $product->image,
        ])));
        $galleryByColor = [];
        $colors = collect($product->colors)
            ->filter(fn ($color) => $availableColorKeys->contains(strtolower(trim($color))))
            ->values()
            ->map(function ($color, $i) use ($colorHexByName, $storedGallery, $generalGallery, &$galleryByColor) {
                $slug = Str::slug($color);
                $colorGallery = array_values(array_unique(array_filter([
                    ...($storedGallery['colors'][$slug] ?? []),
                    ...$generalGallery,
                ])));
                $galleryByColor[$slug] = $colorGallery;

                return [
                    'name' => $color,
                    'slug' => $slug,
                    'hex' => $colorHexByName->get(strtolower(trim($color)), '#B58D97'),
                    'active' => $i === 0,
                    'gallery' => $colorGallery,
                ];
            });
        $defaultColor = $colors->first()['name'] ?? null;
        $defaultSize = $product->variants
            ->first(fn ($variant) => $variant->stock > 0 && strcasecmp($variant->color, (string) $defaultColor) === 0)
            ?->size ?? ($product->sizes[0] ?? 'M');

        $gallery = $colors->first()['gallery'] ?? $generalGallery;
        $wishlistIds = $request->user()
            ? $request->user()->wishlists()->pluck('product_id')->map(fn($id) => (int)$id)->toArray()
            : [];
        $isWishlisted = in_array($product->id, $wishlistIds);
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
            'rating' => number_format((float) ($product->reviews_avg_rating ?? 0), 1),
            'review_count' => (int) ($product->reviews_count ?? 0),
            'rating_distribution' => $reviews->isNotEmpty()
                ? (int) round($reviews->where('rating', '>=', 4)->count() / $reviews->count() * 100)
                : 0,
            'reviews' => $reviews,
            'user_review' => $userReview,
            'can_review' => $canReview,
            'short_desc' => $product->short_desc,
            'badge' => $product->category->name,
            'is_best_seller' => isset($this->topSellerIds()[$product->id]),
            'main_image' => $product->image,
            'gallery' => $gallery,
            'gallery_by_color' => $galleryByColor,
            'colors' => $colors,
            'sizes' => $product->sizes,
            'variants' => $variants,
            'stock' => $product->variants->sum('stock'),
            'default_size' => $defaultSize,
            'long_desc_title' => 'Deskripsi Produk',
            'long_desc' => filled($product->long_desc)
                ? $product->long_desc
                : 'Tidak ada deskripsi.',
            'is_wishlisted' => $isWishlisted,
        ];

        $relatedProducts = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(function ($p) use ($wishlistIds) {
                return [
                    'id' => $p->id,
                    'slug' => $p->slug,
                    'name' => $p->title,
                    'image' => $p->image,
                    'discount' => $p->discount,
                    'price' => $this->formatRupiah($p->price),
                    'original_price' => $this->formatRupiah($p->original_price),
                    'is_wishlisted' => in_array($p->id, $wishlistIds),
                ];
            })->toArray();

        return view('produk-detail', [
            'product' => $data,
            'relatedProducts' => $relatedProducts,
            'wishlistIds' => $wishlistIds,
            'flashSuccess' => session('success'),
            'flashError' => session('error'),
        ]);
        
    }
       
}