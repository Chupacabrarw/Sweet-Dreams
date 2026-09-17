<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
        public function index(Request $request)
    {
        $wishlistItems = $request->user()->wishlists()
            ->with('product.category')
            ->latest()
            ->get()
            ->map(function ($w) {
                $product = $w->product;
                return [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'title' => $product->title,
                    'category_name' => $product->category->name,
                    'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'image' => $product->image,
                    'default_size' => $product->sizes[0] ?? null,
                    'default_color' => $product->colors[0] ?? null,
                ];
            })->toArray();

        return view('wishlist', [
            'wishlistItems' => $wishlistItems,
        ]);
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $existing = $request->user()->wishlists()->where('product_id', $data['product_id'])->first();

        if ($existing) {
            $existing->delete();
            $isWishlisted = false;
        } else {
            $request->user()->wishlists()->create(['product_id' => $data['product_id']]);
            $isWishlisted = true;
        }

        return response()->json([
            'is_wishlisted' => $isWishlisted,
            'wishlist_count' => $request->user()->wishlists()->count(),
        ]);
    }
}