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

    public function index(Request $request)
    {
        $search = $request->get('q');

        $products = Product::with('category')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'image' => $p->image,
                    'category_id' => $p->category_id,
                    'category' => $p->category->name,
                    'sizes' => implode(', ', $p->sizes ?? []),
                    'sizes_raw' => implode(',', $p->sizes ?? []),
                    'colors_raw' => implode(',', $p->colors ?? []),
                    'colors_count' => count($p->colors ?? []),
                    'price' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                    'price_raw' => $p->price,
                    'stock' => $p->stock,
                    'sku' => $p->sku,
                    'short_desc' => $p->short_desc,
                    'status_label' => $this->statusLabel($p->stock),
                    'status_class' => $this->statusClass($p->stock),
                ];
            });

        return view('admin.products', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $this->handleImage($request, $data);

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(5);
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

    protected function handleImage(Request $request, array &$data): void
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug($data['title']) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/products'), $filename);
            $data['image'] = 'images/products/' . $filename;
        }
    }

    protected function validateData(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'short_desc' => 'nullable|string',
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
            'sku' => 'nullable|string|max:100',
        ]);

        $validated['sizes'] = $validated['sizes'] ? array_map('trim', explode(',', $validated['sizes'])) : [];
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