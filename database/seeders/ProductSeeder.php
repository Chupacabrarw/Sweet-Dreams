<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Baju Tidur', 'slug' => 'baju-tidur'],
            ['name' => 'Lingerie', 'slug' => 'lingerie'],
            ['name' => 'Kimono', 'slug' => 'kimono'],
            ['name' => 'Pakaian Dalam', 'slug' => 'pakaian-dalam'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $colors = [
            ['name' => 'pink', 'slug' => 'pink', 'hex' => '#E8A0B0'],
            ['name' => 'cream', 'slug' => 'cream', 'hex' => '#EEDFC8'],
            ['name' => 'grey', 'slug' => 'grey', 'hex' => '#6A6A7A'],
            ['name' => 'white', 'slug' => 'white', 'hex' => '#F5F0EC'],
            ['name' => 'gold', 'slug' => 'gold', 'hex' => '#D4A854'],
            ['name' => 'dark blue', 'slug' => 'dark-blue', 'hex' => '#27385D'],
        ];

        foreach ($colors as $color) {
            ProductColor::updateOrCreate(['slug' => $color['slug']], $color);
        }

        $products = [
            ['slug' => 'baju-tidur-modal-soft-peach', 'title' => 'Baju Tidur Modal Soft Peach', 'category' => 'baju-tidur', 'collection' => 'MODAL COLLECTION', 'price_raw' => 280000, 'original_price' => 'Rp 340.000', 'discount' => '-18%', 'rating' => '4.8', 'review_count' => 95, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['pink', 'cream'], 'badge' => 'POPULAR', 'short_desc' => 'Bahan modal lembut, potongan longgar, dan warna peach menenangkan untuk istirahat maksimal.', 'image' => 'images/katalog-product-1.jpg', 'created_at' => '2026-08-10', 'sales_count' => 320],
            ['slug' => 'sailor-rabbit-set', 'title' => 'Sailor Rabbit Set', 'category' => 'baju-tidur', 'collection' => 'SATIN COLLECTION', 'price_raw' => 280000, 'original_price' => 'Rp 350.000', 'discount' => '-20%', 'rating' => '4.9', 'review_count' => 128, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['pink', 'cream', 'grey'], 'badge' => 'BEST SELLER', 'short_desc' => 'Bahan katun yang lembut dan adem, dilengkapi bordir kelinci manis untuk kenyamanan tidurmu.', 'image' => 'images/sailor-rabbit-main.jpg', 'created_at' => '2026-08-15', 'sales_count' => 450],
            ['slug' => 'baju-tidur-cotton-floral', 'title' => 'Baju Tidur Cotton Floral', 'category' => 'baju-tidur', 'collection' => 'COTTON COLLECTION', 'price_raw' => 310000, 'original_price' => 'Rp 360.000', 'discount' => '-14%', 'rating' => '4.7', 'review_count' => 84, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['pink', 'white'], 'badge' => 'NEW', 'short_desc' => 'Motif bunga kecil yang elegan dengan kancing depan dan kerah yang nyaman.', 'image' => 'images/katalog-product-2.jpg', 'created_at' => '2026-08-20', 'sales_count' => 210],
            ['slug' => 'rose-satin-pajama-set', 'title' => 'Rosé Satin Pajama Set', 'category' => 'baju-tidur', 'collection' => 'ROYAL SILK', 'price_raw' => 489000, 'original_price' => 'Rp 550.000', 'discount' => '-11%', 'rating' => '4.9', 'review_count' => 110, 'sizes' => ['S', 'M', 'L'], 'colors' => ['pink', 'gold'], 'badge' => 'PREMIUM', 'short_desc' => 'Set piyama satin sutra berkilau mewah dengan potongan santai yang flowy dan anggun.', 'image' => 'images/product-baju-tidur.jpg', 'created_at' => '2026-08-18', 'sales_count' => 380],
            ['slug' => 'short-penguin-set', 'title' => 'Short Penguin Set', 'category' => 'baju-tidur', 'collection' => 'COMFORT COLLECTION', 'price_raw' => 349000, 'original_price' => null, 'discount' => null, 'rating' => '4.8', 'review_count' => 76, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['pink', 'grey'], 'badge' => 'FAVORITE', 'short_desc' => 'Set celana pendek santai berbahan katun organik breathable dengan motif penguin imut.', 'image' => 'images/cart-item-penguin.jpg', 'created_at' => '2026-08-12', 'sales_count' => 275],
            ['slug' => 'lingerie-set-lace', 'title' => 'Lingerie Set Lace', 'category' => 'lingerie', 'collection' => 'LACE COLLECTION', 'price_raw' => 320000, 'original_price' => 'Rp 380.000', 'discount' => '-16%', 'rating' => '4.9', 'review_count' => 142, 'sizes' => ['S', 'M', 'L'], 'colors' => ['pink', 'white', 'grey'], 'badge' => 'BEST SELLER', 'short_desc' => 'Set lingerie dengan detail lace prancis yang manis, feminin, dan sangat nyaman di kulit.', 'image' => 'images/katalog-product-4.jpg', 'created_at' => '2026-08-14', 'sales_count' => 420],
            ['slug' => 'amour-lace-bralette', 'title' => 'Amour Lace Bralette', 'category' => 'lingerie', 'collection' => 'LACE COLLECTION', 'price_raw' => 329000, 'original_price' => null, 'discount' => null, 'rating' => '4.8', 'review_count' => 68, 'sizes' => ['S', 'M', 'L'], 'colors' => ['white', 'pink'], 'badge' => 'TRENDING', 'short_desc' => 'Bralette tanpa kawat dengan renda elastis halus dan tali silang punggung yang mempesona.', 'image' => 'images/product-lingerie-set.jpg', 'created_at' => '2026-08-19', 'sales_count' => 260],
            ['slug' => 'silk-classic-slip-nightgown', 'title' => 'Silk Classic Slip Nightgown', 'category' => 'lingerie', 'collection' => 'ROYAL SILK', 'price_raw' => 389000, 'original_price' => 'Rp 459.000', 'discount' => '-15%', 'rating' => '4.9', 'review_count' => 165, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['gold', 'cream', 'pink'], 'badge' => 'BEST SELLER', 'short_desc' => 'Gaun tidur sutra klasik model slip dengan tali spaghetti yang dapat disesuaikan dan belahan samping.', 'image' => 'images/rel-prod-1.jpg', 'created_at' => '2026-08-08', 'sales_count' => 510],
            ['slug' => 'lace-camisole-set', 'title' => 'Lace Camisole Set', 'category' => 'lingerie', 'collection' => 'MODAL COLLECTION', 'price_raw' => 289000, 'original_price' => 'Rp 340.000', 'discount' => '-15%', 'rating' => '4.8', 'review_count' => 112, 'sizes' => ['S', 'M', 'L'], 'colors' => ['gold', 'cream'], 'badge' => 'POPULAR', 'short_desc' => 'Set kamisol sutra beraksen renda cantik pada garis leher dengan celana pendek senada.', 'image' => 'images/cart-item-camisole.jpg', 'created_at' => '2026-08-05', 'sales_count' => 340],
            ['slug' => 'kimono-silk-premium', 'title' => 'Kimono Silk Premium', 'category' => 'kimono', 'collection' => 'ROYAL SILK', 'price_raw' => 450000, 'original_price' => 'Rp 520.000', 'discount' => '-15%', 'rating' => '5.0', 'review_count' => 156, 'sizes' => ['S', 'M', 'L', 'XL', 'XXL'], 'colors' => ['pink', 'gold', 'white'], 'badge' => 'BEST SELLER', 'short_desc' => 'Kimono ringan dengan bahan silk yang lembut dan tampilan mewah untuk momen santai di rumah.', 'image' => 'images/product-kimono-silk.jpg', 'created_at' => '2026-08-16', 'sales_count' => 490],
            ['slug' => 'lace-trim-kimono-robe', 'title' => 'Lace Trim Kimono Robe', 'category' => 'kimono', 'collection' => 'ROYAL SILK', 'price_raw' => 399000, 'original_price' => 'Rp 460.000', 'discount' => '-13%', 'rating' => '4.8', 'review_count' => 92, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['cream', 'white', 'grey'], 'badge' => 'NEW', 'short_desc' => 'Jubah tidur kimono beraksen renda chantilly pada lengan lebar dan keliman bawah.', 'image' => 'images/katalog-product-3.jpg', 'created_at' => '2026-08-21', 'sales_count' => 230],
            ['slug' => 'royal-satin-long-robe', 'title' => 'Royal Satin Long Robe', 'category' => 'kimono', 'collection' => 'ROYAL SILK', 'price_raw' => 520000, 'original_price' => 'Rp 620.000', 'discount' => '-16%', 'rating' => '4.9', 'review_count' => 310, 'sizes' => ['M', 'L', 'XL', 'XXL'], 'colors' => ['gold', 'grey'], 'badge' => 'PREMIUM', 'short_desc' => 'Jubah tidur panjang berbahan satin tebal bermutu tinggi dengan sentuhan drape anggun paripurna.', 'image' => 'images/katalog-product-5.jpg', 'created_at' => '2026-08-17', 'sales_count' => 310],
            ['slug' => 'seamless-comfort-panty-set', 'title' => 'Seamless Comfort Panty Set', 'category' => 'pakaian-dalam', 'collection' => 'COMFORT COLLECTION', 'price_raw' => 180000, 'original_price' => 'Rp 220.000', 'discount' => '-18%', 'rating' => '4.9', 'review_count' => 210, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['cream', 'pink', 'grey'], 'badge' => 'BEST SELLER', 'short_desc' => 'Set 3 celana dalam laser cut tanpa jahitan pinggir, tidak menjiplak di pakaian dan anti-iritasi.', 'image' => 'images/rel-prod-2.jpg', 'created_at' => '2026-08-07', 'sales_count' => 620],
            ['slug' => 'soft-wireless-cotton-bra', 'title' => 'Soft Wireless Cotton Bra', 'category' => 'pakaian-dalam', 'collection' => 'COTTON COLLECTION', 'price_raw' => 225000, 'original_price' => 'Rp 275.000', 'discount' => '-18%', 'rating' => '4.8', 'review_count' => 88, 'sizes' => ['S', 'M', 'L', 'XL'], 'colors' => ['white', 'cream'], 'badge' => 'POPULAR', 'short_desc' => 'Bra katun alami tanpa kawat dengan busa tipis bernapas untuk penopang yang relaks sepanjang hari.', 'image' => 'images/rel-prod-3.jpg', 'created_at' => '2026-08-11', 'sales_count' => 290],
            ['slug' => 'pure-modal-high-waist-brief', 'title' => 'Pure Modal High-Waist Brief', 'category' => 'pakaian-dalam', 'collection' => 'MODAL COLLECTION', 'price_raw' => 160000, 'original_price' => null, 'discount' => null, 'rating' => '4.7', 'review_count' => 74, 'sizes' => ['M', 'L', 'XL', 'XXL'], 'colors' => ['pink', 'grey', 'cream'], 'badge' => 'COMFORT', 'short_desc' => 'Celana dalam high-waist dari serat modal lembut yang memeluk perut dengan elastisitas alami.', 'image' => 'images/rel-prod-4.jpg', 'created_at' => '2026-08-09', 'sales_count' => 180],
            ['slug' => 'silk-touch-lace-panty', 'title' => 'Silk Touch Lace Panty', 'category' => 'pakaian-dalam', 'collection' => 'ROYAL SILK', 'price_raw' => 195000, 'original_price' => 'Rp 240.000', 'discount' => '-19%', 'rating' => '4.9', 'review_count' => 130, 'sizes' => ['S', 'M', 'L'], 'colors' => ['white', 'pink', 'gold'], 'badge' => 'HOT', 'short_desc' => 'Celana dalam sentuhan sutra berhias renda halus pada bagian depan dan pinggang elastis.', 'image' => 'images/katalog-product-6.jpg', 'created_at' => '2026-08-13', 'sales_count' => 350],
        ];

        foreach ($products as $item) {
            $category = Category::where('slug', $item['category'])->first();

            $product = Product::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $category->id,
                    'title' => $item['title'],
                    'collection' => $item['collection'],
                    'price' => $item['price_raw'],
                    'original_price' => $item['original_price']
                        ? (int) preg_replace('/\D/', '', $item['original_price'])
                        : null,
                    'discount' => $item['discount'],
                    'rating' => $item['rating'],
                    'review_count' => $item['review_count'],
                    'sizes' => $item['sizes'],
                    'colors' => $item['colors'],
                    'badge' => $item['badge'],
                    'short_desc' => $item['short_desc'],
                    'long_desc' => null,
                    'image' => $item['image'],
                    'sales_count' => $item['sales_count'],
                    'weight' => 250,
                    'length' => null,
                    'width' => null,
                    'height' => null,
                    'stock' => 0,
                    'is_active' => true,
                    'created_at' => $item['created_at'],
                ]
            );

            // SKU otomatis (model boot nonaktif saat seeding karena WithoutModelEvents,
            // jadi dibuat eksplisit dengan rumus yang sama: PREFIX-00001)
            if (blank($product->sku)) {
                $product->forceFill([
                    'sku' => sprintf('%s-%05d', Product::skuPrefix($category->slug), $product->getKey()),
                ])->saveQuietly();
            }

            // Varian ukuran x warna (stok 0, diisi lewat halaman Stok)
            foreach ($item['sizes'] as $size) {
                foreach ($item['colors'] as $color) {
                    ProductVariant::firstOrCreate(
                        [
                            'product_id' => $product->id,
                            'size' => $size,
                            'color' => $color,
                        ],
                        [
                            'stock' => 0,
                            'sku' => $product->sku,
                        ]
                    );
                }
            }

            $product->update(['stock' => $product->variants()->sum('stock')]);
        }
    }
}
