<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected function indicator(int $stock): array
    {
        if ($stock === 0) return ['label' => 'Habis', 'class' => 'habis'];
        if ($stock <= 5) return ['label' => 'Kritis', 'class' => 'kritis'];
        if ($stock <= 15) return ['label' => 'Menipis', 'class' => 'menipis'];
        return ['label' => 'Aman', 'class' => 'aman'];
    }

    public function index()
    {
        $variants = ProductVariant::with('product')->latest('updated_at')->get();

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
            'totalUnit' => $variants->sum('stock'),
            'menipis' => $variants->filter(fn ($v) => in_array($this->indicator($v->stock)['class'], ['kritis', 'menipis']))->count(),
            'habis' => $variants->where('stock', 0)->count(),
            'lastUpdated' => optional($variants->max('updated_at'))->diffForHumans(),
        ]);
    }

    public function update(Request $request, ProductVariant $variant)
    {
        $data = $request->validate(['stock' => 'required|integer|min:0']);

        $variant->update(['stock' => $data['stock']]);
        $variant->product->update(['stock' => $variant->product->variants()->sum('stock')]);

        return redirect()->route('admin.stock')->with('success', 'Stok berhasil diperbarui.');
    }
}