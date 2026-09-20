<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cartItems = $request->user()->cartItems()
            ->with('product')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->product->title,
                    'variant' => $item->color . ' · Size ' . $item->size,
                    'color' => $item->color,
                    'size' => $item->size,
                    'price' => $item->product->price,
                    'qty' => $item->quantity,
                    'image' => $item->product->image,
                    'slug' => $item->product->slug,
                ];
            })
            ->toArray();

        $recommendedProducts = Product::where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->map(function ($p) {
                return [
                    'slug' => $p->slug,
                    'name' => $p->title,
                    'image' => $p->image,
                    'discount' => $p->discount,
                    'price' => 'Rp ' . number_format($p->price, 0, ',', '.'),
                    'original_price' => $p->original_price
                        ? 'Rp ' . number_format($p->original_price, 0, ',', '.')
                        : null,
                ];
            })
            ->toArray();

        return view('keranjang', [
            'cartItems' => $cartItems,
            'recommendedProducts' => $recommendedProducts,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:50',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $data['quantity'] ?? 1;

        $item = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $data['product_id'])
            ->where('size', $data['size'] ?? null)
            ->where('color', $data['color'] ?? null)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
        } else {
            $item = CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $data['product_id'],
                'size' => $data['size'] ?? null,
                'color' => $data['color'] ?? null,
                'quantity' => $quantity,
            ]);
        }

        $cartCount = $request->user()->cartItems()->sum('quantity');

        return response()->json(['message' => 'Produk ditambahkan ke keranjang.', 'cart_count' => $cartCount], 201);
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem->update(['quantity' => $data['quantity']]);

        return response()->json(['message' => 'Kuantitas berhasil diubah.']);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);
        $cartItem->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang.']);
    }
}