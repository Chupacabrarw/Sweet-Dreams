<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            'size' => 'required|string|max:20',
            'color' => 'required|string|max:50',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $data['quantity'] ?? 1;

        DB::transaction(function () use ($request, $data, $quantity) {
            $item = CartItem::query()
                ->where('user_id', $request->user()->id)
                ->where('product_id', $data['product_id'])
                ->where('size', $data['size'])
                ->where('color', $data['color'])
                ->lockForUpdate()
                ->first();

            $product = Product::query()
                ->where('is_active', true)
                ->lockForUpdate()
                ->find($data['product_id']);
            $item = CartItem::query()
                ->where('user_id', $request->user()->id)
                ->where('product_id', $data['product_id'])
                ->where('size', $data['size'])
                ->where('color', $data['color'])
                ->lockForUpdate()
                ->first();
            $variant = $this->findAvailableVariant($product, $data['size'], $data['color'], true);

            $requestedTotal = ($item?->quantity ?? 0) + $quantity;
            if ($requestedTotal > $variant->stock) {
                throw ValidationException::withMessages([
                    'stock' => "Stok tersedia {$variant->stock} unit untuk warna {$data['color']} ukuran {$data['size']}.",
                ]);
            }

            if ($item) {
                $item->update(['quantity' => $requestedTotal]);
            } else {
                CartItem::create([
                    'user_id' => $request->user()->id,
                    'product_id' => $data['product_id'],
                    'size' => $data['size'],
                    'color' => $data['color'],
                    'quantity' => $quantity,
                ]);
            }
        });

        $cartCount = $request->user()->cartItems()->sum('quantity');

        return response()->json(['message' => 'Produk ditambahkan ke keranjang.', 'cart_count' => $cartCount], 201);
    }

    public function updateQuantity(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);

        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($cartItem, $data) {
            $lockedItem = CartItem::query()->lockForUpdate()->findOrFail($cartItem->id);
            $product = Product::query()
                ->where('is_active', true)
                ->lockForUpdate()
                ->find($lockedItem->product_id);
            $variant = $this->findAvailableVariant($product, $lockedItem->size, $lockedItem->color, true);

            if ($data['quantity'] > $variant->stock) {
                throw ValidationException::withMessages([
                    'stock' => "Stok tersedia {$variant->stock} unit untuk warna {$lockedItem->color} ukuran {$lockedItem->size}.",
                ]);
            }

            $lockedItem->update(['quantity' => $data['quantity']]);
        });

        return response()->json(['message' => 'Kuantitas berhasil diubah.']);
    }

    public function destroy(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== $request->user()->id, 403);
        $cartItem->delete();

        return response()->json(['message' => 'Item berhasil dihapus dari keranjang.']);
    }

    private function findAvailableVariant(?Product $product, string $size, string $color, bool $lock): ProductVariant
    {
        $isConfigured = $product
            && collect($product->sizes ?? [])->contains(fn ($value) => strcasecmp((string) $value, $size) === 0)
            && collect($product->colors ?? [])->contains(fn ($value) => strcasecmp((string) $value, $color) === 0);

        $query = ProductVariant::query()
            ->where('product_id', $product?->id)
            ->whereRaw('LOWER(size) = ?', [strtolower($size)])
            ->whereRaw('LOWER(color) = ?', [strtolower($color)]);

        if ($lock) {
            $query->lockForUpdate();
        }

        $variant = $isConfigured ? $query->first() : null;
        if (!$variant || $variant->stock <= 0) {
            throw ValidationException::withMessages([
                'stock' => 'Varian yang dipilih tidak tersedia atau stoknya sudah habis.',
            ]);
        }

        return $variant;
    }
}