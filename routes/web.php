<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/katalog/{category?}', function ($category = null) {
    return view('katalog', ['activeCategory' => $category]);
})->name('katalog');

Route::get('/produk/{slug?}', function ($slug = 'sailor-rabbit-set') {
    $products = [
        'sailor-rabbit-set' => [
            'slug' => 'sailor-rabbit-set',
            'title' => 'Sailor Rabbit Set',
            'category' => 'Baju Tidur',
            'collection' => 'SATIN COLLECTION',
            'price' => 'Rp 280.000',
            'price_raw' => 280000,
            'original_price' => 'Rp 350.000',
            'discount' => '-20%',
            'rating' => '4.9',
            'review_count' => '128',
            'short_desc' => 'Bahan katun yang lembut dan adem, dilengkapi bordir kelinci manis untuk kenyamanan tidurmu.',
            'badge' => 'BEST SELLER',
            'main_image' => 'images/sailor-rabbit-main.jpg',
            'gallery' => [
                'images/sailor-rabbit-thumb1.jpg',
                'images/sailor-rabbit-thumb2.jpg',
                'images/sailor-rabbit-thumb3.jpg',
            ],
            'colors' => [
                ['name' => 'Red', 'hex' => '#9e1b32', 'active' => true],
                ['name' => 'Cream', 'hex' => '#eedfc8', 'active' => false],
                ['name' => 'Charcoal', 'hex' => '#4a2e35', 'active' => false],
            ],
            'sizes' => ['S', 'M', 'L', 'XL'],
            'default_size' => 'M',
            'long_desc_title' => 'Kemewahan Sutra di Setiap Sentuhan',
            'long_desc' => 'Terbuat dari material premium blend (95% Natural Silk, 5% Elastane) yang memberikan elastisitas mikro saat bergerak. Bahan mengalir indah mengikuti bentuk lekuk tubuh, sangat ringan, berpori, dan menyejukkan kulit sepanjang malam.',
            'features' => [
                'Detail renda brokat chantilly berkualitas tinggi di bagian lengan & tepi bawah',
                'Tali ikat pinggang sutra yang dapat disesuaikan dan dilepas',
                'Side slit (belahan samping) yang anggun untuk kenyamanan maksimal saat melangkah',
                'Anti-static, tidak lengket di kulit, hipoalergenik, dan aman untuk kulit sensitif',
            ],
        ],
        'baju-tidur-modal-soft-peach' => [
            'slug' => 'baju-tidur-modal-soft-peach',
            'title' => 'Baju Tidur Modal Soft Peach',
            'category' => 'Baju Tidur',
            'collection' => 'MODAL COLLECTION',
            'price' => 'Rp 280.000',
            'price_raw' => 280000,
            'original_price' => 'Rp 340.000',
            'discount' => '-18%',
            'rating' => '4.8',
            'review_count' => '95',
            'short_desc' => 'Bahan modal lembut, potongan longgar, dan warna peach menenangkan untuk istirahat maksimal.',
            'badge' => 'POPULAR',
            'main_image' => 'images/katalog-product-1.jpg',
            'gallery' => [
                'images/katalog-product-1.jpg',
                'images/sailor-rabbit-thumb2.jpg',
                'images/sailor-rabbit-thumb3.jpg',
            ],
            'colors' => [
                ['name' => 'Peach', 'hex' => '#e8a0b0', 'active' => true],
                ['name' => 'Cream', 'hex' => '#eedfc8', 'active' => false],
            ],
            'sizes' => ['S', 'M', 'L', 'XL'],
            'default_size' => 'M',
            'long_desc_title' => 'Kenyamanan Alami Serat Modal Pilihan',
            'long_desc' => 'Dirancang dengan serat modal premium yang memiliki sirkulasi udara 50% lebih baik dari katun biasa. Sentuhan super halus di kulit dan daya serap tinggi untuk tidur pulas semalaman.',
            'features' => [
                'Bahan 100% Austrian Lenzing Modal bersertifikasi',
                'Potongan relaks anti-sesak dan elastis lembut',
                'Warna tahan luntur meski dicuci berulang kali',
                'Bebas dari bahan kimia berbahaya (OEKO-TEX Certified)',
            ],
        ],
        'kimono-silk-premium' => [
            'slug' => 'kimono-silk-premium',
            'title' => 'Kimono Silk Premium',
            'category' => 'Kimono',
            'collection' => 'ROYAL SILK',
            'price' => 'Rp 450.000',
            'price_raw' => 450000,
            'original_price' => 'Rp 520.000',
            'discount' => '-15%',
            'rating' => '5.0',
            'review_count' => '156',
            'short_desc' => 'Kimono ringan dengan bahan silk yang lembut dan tampilan mewah untuk momen santai di rumah.',
            'badge' => 'BEST SELLER',
            'main_image' => 'images/product-kimono-silk.jpg',
            'gallery' => [
                'images/product-kimono-silk.jpg',
                'images/katalog-product-3.jpg',
                'images/katalog-product-5.jpg',
            ],
            'colors' => [
                ['name' => 'Rose Silk', 'hex' => '#b83b5e', 'active' => true],
                ['name' => 'Champagne', 'hex' => '#d4a854', 'active' => false],
                ['name' => 'Ivory', 'hex' => '#f5f0ec', 'active' => false],
            ],
            'sizes' => ['S', 'M', 'L', 'XL'],
            'default_size' => 'M',
            'long_desc_title' => 'Kemewahan Sutra Murni Kelas Dunia',
            'long_desc' => 'Kimono sutra dengan drape yang luar biasa anggun. Menghadirkan siluet tubuh yang memukau tanpa mengorbankan kenyamanan bersantai.',
            'features' => [
                'Bahan sutra mulberry 22 momme berkualitas tinggi',
                'Termasuk sabuk sutra lebar yang dapat disesuaikan',
                'Jahitan rapi khas haute couture',
                'Sangat sejuk di musim panas, hangat di ruangan ber-AC',
            ],
        ],
    ];

    // Slug aliases
    if ($slug === 'baju-tidur-modal') {
        $slug = 'baju-tidur-modal-soft-peach';
    }
    if ($slug === 'lingerie-set-lace' || $slug === 'rabbit-set-silk') {
        $slug = 'sailor-rabbit-set';
    }

    $product = $products[$slug] ?? $products['sailor-rabbit-set'];

    $relatedProducts = [
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-1.jpg',
            'discount' => '-12%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-2.jpg',
            'discount' => '-12%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-3.jpg',
            'discount' => '-12%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-4.jpg',
            'discount' => '-12%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
    ];

    return view('produk-detail', [
        'product' => $product,
        'relatedProducts' => $relatedProducts,
    ]);
})->name('produk.detail');

Route::get('/keranjang', function () {
    $cartItems = [
        [
            'id' => 'item-1',
            'title' => 'Short Penguin Set',
            'variant' => 'Dusty Rose · Size M',
            'price' => 349000,
            'qty' => 1,
            'image' => 'images/cart-item-penguin.jpg',
            'slug' => 'sailor-rabbit-set',
        ],
        [
            'id' => 'item-2',
            'title' => 'Lace Camisole Set',
            'variant' => 'Champagne · Size S',
            'price' => 289000,
            'qty' => 1,
            'image' => 'images/cart-item-camisole.jpg',
            'slug' => 'sailor-rabbit-set',
        ],
    ];

    $recommendedProducts = [
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-1.jpg',
            'discount' => '-13%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-2.jpg',
            'discount' => '-13%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-3.jpg',
            'discount' => '-13%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
        [
            'slug' => 'sailor-rabbit-set',
            'name' => 'Silk Classic Slip Nightgown',
            'image' => 'images/rel-prod-4.jpg',
            'discount' => '-13%',
            'price' => 'Rp 389.000',
            'original_price' => 'Rp 459.000',
        ],
    ];

    return view('keranjang', [
        'cartItems' => $cartItems,
        'recommendedProducts' => $recommendedProducts,
    ]);
})->name('keranjang');

Route::get('/checkout', function () {
    $checkoutItems = [
        [
            'id' => 'item-1',
            'title' => 'Short Penguin Set',
            'variant' => 'Dusty Rose · Size M · Qty: 1',
            'price' => 349000,
            'image' => 'images/cart-item-penguin.jpg',
        ],
        [
            'id' => 'item-2',
            'title' => 'Lace Camisole Set',
            'variant' => 'Champagne · Size S · Qty: 2',
            'price' => 289000,
            'image' => 'images/cart-item-camisole.jpg',
        ],
    ];

    $shippingMethods = [
        [
            'id' => 'jne',
            'name' => 'JNE Regular',
            'desc' => 'Estimasi pengiriman 3–5 hari kerja',
            'cost' => 15000,
            'active' => false,
        ],
        [
            'id' => 'sicepat',
            'name' => 'SiCepat Express',
            'desc' => 'Estimasi pengiriman 1–2 hari kerja',
            'cost' => 25000,
            'active' => true,
        ],
        [
            'id' => 'gosend',
            'name' => 'GoSend Same Day',
            'desc' => 'Estimasi tiba dalam 2–4 jam',
            'cost' => 35000,
            'active' => false,
        ],
    ];

    $paymentMethods = [
        [
            'id' => 'qris',
            'name' => 'Qris',
            'desc' => 'Mendukung pembayaran dari berbagai aplikasi',
            'badges' => 'BCA MANDIRI BNI',
            'active' => true,
        ],
        [
            'id' => 'ewallet',
            'name' => 'E-Wallet',
            'desc' => 'Bayar instan pakai GoPay, OVO, atau DANA',
            'badges' => 'GOPAY OVO DANA',
            'active' => false,
        ],
        [
            'id' => 'transfer',
            'name' => 'Transfer Bank',
            'desc' => 'Transfer via BCA, Mandiri, atau BNI',
            'badges' => '',
            'active' => false,
        ],
    ];

    return view('checkout', [
        'checkoutItems' => $checkoutItems,
        'shippingMethods' => $shippingMethods,
        'paymentMethods' => $paymentMethods,
        'subtotal' => 638000,
        'shippingCost' => 25000,
        'discount' => 68700,
        'total' => 643700,
    ]);
})->name('checkout');

Route::get('/profil', function () {
    $user = [
        'name' => 'Alya Putri',
        'email' => 'alya.putri@email.com',
        'phone' => '0812 3456 7890',
        'birthdate' => '17 Mei 1997',
        'city' => 'Purwokerto Timur',
        'avatar' => 'images/alya-avatar.jpg',
    ];

    $stats = [
        'total_orders' => 2,
        'in_delivery' => 1,
        'wishlist_count' => 12,
    ];

    $recentOrders = [
        [
            'id' => '#SD-240812',
            'title' => 'Rosé Satin Pajama Set',
            'status' => 'Sedang dikirim',
            'status_type' => 'shipping',
            'price' => 'Rp 489.000',
        ],
        [
            'id' => '#SD-240728',
            'title' => 'Amour Lace Bralette',
            'status' => 'Selesai',
            'status_type' => 'completed',
            'price' => 'Rp 329.000',
        ],
    ];

    $myOrders = [
        [
            'id' => '#SD-20260815',
            'date' => '08 Sep 2026',
            'status' => 'Menunggu Pembayaran',
            'status_type' => 'pending',
            'title' => 'Lace Trim Kimono Robe',
            'variant' => 'Creamy White, Size L',
            'qty' => '1x',
            'price' => 'Rp 349.000',
            'total' => 'Rp 349.000',
            'image' => 'images/cart-item-penguin.jpg',
        ],
        [
            'id' => '#SD-20260815',
            'date' => '08 Sep 2026',
            'status' => 'Menunggu Pembayaran',
            'status_type' => 'pending',
            'title' => 'Lace Trim Kimono Robe',
            'variant' => 'Creamy White, Size L',
            'qty' => '1x',
            'price' => 'Rp 289.000',
            'total' => 'Rp 289.000',
            'image' => 'images/cart-item-camisole.jpg',
        ],
    ];

    $addresses = [
        [
            'label' => 'Alamat Utama',
            'name' => 'Alya Putri',
            'phone' => '0812 3456 7890',
            'address' => 'Jl. Kemang Raya No. 45, RT.2/RW.2, Bangka, Kec. Mampang Prapatan, Jakarta Selatan, DKI Jakarta 12730',
            'is_primary' => true,
        ],
        [
            'label' => 'Kantor',
            'name' => 'Alya Putri',
            'phone' => '0812 3456 7890',
            'address' => 'Gedung Menara Sudirman Lt. 14, Jl. Jend. Sudirman Kav. 60, Jakarta Selatan 12190',
            'is_primary' => false,
        ],
    ];

    return view('profil', [
        'user' => $user,
        'stats' => $stats,
        'recentOrders' => $recentOrders,
        'myOrders' => $myOrders,
        'addresses' => $addresses,
    ]);
})->name('profil');




