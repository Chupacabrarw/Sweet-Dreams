<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, string $slug): RedirectResponse
    {
        $body = $request->input('body');
        if (is_string($body)) {
            $request->merge(['body' => trim($body)]);
        }

        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'min:5', 'max:1500'],
        ]);

        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $hasCompletedPurchase = $request->user()
            ->orders()
            ->where('status', 'completed')
            ->whereHas('items', fn ($query) => $query->where('product_id', $product->id))
            ->exists();

        if (!$hasCompletedPurchase) {
            return redirect()
                ->route('produk.detail', $product->slug)
                ->with('error', 'Ulasan hanya dapat diberikan untuk produk yang sudah dibeli dan pesanan berstatus selesai.')
                ->withFragment('tab-panel-ulasan');
        }

        $product->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            ['rating' => $data['rating'], 'body' => $data['body']]
        );

        return redirect()
            ->route('produk.detail', $product->slug)
            ->with('success', 'Ulasan produk berhasil disimpan.')
            ->withFragment('tab-panel-ulasan');
    }
}
