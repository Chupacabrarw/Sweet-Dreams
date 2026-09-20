<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    protected function formatProduct(Product $p): array
    {
        return [
            'id'           => $p->id,
            'title'        => $p->title,
            'image'        => $p->image,
            'category_id'  => $p->category_id,
            'category'     => $p->category->name ?? '—',
            'sizes'        => implode(', ', $p->sizes ?? []),
            'sizes_raw'    => implode(',', $p->sizes ?? []),
            'colors_raw'   => implode(',', $p->colors ?? []),
            'colors_count' => count($p->colors ?? []),
            'price'        => 'Rp ' . number_format($p->price, 0, ',', '.'),
            'price_raw'    => $p->price,
            'stock'        => $p->stock,
            'sku'          => $p->sku,
            'short_desc'   => $p->short_desc,
            'status_label' => $this->statusLabel($p->stock),
            'status_class' => $this->statusClass($p->stock),
        ];
    }

    public function index(Request $request)
    {
        $search = $request->get('q');
        $activeCat = $request->get('cat'); // active category slug/id filter

        $categories = Category::withCount('products')->orderBy('name')->get();

        // Products grouped by category (with optional search)
        $productsByCategory = $categories->mapWithKeys(function ($cat) use ($search) {
            $products = Product::with('category')
                ->where('category_id', $cat->id)
                ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%")
                       ->orWhere('sku', 'like', "%{$search}%");
                }))
                ->latest()
                ->get()
                ->map(fn($p) => $this->formatProduct($p));

            return [$cat->id => $products];
        });

        // All products (for the "Semua" tab)
        $allProducts = Product::with('category')
            ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                $q2->where('title', 'like', "%{$search}%")
                   ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->latest()
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return view('admin.products', [
            'allProducts'          => $allProducts,
            'productsByCategory'   => $productsByCategory,
            'categories'           => $categories,
            'search'               => $search,
            'activeCat'            => $activeCat,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->handleImage($request, $data);

        $data['slug']  = Str::slug($data['title']) . '-' . Str::random(5);
        $data['stock'] = 0;

        $product = Product::create($data);
        $this->syncVariants($product);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $this->handleImage($request, $data);

        $product->update($data);
        $this->syncVariants($product);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

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
        }
    }

    protected function validateData(Request $request): array
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|integer|min:0',
            'short_desc'  => 'nullable|string',
            'sizes'       => 'nullable|string',
            'colors'      => 'nullable|string',
            'sku'         => 'nullable|string|max:100',
        ]);

        $validated['sizes']  = $validated['sizes']  ? array_map('trim', explode(',', $validated['sizes']))  : [];
        $validated['colors'] = $validated['colors'] ? array_map('trim', explode(',', $validated['colors'])) : [];

        return $validated;
    }

    protected function syncVariants(Product $product): void
    {
        foreach ($product->sizes ?? [] as $size) {
            foreach ($product->colors ?? [] as $color) {
                ProductVariant::firstOrCreate(
                    ['product_id' => $product->id, 'size' => $size, 'color' => $color],
                    ['stock' => 0, 'sku' => $product->sku]
                );
            }
        }
    }
}