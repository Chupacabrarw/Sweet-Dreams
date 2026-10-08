<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderItem;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    protected function statusLabel(int $stock): string
    {
        if ($stock === 0) return 'Nonaktif';
        if ($stock <= 15) return 'Stok tipis';
        return 'Aktif';
    }

    protected function statusClass(int $stock): string
    {
        if ($stock === 0) return 'nonaktif';
        if ($stock <= 15) return 'tipis';
        return 'aktif';
    }

    protected function formatProduct(Product $p, array $colorIdsBySlug = []): array
    {
        $stockTotal = $p->variants->sum('stock');
        $gallery = $this->normalizeGallery($p->gallery);

        return [
            'id'           => $p->id,
            'title'        => $p->title,
            'image'        => $p->image,
            'category_id'  => $p->category_id,
            'category'     => $p->category->name ?? '—',
            'sizes'        => implode(', ', $p->sizes ?? []),
            'sizes_raw'    => implode(',', $p->sizes ?? []),
            'colors_raw'   => implode(',', $p->colors ?? []),
            'colors_ids'   => collect($p->colors ?? [])
                ->map(fn ($color) => $colorIdsBySlug[Str::slug($color)] ?? null)
                ->filter()
                ->values()
                ->all(),
            'colors_count' => count($p->colors ?? []),
            'gallery_general' => $gallery['general'],
            'color_gallery' => collect($gallery['colors'])
                ->mapWithKeys(fn (array $paths, string $slug) => isset($colorIdsBySlug[$slug])
                    ? [$colorIdsBySlug[$slug] => $paths]
                    : [])
                ->all(),
            'price'        => 'Rp ' . number_format($p->price, 0, ',', '.'),
            'price_raw'    => $p->price,
            'stock'        => $stockTotal,
            'variant_stocks' => $p->variants->map(fn (ProductVariant $variant) => [
                'size' => $variant->size,
                'color' => $variant->color,
                'stock' => $variant->stock,
            ])->values()->all(),
            'sku'          => $p->sku,
            'short_desc'   => $p->short_desc,
            'status_label' => $this->statusLabel($stockTotal),
            'status_class' => $this->statusClass($stockTotal),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->get('q');
        $activeCat = $request->get('cat'); // active category slug/id filter

        $categories = Category::withCount('products')->orderBy('name')->get();
        $colorOptions = ProductColor::query()->orderBy('name')->get(['id', 'name', 'slug', 'hex']);
        $colorOptionsData = $colorOptions->map(fn (ProductColor $color) => [
            'id' => $color->id,
            'name' => $color->name,
            'hex' => $color->hex,
            'slug' => $color->slug,
        ])->values();
        $colorIdsBySlug = $colorOptions->mapWithKeys(
            fn (ProductColor $color) => [$color->slug => $color->id]
        )->all();

        // Products grouped by category (with optional search)
        $productsByCategory = $categories->mapWithKeys(function ($cat) use ($search, $colorIdsBySlug) {
            $products = Product::with(['category', 'variants'])
                ->where('category_id', $cat->id)
                ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                }))
                ->latest()
                ->get()
                ->map(fn($p) => $this->formatProduct($p, $colorIdsBySlug));

            return [$cat->id => $products];
        });

        // All products (for the "Semua" tab)
        $allProducts = Product::with(['category', 'variants'])
            ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                $q2->where('title', 'like', "%{$search}%")
                   ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->latest()
            ->get()
            ->map(fn($p) => $this->formatProduct($p, $colorIdsBySlug));

        return view('admin.products', [
            'allProducts'          => $allProducts,
            'productsByCategory'   => $productsByCategory,
            'categories'           => $categories,
            'search'               => $search,
            'activeCat'            => $activeCat,
            'colorOptions'         => $colorOptions,
            'colorOptionsData'     => $colorOptionsData,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $colorIds = $data['colors'];
        $data['colors'] = $this->resolveColorNames($data['colors']);
        $this->handleImage($request, $data);
        $data['gallery'] = $this->storeGallery($request, $colorIds);
        unset($data['gallery_general'], $data['color_gallery'], $data['keep_gallery_general'], $data['keep_color_gallery']);

        $data['slug']  = Str::slug($data['title']) . '-' . Str::random(5);
        $data['stock'] = 0;

        DB::transaction(function () use ($data) {
            $product = Product::create($data);
            $this->syncVariants($product);
        });

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $colorIds = $data['colors'];
        $data['colors'] = $this->resolveColorNames($data['colors']);
        $this->handleImage($request, $data);
        $data['gallery'] = $this->storeGallery($request, $colorIds, $product);
        unset($data['gallery_general'], $data['color_gallery'], $data['keep_gallery_general'], $data['keep_color_gallery']);

        DB::transaction(function () use ($product, $data) {
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);
            $this->ensureStockedVariantsRemainConfigured($lockedProduct, $data);
            $lockedProduct->update($data);
            $this->syncVariants($lockedProduct);
            $lockedProduct->update(['stock' => $lockedProduct->variants()->sum('stock')]);
        });

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $deleted = DB::transaction(function () use ($product) {
            DB::table('cart_items')
                ->where('product_id', $product->id)
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id']);
            $lockedProduct = Product::query()->lockForUpdate()->findOrFail($product->id);
            $hasReservedOrder = OrderItem::query()
                ->where('product_id', $lockedProduct->id)
                ->whereHas('order', fn ($query) => $query
                    ->where('inventory_deducted', true)
                    ->whereIn('status', ['pending', 'processing']))
                ->exists();

            if ($hasReservedOrder) {
                return false;
            }

            $lockedProduct->delete();
            return true;
        });

        if (!$deleted) {
            return redirect()->route('admin.products')
                ->with('error', 'Produk tidak dapat dihapus karena masih ada pesanan yang menahan stok varian produk ini.');
        }

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus.');
    }

    // ─── Category Management ─────────────────────────────────────────────────

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        Category::create([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.products')->with('success', 'Kategori "' . $request->name . '" berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => trim($request->name),
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.products')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.products')
                ->with('error', 'Tidak bisa hapus kategori yang masih memiliki produk (' . $category->products()->count() . ' produk).');
        }

        $category->delete();

        return redirect()->route('admin.products')->with('success', 'Kategori berhasil dihapus.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    protected function handleImage(Request $request, array &$data): void
    {
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = Str::slug($data['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = 'images/products/' . $filename;
        } else {
            unset($data['image']);
        }
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

    protected function storeGallery(Request $request, array $colorIds, ?Product $product = null): array
    {
        $existingGallery = $this->normalizeGallery($product?->gallery);
        $requestedGeneral = array_map('strval', (array) $request->input('keep_gallery_general', []));
        $general = array_values(array_intersect($existingGallery['general'], $requestedGeneral));
        $generalFiles = $request->file('gallery_general', []);
        if (count($general) + count($generalFiles) > 8) {
            throw ValidationException::withMessages([
                'gallery_general' => 'Maksimal 8 foto umum dapat disimpan untuk satu produk.',
            ]);
        }
        $general = array_merge(
            $general,
            $this->storeGalleryFiles($generalFiles, $request->input('title', 'produk'), 'general')
        );

        $colors = [];
        $selectedColors = ProductColor::query()->whereIn('id', $colorIds)->get(['id', 'slug']);
        foreach ($selectedColors as $color) {
            $existingPaths = $existingGallery['colors'][$color->slug] ?? [];
            $requestedPaths = array_map(
                'strval',
                (array) $request->input("keep_color_gallery.{$color->id}", [])
            );
            $paths = array_values(array_intersect($existingPaths, $requestedPaths));
            $colorFiles = $request->file("color_gallery.{$color->id}", []);
            if (count($paths) + count($colorFiles) > 8) {
                throw ValidationException::withMessages([
                    "color_gallery.{$color->id}" => "Maksimal 8 foto dapat disimpan untuk warna {$color->slug}.",
                ]);
            }
            $paths = array_merge(
                $paths,
                $this->storeGalleryFiles(
                    $colorFiles,
                    $request->input('title', 'produk'),
                    $color->slug
                )
            );

            if ($paths !== []) {
                $colors[$color->slug] = $paths;
            }
        }

        return ['general' => $general, 'colors' => $colors];
    }

    protected function storeGalleryFiles(mixed $files, string $title, string $group): array
    {
        if (!is_array($files)) {
            return [];
        }

        $directory = public_path('images/products/gallery');
        File::ensureDirectoryExists($directory);

        $paths = [];
        foreach ($files as $file) {
            $filename = Str::slug($title) . '-' . Str::slug($group) . '-' . Str::random(12) . '.' . $file->extension();
            $file->move($directory, $filename);
            $paths[] = 'images/products/gallery/' . $filename;
        }

        return $paths;
    }

    protected function validateData(Request $request): array
    {
        $imageRequirement = $request->routeIs('admin.products.store') ? 'required' : 'nullable';
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'image'       => [$imageRequirement, 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'short_desc'  => 'nullable|string',
            'sizes'       => 'nullable|string',
            'colors'      => 'nullable|array',
            'colors.*'    => 'required|integer|distinct|exists:product_colors,id',
            'sku'         => 'nullable|string|max:100',
            'gallery_general' => 'nullable|array|max:8',
            'gallery_general.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'color_gallery' => 'nullable|array',
            'color_gallery.*' => 'array|max:8',
            'color_gallery.*.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'keep_gallery_general' => 'nullable|array|max:8',
            'keep_gallery_general.*' => 'string|max:255',
            'keep_color_gallery' => 'nullable|array',
            'keep_color_gallery.*' => 'array|max:8',
            'keep_color_gallery.*.*' => 'string|max:255',
        ]);

        $validated['sizes']  = $validated['sizes']  ? array_map('trim', explode(',', $validated['sizes']))  : [];
        $validated['sizes'] = array_values(array_unique(array_filter($validated['sizes'], fn ($value) => $value !== '')));
        $validated['colors'] = array_values(array_unique($validated['colors'] ?? []));

        return $validated;
    }

    protected function resolveColorNames(array $colorIds): array
    {
        return ProductColor::query()
            ->whereIn('id', $colorIds)
            ->orderBy('id')
            ->pluck('name')
            ->all();
    }

    protected function ensureStockedVariantsRemainConfigured(Product $product, array $data): void
    {
        $sizes = collect($data['sizes'])->map(fn ($size) => strtolower($size));
        $colors = collect($data['colors'])->map(fn ($color) => strtolower($color));

        $hasRemovedStock = $product->variants()
            ->where('stock', '>', 0)
            ->get()
            ->contains(fn (ProductVariant $variant) =>
                !$sizes->contains(strtolower($variant->size))
                || !$colors->contains(strtolower($variant->color))
            );

        if ($hasRemovedStock) {
            throw ValidationException::withMessages([
                'sizes' => 'Stok masih tersimpan pada varian yang ingin dihapus. Atur stok varian tersebut menjadi 0 terlebih dahulu.',
            ]);
        }
    }

    protected function syncVariants(Product $product): void
    {
        $desiredVariants = [];
        foreach ($product->sizes ?? [] as $size) {
            foreach ($product->colors ?? [] as $color) {
                $desiredVariants[serialize([strtolower($size), strtolower($color)])] = [
                    'size' => $size,
                    'color' => $color,
                ];
            }
        }

        foreach ($product->variants()->get() as $variant) {
            $key = serialize([strtolower($variant->size), strtolower($variant->color)]);

            if (isset($desiredVariants[$key])) {
                $desired = $desiredVariants[$key];
                $variant->update($desired);
                unset($desiredVariants[$key]);
            } elseif ($variant->stock === 0) {
                $variant->delete();
            }
        }

        foreach ($desiredVariants as $desired) {
            ProductVariant::create([
                'product_id' => $product->id,
                ...$desired,
                'stock' => 0,
                'sku' => $product->sku,
            ]);
        }
    }
}