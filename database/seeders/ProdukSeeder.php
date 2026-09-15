<?php

namespace Database\Seeders;

use App\Models\Produk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        Produk::create([
            'slug' => 'baju-tidur-modal-soft-peach',
            'title' => 'Baju Tidur Modal Soft Peach',
            'category' => 'Baju Tidur',
            'description' => 'Bahan modal lembut, potongan longgar, dan warna pastel yang menenangkan.',
            'price' => 280000,
            'original_price' => 320000,
            'stock' => 10,
            'image' => 'images/katalog-product-1.jpg',
            'main_image' => 'images/katalog-product-1.jpg',
            'gallery' => [
                'images/katalog-product-1.jpg',
            ],
            'badge' => 'BEST SELLER',
            'review_count' => 128,
            'collection' => 'Sleepwear Collection',
            'short_desc' => 'Bahan modal lembut, potongan longgar, dan warna pastel yang menenangkan.',
            'colors' => [
                ['name' => 'Red', 'hex' => '#c0392b', 'active' => true],
                ['name' => 'Peach', 'hex' => '#f5c6a5', 'active' => false],
            ],
            'sizes' => ['S', 'M', 'L', 'XL'],
            'default_size' => 'M',
            'long_desc_title' => 'Tentang Produk Ini',
            'long_desc' => 'Baju tidur modal soft peach ini dibuat dari bahan modal premium yang lembut di kulit dan adem sepanjang malam. Potongannya longgar dan nyaman dipakai untuk tidur maupun santai di rumah.',
            'features' => [
                'Bahan modal 100% premium',
                'Jahitan rapi anti gulung',
                'Cocok untuk cuaca tropis',
            ],
        ]);

        Produk::create([
            'slug' => 'sailor-rabbit-set',
            'title' => 'Sailor Rabbit Set',
            'category' => 'Baju Tidur',
            'description' => 'Bahan katun yang lembut dan adem, dilengkapi bordir kelinci manis untuk kenyamanan tidurmu.',
            'price' => 280000,
            'original_price' => null,
            'stock' => 15,
            'image' => 'images/sailor-rabbit-main.jpg',
            'main_image' => 'images/sailor-rabbit-main.jpg',
            'gallery' => [
                'images/sailor-rabbit-main.jpg',
            ],
            'badge' => 'NEW',
            'review_count' => 84,
            'collection' => 'Sailor Collection',
            'short_desc' => 'Bahan katun yang lembut dan adem, dilengkapi bordir kelinci manis untuk kenyamanan tidurmu.',
            'colors' => [
                ['name' => 'Dusty Rose', 'hex' => '#d4a5a5', 'active' => true],
                ['name' => 'Navy', 'hex' => '#2c3e50', 'active' => false],
            ],
            'sizes' => ['S', 'M', 'L'],
            'default_size' => 'S',
            'long_desc_title' => 'Tentang Produk Ini',
            'long_desc' => 'Sailor Rabbit Set terbuat dari katun lembut dengan bordir kelinci yang manis, cocok untuk menemani tidurmu dengan gaya yang menggemaskan.',
            'features' => [
                'Bahan katun 100% lembut',
                'Bordir kelinci eksklusif',
                'Nyaman untuk kulit sensitif',
            ],
        ]);

        Produk::create([
            'slug' => 'kimono-silk-premium',
            'title' => 'Kimono Silk Premium',
            'category' => 'Kimono',
            'description' => 'Kimono ringan dengan bahan silk yang lembut dan tampilan elegan untuk santai.',
            'price' => 450000,
            'original_price' => 520000,
            'stock' => 8,
            'image' => 'images/katalog-product-3.jpg',
            'main_image' => 'images/katalog-product-3.jpg',
            'gallery' => [
                'images/katalog-product-3.jpg',
            ],
            'badge' => 'PREMIUM',
            'review_count' => 56,
            'collection' => 'Silk Collection',
            'short_desc' => 'Kimono ringan dengan bahan silk yang lembut dan tampilan elegan untuk santai.',
            'colors' => [
                ['name' => 'Champagne', 'hex' => '#f7e7ce', 'active' => true],
                ['name' => 'Blush', 'hex' => '#de9fa3', 'active' => false],
            ],
            'sizes' => ['S', 'M', 'L', 'XL'],
            'default_size' => 'M',
            'long_desc_title' => 'Tentang Produk Ini',
            'long_desc' => 'Kimono Silk Premium dibuat dari bahan silk berkualitas tinggi, ringan dipakai dan memberikan tampilan elegan untuk momen santai di rumah.',
            'features' => [
                'Bahan silk premium',
                'Jatuh kain elegan',
                'Cocok untuk hadiah',
            ],
        ]);
    }
}