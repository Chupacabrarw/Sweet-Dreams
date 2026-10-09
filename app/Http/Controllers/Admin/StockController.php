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

        $variants = ProductVariant::with('product')
            ->when($search !== '', function ($query) use ($search) {
                // SKU/nama: cocok sebagian; ukuran & warna: cocok persis
                // (LIKE 1 huruf "M" ikut kena "Merah Maroon"/"Muda")
                $query->where(function ($query) use ($search) {
                    $query->where('sku', 'like', "%{$search}%")
                        ->orWhereRaw('LOWER(size) = ?', [mb_strtolower($search)])
                        ->orWhereRaw('LOWER(color) = ?', [mb_strtolower($search)])
                        ->orWhereHas('product', fn ($query) => $query->where('title', 'like', "%{$search}%"));
                });
            })
            ->latest('updated_at')
            ->get();

        $rows = $variants->map(function ($v) {
            $ind = $this->indicator($v->stock);
            return [
                'id' => $v->id,
                'sku' => $v->sku ?: '-',
                'product' => $v->product->title,
                'size' => $v->size,
                'color' => $v->color,
                'stock' => $v->stock,
                'indicator_label' => $ind['label'],
                'indicator_class' => $ind['class'],
            ];
        });

        return view('admin.stock', [
            'rows' => $rows,
            'search' => $search,
            'totalUnit' => $variants->sum('stock'),
            'menipis' => $variants->filter(fn ($v) => in_array($this->indicator($v->stock)['class'], ['kritis', 'menipis']))->count(),
            'habis' => $variants->where('stock', 0)->count(),
            'lastUpdated' => optional($variants->max('updated_at'))->diffForHumans(),
        ]);
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