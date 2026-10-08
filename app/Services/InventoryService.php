<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class InventoryService
{
    public function reserveForCartItems(Collection $cartItems): void
    {
        $requirements = $this->requirements($cartItems);
        $requiredProductIds = collect($requirements)->pluck('product_id')->unique()->sort()->values();
        $lockedProducts = Product::query()
            ->whereIn('id', $requiredProductIds)
            ->orderBy('id')
            ->lockForUpdate()
            ->get()
            ->keyBy('id');
        $productIds = [];

        foreach ($requirements as $required) {
            $product = $lockedProducts->get($required['product_id']);
            if (!$product || !$product->is_active) {
                throw ValidationException::withMessages([
                    'stock' => 'Salah satu produk di keranjang sudah tidak tersedia.',
                ]);
            }

            $sizeExists = collect($product->sizes ?? [])->contains(
                fn ($size) => strcasecmp((string) $size, $required['size']) === 0
            );
            $colorExists = collect($product->colors ?? [])->contains(
                fn ($color) => strcasecmp((string) $color, $required['color']) === 0
            );

            $variant = ProductVariant::query()
                ->where('product_id', $required['product_id'])
                ->whereRaw('LOWER(size) = ?', [strtolower($required['size'])])
                ->whereRaw('LOWER(color) = ?', [strtolower($required['color'])])
                ->lockForUpdate()
                ->first();

            if (!$sizeExists || !$colorExists || !$variant || $variant->stock < $required['quantity']) {
                $available = $variant?->stock ?? 0;
                throw ValidationException::withMessages([
                    'stock' => "Stok {$product->title} ({$required['color']}, {$required['size']}) tidak cukup. Tersedia {$available} unit.",
                ]);
            }

            $variant->decrement('stock', $required['quantity']);
            $productIds[] = $required['product_id'];
        }

        $this->syncProductTotals($productIds);
    }

    public function cancelOrder(
        Order $order,
        array $updates = [],
        bool $fromPayment = false,
        bool $customerInitiated = false
    ): bool
    {
        return DB::transaction(function () use ($order, $updates, $fromPayment, $customerInitiated) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);

            if (
                in_array($lockedOrder->status, ['cancelled', 'shipped', 'completed'], true)
                || ($fromPayment && $lockedOrder->payment_status === 'paid')
                || ($customerInitiated && (
                    $lockedOrder->status !== 'pending'
                    || $lockedOrder->payment_status === 'paid'
                ))
            ) {
                return false;
            }

            $productIds = [];
            if (
                $lockedOrder->inventory_deducted
                && in_array($lockedOrder->status, ['pending', 'processing'], true)
            ) {
                $requirements = $this->requirements($lockedOrder->items()->get());
                $requiredProductIds = collect($requirements)->pluck('product_id')->unique()->sort()->values();
                $lockedProducts = Product::query()
                    ->whereIn('id', $requiredProductIds)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($lockedProducts->count() !== $requiredProductIds->count()) {
                    throw new RuntimeException(
                        "Tidak dapat mengembalikan stok pesanan {$lockedOrder->order_number}: produk sudah tidak ditemukan."
                    );
                }

                $productIds = [];
                foreach ($requirements as $required) {
                    $variant = ProductVariant::query()
                        ->where('product_id', $required['product_id'])
                        ->whereRaw('LOWER(size) = ?', [strtolower($required['size'])])
                        ->whereRaw('LOWER(color) = ?', [strtolower($required['color'])])
                        ->lockForUpdate()
                        ->first();

                    if (!$variant) {
                        throw new RuntimeException(
                            "Tidak dapat mengembalikan stok varian {$required['color']} / {$required['size']} untuk pesanan {$lockedOrder->order_number}."
                        );
                    }

                    $variant->increment('stock', $required['quantity']);
                    $productIds[] = $required['product_id'];
                }
            }

            $lockedOrder->update($updates + ['inventory_deducted' => false]);
            $this->syncProductTotals($productIds);

            return true;
        });
    }

    private function requirements(Collection $items): array
    {
        $requirements = [];

        foreach ($items as $item) {
            $size = (string) $item->size;
            $color = (string) $item->color;
            $key = serialize([$item->product_id, $size, $color]);

            if (!isset($requirements[$key])) {
                $requirements[$key] = [
                    'product_id' => $item->product_id,
                    'size' => $size,
                    'color' => $color,
                    'quantity' => 0,
                    'product' => $item->product,
                ];
            }

            $requirements[$key]['quantity'] += (int) $item->quantity;
        }

        ksort($requirements);

        return array_values($requirements);
    }

    private function syncProductTotals(array $productIds): void
    {
        foreach (array_unique($productIds) as $productId) {
            Product::query()
                ->whereKey($productId)
                ->update(['stock' => ProductVariant::query()->where('product_id', $productId)->sum('stock')]);
        }
    }
}
