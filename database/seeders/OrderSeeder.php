<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();
        if (!$user) {
            $user = User::first();
        }

        $products = Product::inRandomOrder()->take(3)->get();
        
        if ($products->isEmpty()) {
            return;
        }

        // Create 3 orders
        $statuses = ['pending', 'processing', 'shipped', 'completed'];

        for ($i = 0; $i < 5; $i++) {
            $subtotal = 0;
            $orderItems = [];

            // Add 1-2 random products to order
            $orderProducts = $products->random(rand(1, 2));

            foreach ($orderProducts as $product) {
                $qty = rand(1, 3);
                $price = $product->price;
                $itemSubtotal = $price * $qty;
                $subtotal += $itemSubtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_title' => $product->title,
                    'product_image' => $product->image,
                    'size' => collect(['S', 'M', 'L'])->random(),
                    'color' => collect(['Hitam', 'Putih', 'Biru'])->random(),
                    'price' => $price,
                    'quantity' => $qty,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $shippingCost = 25000;
            $total = $subtotal + $shippingCost;
            $status = collect($statuses)->random();

            $order = Order::create([
                'order_number' => 'SD-' . now()->format('ymd') . '-' . strtoupper(Str::random(4)),
                'user_id' => $user->id,
                'shipping_recipient_name' => $user->name,
                'shipping_phone' => '081234567890',
                'shipping_address' => 'Jl. Merdeka No. 10',
                'shipping_city' => 'Jakarta Selatan',
                'shipping_province' => 'DKI Jakarta',
                'shipping_postal_code' => '12345',
                'shipping_courier' => 'SiCepat Express',
                'shipping_service' => 'sicepat',
                'shipping_cost' => $shippingCost,
                'subtotal' => $subtotal,
                'discount' => 0,
                'total' => $total,
                'status' => $status,
                'payment_method' => 'qris',
                'payment_status' => $status === 'pending' ? 'unpaid' : 'paid',
            ]);

            foreach ($orderItems as $item) {
                $item['order_id'] = $order->id;
                OrderItem::create($item);
            }
        }
    }
}
