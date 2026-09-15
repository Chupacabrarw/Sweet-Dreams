<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tentang', function () {
    return view('tentang');
})->name('tentang');

use App\Http\Controllers\AuthController;

Route::post('/api/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/api/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/api/logout', [AuthController::class, 'logout'])->name('api.logout');
Route::post('/api/check-email', [AuthController::class, 'checkEmail'])->name('api.check-email');
Route::post('/api/reset-password', [AuthController::class, 'resetPassword'])->name('api.reset-password');

if (!function_exists('getCatalogProducts')) {
    function getCatalogProducts() {
        return [
            [
                'id' => 1,
                'slug' => 'baju-tidur-modal-soft-peach',
                'title' => 'Baju Tidur Modal Soft Peach',
                'category' => 'baju-tidur',
                'category_name' => 'Baju Tidur',
                'collection' => 'MODAL COLLECTION',
                'price' => 'Rp 280.000',
                'price_raw' => 280000,
                'original_price' => 'Rp 340.000',
                'discount' => '-18%',
                'rating' => '4.8',
                'review_count' => 95,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['pink', 'cream'],
                'badge' => 'POPULAR',
                'short_desc' => 'Bahan modal lembut, potongan longgar, dan warna peach menenangkan untuk istirahat maksimal.',
                'image' => 'images/katalog-product-1.jpg',
                'created_at' => '2026-08-10',
                'sales_count' => 320,
            ],
            [
                'id' => 2,
                'slug' => 'sailor-rabbit-set',
                'title' => 'Sailor Rabbit Set',
                'category' => 'baju-tidur',
                'category_name' => 'Baju Tidur',
                'collection' => 'SATIN COLLECTION',
                'price' => 'Rp 280.000',
                'price_raw' => 280000,
                'original_price' => 'Rp 350.000',
                'discount' => '-20%',
                'rating' => '4.9',
                'review_count' => 128,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['pink', 'cream', 'grey'],
                'badge' => 'BEST SELLER',
                'short_desc' => 'Bahan katun yang lembut dan adem, dilengkapi bordir kelinci manis untuk kenyamanan tidurmu.',
                'image' => 'images/sailor-rabbit-main.jpg',
                'created_at' => '2026-08-15',
                'sales_count' => 450,
            ],
            [
                'id' => 3,
                'slug' => 'baju-tidur-cotton-floral',
                'title' => 'Baju Tidur Cotton Floral',
                'category' => 'baju-tidur',
                'category_name' => 'Baju Tidur',
                'collection' => 'COTTON COLLECTION',
                'price' => 'Rp 310.000',
                'price_raw' => 310000,
                'original_price' => 'Rp 360.000',
                'discount' => '-14%',
                'rating' => '4.7',
                'review_count' => 84,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['pink', 'white'],
                'badge' => 'NEW',
                'short_desc' => 'Motif bunga kecil yang elegan dengan kancing depan dan kerah yang nyaman.',
                'image' => 'images/katalog-product-2.jpg',
                'created_at' => '2026-08-20',
                'sales_count' => 210,
            ],
            [
                'id' => 4,
                'slug' => 'rose-satin-pajama-set',
                'title' => 'Rosé Satin Pajama Set',
                'category' => 'baju-tidur',
                'category_name' => 'Baju Tidur',
                'collection' => 'ROYAL SILK',
                'price' => 'Rp 489.000',
                'price_raw' => 489000,
                'original_price' => 'Rp 550.000',
                'discount' => '-11%',
                'rating' => '4.9',
                'review_count' => 110,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['pink', 'gold'],
                'badge' => 'PREMIUM',
                'short_desc' => 'Set piyama satin sutra berkilau mewah dengan potongan santai yang flowy dan anggun.',
                'image' => 'images/product-baju-tidur.jpg',
                'created_at' => '2026-08-18',
                'sales_count' => 380,
            ],
            [
                'id' => 5,
                'slug' => 'short-penguin-set',
                'title' => 'Short Penguin Set',
                'category' => 'baju-tidur',
                'category_name' => 'Baju Tidur',
                'collection' => 'COMFORT COLLECTION',
                'price' => 'Rp 349.000',
                'price_raw' => 349000,
                'original_price' => null,
                'discount' => null,
                'rating' => '4.8',
                'review_count' => 76,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['pink', 'grey'],
                'badge' => 'FAVORITE',
                'short_desc' => 'Set celana pendek santai berbahan katun organik breathable dengan motif penguin imut.',
                'image' => 'images/cart-item-penguin.jpg',
                'created_at' => '2026-08-12',
                'sales_count' => 275,
            ],
            [
                'id' => 6,
                'slug' => 'lingerie-set-lace',
                'title' => 'Lingerie Set Lace',
                'category' => 'lingerie',
                'category_name' => 'Lingerie',
                'collection' => 'LACE COLLECTION',
                'price' => 'Rp 320.000',
                'price_raw' => 320000,
                'original_price' => 'Rp 380.000',
                'discount' => '-16%',
                'rating' => '4.9',
                'review_count' => 142,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['pink', 'white', 'grey'],
                'badge' => 'BEST SELLER',
                'short_desc' => 'Set lingerie dengan detail lace prancis yang manis, feminin, dan sangat nyaman di kulit.',
                'image' => 'images/katalog-product-4.jpg',
                'created_at' => '2026-08-14',
                'sales_count' => 420,
            ],
            [
                'id' => 7,
                'slug' => 'amour-lace-bralette',
                'title' => 'Amour Lace Bralette',
                'category' => 'lingerie',
                'category_name' => 'Lingerie',
                'collection' => 'LACE COLLECTION',
                'price' => 'Rp 329.000',
                'price_raw' => 329000,
                'original_price' => null,
                'discount' => null,
                'rating' => '4.8',
                'review_count' => 68,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['white', 'pink'],
                'badge' => 'TRENDING',
                'short_desc' => 'Bralette tanpa kawat dengan renda elastis halus dan tali silang punggung yang mempesona.',
                'image' => 'images/product-lingerie-set.jpg',
                'created_at' => '2026-08-19',
                'sales_count' => 260,
            ],
            [
                'id' => 8,
                'slug' => 'silk-classic-slip-nightgown',
                'title' => 'Silk Classic Slip Nightgown',
                'category' => 'lingerie',
                'category_name' => 'Lingerie',
                'collection' => 'ROYAL SILK',
                'price' => 'Rp 389.000',
                'price_raw' => 389000,
                'original_price' => 'Rp 459.000',
                'discount' => '-15%',
                'rating' => '4.9',
                'review_count' => 165,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['gold', 'cream', 'pink'],
                'badge' => 'BEST SELLER',
                'short_desc' => 'Gaun tidur sutra klasik model slip dengan tali spaghetti yang dapat disesuaikan dan belahan samping.',
                'image' => 'images/rel-prod-1.jpg',
                'created_at' => '2026-08-08',
                'sales_count' => 510,
            ],
            [
                'id' => 9,
                'slug' => 'lace-camisole-set',
                'title' => 'Lace Camisole Set',
                'category' => 'lingerie',
                'category_name' => 'Lingerie',
                'collection' => 'MODAL COLLECTION',
                'price' => 'Rp 289.000',
                'price_raw' => 289000,
                'original_price' => 'Rp 340.000',
                'discount' => '-15%',
                'rating' => '4.8',
                'review_count' => 112,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['gold', 'cream'],
                'badge' => 'POPULAR',
                'short_desc' => 'Set kamisol sutra beraksen renda cantik pada garis leher dengan celana pendek senada.',
                'image' => 'images/cart-item-camisole.jpg',
                'created_at' => '2026-08-05',
                'sales_count' => 340,
            ],
            [
                'id' => 10,
                'slug' => 'kimono-silk-premium',
                'title' => 'Kimono Silk Premium',
                'category' => 'kimono',
                'category_name' => 'Kimono',
                'collection' => 'ROYAL SILK',
                'price' => 'Rp 450.000',
                'price_raw' => 450000,
                'original_price' => 'Rp 520.000',
                'discount' => '-15%',
                'rating' => '5.0',
                'review_count' => 156,
                'sizes' => ['S', 'M', 'L', 'XL', 'XXL'],
                'colors' => ['pink', 'gold', 'white'],
                'badge' => 'BEST SELLER',
                'short_desc' => 'Kimono ringan dengan bahan silk yang lembut dan tampilan mewah untuk momen santai di rumah.',
                'image' => 'images/product-kimono-silk.jpg',
                'created_at' => '2026-08-16',
                'sales_count' => 490,
            ],
            [
                'id' => 11,
                'slug' => 'lace-trim-kimono-robe',
                'title' => 'Lace Trim Kimono Robe',
                'category' => 'kimono',
                'category_name' => 'Kimono',
                'collection' => 'ROYAL SILK',
                'price' => 'Rp 399.000',
                'price_raw' => 399000,
                'original_price' => 'Rp 460.000',
                'discount' => '-13%',
                'rating' => '4.8',
                'review_count' => 92,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['cream', 'white', 'grey'],
                'badge' => 'NEW',
                'short_desc' => 'Jubah tidur kimono beraksen renda chantilly pada lengan lebar dan keliman bawah.',
                'image' => 'images/katalog-product-3.jpg',
                'created_at' => '2026-08-21',
                'sales_count' => 230,
            ],
            [
                'id' => 12,
                'slug' => 'royal-satin-long-robe',
                'title' => 'Royal Satin Long Robe',
                'category' => 'kimono',
                'category_name' => 'Kimono',
                'collection' => 'ROYAL SILK',
                'price' => 'Rp 520.000',
                'price_raw' => 520000,
                'original_price' => 'Rp 620.000',
                'discount' => '-16%',
                'rating' => '4.9',
                'review_count' => 115,
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['gold', 'grey'],
                'badge' => 'PREMIUM',
                'short_desc' => 'Jubah tidur panjang berbahan satin tebal bermutu tinggi dengan sentuhan drape anggun paripurna.',
                'image' => 'images/katalog-product-5.jpg',
                'created_at' => '2026-08-17',
                'sales_count' => 310,
            ],
            [
                'id' => 13,
                'slug' => 'seamless-comfort-panty-set',
                'title' => 'Seamless Comfort Panty Set',
                'category' => 'pakaian-dalam',
                'category_name' => 'Pakaian Dalam',
                'collection' => 'COMFORT COLLECTION',
                'price' => 'Rp 180.000',
                'price_raw' => 180000,
                'original_price' => 'Rp 220.000',
                'discount' => '-18%',
                'rating' => '4.9',
                'review_count' => 210,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['cream', 'pink', 'grey'],
                'badge' => 'BEST SELLER',
                'short_desc' => 'Set 3 celana dalam laser cut tanpa jahitan pinggir, tidak menjiplak di pakaian dan anti-iritasi.',
                'image' => 'images/rel-prod-2.jpg',
                'created_at' => '2026-08-07',
                'sales_count' => 620,
            ],
            [
                'id' => 14,
                'slug' => 'soft-wireless-cotton-bra',
                'title' => 'Soft Wireless Cotton Bra',
                'category' => 'pakaian-dalam',
                'category_name' => 'Pakaian Dalam',
                'collection' => 'COTTON COLLECTION',
                'price' => 'Rp 225.000',
                'price_raw' => 225000,
                'original_price' => 'Rp 275.000',
                'discount' => '-18%',
                'rating' => '4.8',
                'review_count' => 88,
                'sizes' => ['S', 'M', 'L', 'XL'],
                'colors' => ['white', 'cream'],
                'badge' => 'POPULAR',
                'short_desc' => 'Bra katun alami tanpa kawat dengan busa tipis bernapas untuk penopang yang relaks sepanjang hari.',
                'image' => 'images/rel-prod-3.jpg',
                'created_at' => '2026-08-11',
                'sales_count' => 290,
            ],
            [
                'id' => 15,
                'slug' => 'pure-modal-high-waist-brief',
                'title' => 'Pure Modal High-Waist Brief',
                'category' => 'pakaian-dalam',
                'category_name' => 'Pakaian Dalam',
                'collection' => 'MODAL COLLECTION',
                'price' => 'Rp 160.000',
                'price_raw' => 160000,
                'original_price' => null,
                'discount' => null,
                'rating' => '4.7',
                'review_count' => 74,
                'sizes' => ['M', 'L', 'XL', 'XXL'],
                'colors' => ['pink', 'grey', 'cream'],
                'badge' => 'COMFORT',
                'short_desc' => 'Celana dalam high-waist dari serat modal lembut yang memeluk perut dengan elastisitas alami.',
                'image' => 'images/rel-prod-4.jpg',
                'created_at' => '2026-08-09',
                'sales_count' => 180,
            ],
            [
                'id' => 16,
                'slug' => 'silk-touch-lace-panty',
                'title' => 'Silk Touch Lace Panty',
                'category' => 'pakaian-dalam',
                'category_name' => 'Pakaian Dalam',
                'collection' => 'ROYAL SILK',
                'price' => 'Rp 195.000',
                'price_raw' => 195000,
                'original_price' => 'Rp 240.000',
                'discount' => '-19%',
                'rating' => '4.9',
                'review_count' => 130,
                'sizes' => ['S', 'M', 'L'],
                'colors' => ['white', 'pink', 'gold'],
                'badge' => 'HOT',
                'short_desc' => 'Celana dalam sentuhan sutra berhias renda halus pada bagian depan dan pinggang elastis.',
                'image' => 'images/katalog-product-6.jpg',
                'created_at' => '2026-08-13',
                'sales_count' => 350,
            ],
        ];
    }
}

Route::get('/katalog/{category?}', function ($category = null) {
    $products = getCatalogProducts();
    $validCategories = ['baju-tidur', 'lingerie', 'kimono', 'pakaian-dalam'];
    $activeCategory = in_array($category, $validCategories) ? $category : null;

    return view('katalog', [
        'products' => $products,
        'activeCategory' => $activeCategory,
    ]);
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

    // Check if slug matches any catalog product to create a rich detail page dynamically
    $catalogList = getCatalogProducts();
    $foundInCatalog = null;
    foreach ($catalogList as $cp) {
        if ($cp['slug'] === $slug) {
            $foundInCatalog = $cp;
            break;
        }
    }

    if ($foundInCatalog && !isset($products[$slug])) {
        $products[$slug] = [
            'slug' => $foundInCatalog['slug'],
            'title' => $foundInCatalog['title'],
            'category' => $foundInCatalog['category_name'],
            'collection' => $foundInCatalog['collection'] ?? 'SWEET DREAMS SIGNATURE',
            'price' => $foundInCatalog['price'],
            'price_raw' => $foundInCatalog['price_raw'],
            'original_price' => $foundInCatalog['original_price'] ?? null,
            'discount' => $foundInCatalog['discount'] ?? null,
            'rating' => $foundInCatalog['rating'],
            'review_count' => (string)$foundInCatalog['review_count'],
            'short_desc' => $foundInCatalog['short_desc'],
            'badge' => $foundInCatalog['badge'],
            'main_image' => $foundInCatalog['image'],
            'gallery' => [
                $foundInCatalog['image'],
                'images/sailor-rabbit-thumb2.jpg',
                'images/sailor-rabbit-thumb3.jpg',
            ],
            'colors' => array_map(function($c) {
                $hexMap = [
                    'pink' => '#e8a0b0',
                    'gold' => '#d4a854',
                    'white' => '#f5f0ec',
                    'cream' => '#eedfc8',
                    'grey' => '#6a6a7a'
                ];
                return [
                    'name' => ucfirst($c),
                    'hex' => $hexMap[$c] ?? '#e8a0b0',
                    'active' => false
                ];
            }, $foundInCatalog['colors']),
            'sizes' => $foundInCatalog['sizes'],
            'default_size' => $foundInCatalog['sizes'][0] ?? 'M',
            'long_desc_title' => 'Kemewahan & Kenyamanan Terbaik',
            'long_desc' => $foundInCatalog['short_desc'] . ' Dirancang dengan material terpilih berstandar internasional yang menjamin kenyamanan maksimal saat Anda beristirahat di rumah.',
            'features' => [
                'Bahan adem, lembut dan sangat ramah di kulit',
                'Jahitan presisi dan kuat untuk daya tahan pemakaian harian',
                'Warna tahan luntur meski dicuci berulang kali',
                'Hypoallergenic dan aman untuk kulit sensitif',
            ],
        ];
    }

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
            'slug' => 'SD-240812',
            'title' => 'Rosé Satin Pajama Set',
            'status' => 'Sedang dikirim',
            'status_type' => 'shipping',
            'price' => 'Rp 489.000',
        ],
        [
            'id' => '#SD-240728',
            'slug' => 'SD-240728',
            'title' => 'Amour Lace Bralette',
            'status' => 'Selesai',
            'status_type' => 'completed',
            'price' => 'Rp 329.000',
        ],
    ];

    $myOrders = [
        [
            'id' => '#SD-240812',
            'slug' => 'SD-240812',
            'date' => '12 Agu 2026',
            'status' => 'Sedang dikirim',
            'status_type' => 'shipping',
            'title' => 'Short Penguin Set',
            'variant' => 'Dusty Rose, Size M',
            'qty' => '1x',
            'price' => 'Rp 349.000',
            'total' => 'Rp 663.000',
            'image' => 'images/cart-item-penguin.jpg',
        ],
        [
            'id' => '#SD-240728',
            'slug' => 'SD-240728',
            'date' => '28 Jul 2026',
            'status' => 'Selesai',
            'status_type' => 'completed',
            'title' => 'Lace Camisole Set',
            'variant' => 'Champagne, Size S',
            'qty' => '1x',
            'price' => 'Rp 289.000',
            'total' => 'Rp 314.000',
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

// ===== ORDER TRACKING DETAIL =====
Route::get('/pesanan/{id}', function ($id) {
    $ordersDB = [
        'SD-240812' => [
            'order_id' => '#SD-240812',
            'headline' => 'Paket cantikmu sedang menuju rumah',
            'subtitle' => 'Kami akan menjaga setiap langkah perjalanan pesananmu tetap jelas dan tenang.',
            'status' => 'Sedang dikirim',
            'status_type' => 'shipping',
            'courier' => [
                'name' => 'J&T Express',
                'resi' => 'JP1234567890',
                'service' => 'Reguler',
            ],
            'progress' => [
                [
                    'label' => 'Diproses',
                    'date' => '12 Agu, 09:24',
                    'completed' => true,
                ],
                [
                    'label' => 'Dikirim',
                    'date' => '13 Agu, 16:40',
                    'completed' => true,
                ],
                [
                    'label' => 'Selesai',
                    'date' => 'Estimasi 15 Agu',
                    'completed' => false,
                ],
            ],
            'current_step' => 1, // 0-indexed: 0=Diproses, 1=Dikirim, 2=Selesai
            'latest_update' => [
                'text' => 'Paket meninggalkan sorting center Jakarta Barat',
                'time' => 'Hari ini, 07:18 WIB',
            ],
            'timeline' => [
                [
                    'date' => '14 Agu 2026',
                    'events' => [
                        ['time' => '07:18', 'text' => 'Paket meninggalkan sorting center Jakarta Barat'],
                        ['time' => '03:45', 'text' => 'Paket tiba di sorting center Jakarta Barat'],
                    ],
                ],
                [
                    'date' => '13 Agu 2026',
                    'events' => [
                        ['time' => '16:40', 'text' => 'Paket telah dipickup oleh kurir'],
                        ['time' => '14:20', 'text' => 'Paket siap dikirim dari gudang Sweet Dreams'],
                        ['time' => '09:15', 'text' => 'Pengemasan pesanan selesai'],
                    ],
                ],
                [
                    'date' => '12 Agu 2026',
                    'events' => [
                        ['time' => '09:24', 'text' => 'Pesanan dikonfirmasi dan sedang diproses'],
                        ['time' => '09:00', 'text' => 'Pembayaran diterima'],
                    ],
                ],
            ],
            'products' => [
                [
                    'title' => 'Short Penguin Set',
                    'variant' => 'Dusty Rose · M · 1 item',
                    'price' => 'Rp 349.000',
                    'image' => 'images/cart-item-penguin.jpg',
                ],
                [
                    'title' => 'Lace Camisole Set',
                    'variant' => 'Dusty Rose · M · 1 item',
                    'price' => 'Rp 289.000',
                    'image' => 'images/cart-item-camisole.jpg',
                ],
            ],
            'summary' => [
                'subtotal' => 'Rp 638.000',
                'shipping' => 'Rp 25.000',
                'total' => 'Rp 663.000',
            ],
        ],
        'SD-240728' => [
            'order_id' => '#SD-240728',
            'headline' => 'Pesananmu telah sampai dengan selamat',
            'subtitle' => 'Terima kasih sudah berbelanja di Sweet Dreams. Semoga kamu menyukainya!',
            'status' => 'Selesai',
            'status_type' => 'completed',
            'courier' => [
                'name' => 'SiCepat',
                'resi' => 'SC9876543210',
                'service' => 'Express',
            ],
            'progress' => [
                [
                    'label' => 'Diproses',
                    'date' => '28 Jul, 10:00',
                    'completed' => true,
                ],
                [
                    'label' => 'Dikirim',
                    'date' => '29 Jul, 08:30',
                    'completed' => true,
                ],
                [
                    'label' => 'Selesai',
                    'date' => '30 Jul, 14:20',
                    'completed' => true,
                ],
            ],
            'current_step' => 2,
            'latest_update' => [
                'text' => 'Paket telah diterima oleh penerima',
                'time' => '30 Jul, 14:20 WIB',
            ],
            'timeline' => [
                [
                    'date' => '30 Jul 2026',
                    'events' => [
                        ['time' => '14:20', 'text' => 'Paket telah diterima oleh penerima'],
                        ['time' => '10:00', 'text' => 'Paket sedang dalam proses pengantaran ke alamat tujuan'],
                    ],
                ],
                [
                    'date' => '29 Jul 2026',
                    'events' => [
                        ['time' => '08:30', 'text' => 'Paket telah dipickup oleh kurir'],
                        ['time' => '07:00', 'text' => 'Paket siap dikirim dari gudang Sweet Dreams'],
                    ],
                ],
                [
                    'date' => '28 Jul 2026',
                    'events' => [
                        ['time' => '10:00', 'text' => 'Pesanan dikonfirmasi dan sedang diproses'],
                        ['time' => '09:45', 'text' => 'Pembayaran diterima'],
                    ],
                ],
            ],
            'products' => [
                [
                    'title' => 'Lace Camisole Set',
                    'variant' => 'Champagne · S · 1 item',
                    'price' => 'Rp 289.000',
                    'image' => 'images/cart-item-camisole.jpg',
                ],
            ],
            'summary' => [
                'subtotal' => 'Rp 289.000',
                'shipping' => 'Rp 25.000',
                'total' => 'Rp 314.000',
            ],
        ],
    ];

    $order = $ordersDB[$id] ?? $ordersDB['SD-240812'];

    return view('pesanan-detail', ['order' => $order]);
})->name('pesanan.detail');

Route::get('/login', function () {
    return view('auth.login', ['initialMode' => 'login']);
})->name('login');

Route::get('/register', function () {
    return view('auth.login', ['initialMode' => 'register']);
})->name('register');

Route::get('/logout', function () {
    return view('auth.logout');
})->name('logout');
