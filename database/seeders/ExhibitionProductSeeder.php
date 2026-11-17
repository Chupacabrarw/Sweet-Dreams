<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Isi katalog untuk pameran: 12 produk per kategori (Baju Tidur, Kimono, Pakaian Dalam),
 * isi stok varian yang masih 0, dan sembunyikan kategori Lingerie (produknya dinonaktifkan).
 * Aman dijalankan berulang (updateOrCreate per slug). Foto: Pexels (lisensi gratis, boleh komersial),
 * disimpan di public/images/products/{slug}.jpg.
 *
 *   php artisan db:seed --class=ExhibitionProductSeeder --force
 */
class ExhibitionProductSeeder extends Seeder
{
    /** Warna baru yang dipakai produk pameran (nama = slug, dipetakan ke swatch di katalog). */
    protected array $colors = [
        'terracotta' => '#C8643B', 'black' => '#1E1A1C', 'blue' => '#8DB3D9', 'champagne' => '#D9C3A5',
        'green' => '#1F8A52', 'lavender' => '#A58BD0', 'red' => '#C0392B', 'navy' => '#1F2D4D',
        'mauve' => '#9C7D97', 'mocca' => '#8A6A55',
    ];

    protected function products(): array
    {
        $tidur = ['S', 'M', 'L', 'XL'];
        $robe = ['S', 'M', 'L', 'XL'];
        $dalam = ['M', 'L', 'XL'];
        $bra = ['34', '36', '38'];

        return [
            'baju-tidur' => [
                ['Piyama Satin Terracotta', 399000, 459000, $tidur, ['terracotta'], 'Setelan piyama satin warna terracotta dengan kemeja lengan panjang dan celana panjang longgar. Jatuh lembut dan adem dipakai tidur.'],
                ['Piyama Satin Hitam Piping', 429000, null, $tidur, ['black'], 'Piyama satin hitam klasik dengan aksen piping putih di kerah, saku, dan ujung lengan. Elegan untuk tidur maupun bersantai.'],
                ['Piyama Satin Biru Langit', 389000, 449000, $tidur, ['blue'], 'Setelan piyama satin biru muda lengan panjang, potongan santai dengan kancing depan. Lembut di kulit dan tidak gerah.'],
                ['Piyama Satin Champagne', 459000, 520000, $tidur, ['champagne'], 'Piyama satin warna champagne yang berkilau lembut. Kemeja lengan panjang dan celana panjang, nyaman dan terlihat mewah.'],
                ['Piyama Katun Hijau Emerald', 299000, null, $tidur, ['green'], 'Setelan piyama katun hijau emerald dengan potongan oversized. Menyerap keringat dan bebas bergerak.'],
                ['Piyama Satin Motif Print', 419000, null, $tidur, ['cream'], 'Piyama satin bermotif ilustrasi dengan warna dasar krem. Kemeja lengan panjang dan celana panjang yang tetap sopan.'],
                ['Piyama Katun Lavender', 289000, 329000, $tidur, ['lavender'], 'Setelan piyama katun lembut warna lavender, lengan panjang dan celana panjang. Cocok untuk tidur nyenyak setiap malam.'],
            ],
            'kimono' => [
                ['Kimono Katun Pink Blush', 279000, null, $robe, ['pink'], 'Kimono katun warna pink blush dengan tali pinggang. Ringan dan praktis dipakai setelah mandi atau bersantai di rumah.'],
                ['Kimono Satin Floral Merah', 459000, 520000, $robe, ['red'], 'Kimono satin bermotif bunga merah dengan lengan lebar khas kimono. Panjang semata kaki dan tampak anggun.'],
                ['Kimono Satin Bunga Putih', 399000, null, $robe, ['white'], 'Kimono satin putih dengan motif bunga dan dedaunan. Dilengkapi tali pinggang, nyaman sebagai luaran baju tidur.'],
                ['Kimono Motif Daun Tropis', 349000, 399000, $robe, ['cream'], 'Kimono krem bermotif daun tropis berwarna lembut. Bahan ringan dan jatuh, pas dipakai santai di rumah.'],
                ['Kimono Satin Biru Sakura', 429000, null, $robe, ['blue'], 'Kimono satin biru bermotif bunga sakura dengan lengan lebar. Halus, berkilau lembut, dan elegan.'],
                ['Long Robe Satin Hitam', 489000, 559000, $robe, ['black'], 'Long robe satin hitam panjang hingga mata kaki. Cocok dipadukan dengan piyama satin untuk tampilan rapi di rumah.'],
                ['Kimono Satin Putih Klasik', 369000, null, $robe, ['white'], 'Kimono satin putih polos dengan tali pinggang. Desain klasik dan bersih, nyaman dipakai kapan saja.'],
                ['Long Robe Satin Cream', 459000, null, $robe, ['cream'], 'Long robe satin warna krem dengan tali pinggang panjang. Tampil anggun sebagai luaran baju tidur.'],
                ['Kimono Waffle Katun Abu', 319000, 359000, $robe, ['grey'], 'Kimono bahan katun waffle warna abu yang tebal namun ringan. Menyerap air, cocok dipakai setelah mandi.'],
            ],
            'pakaian-dalam' => [
                ['Celana Dalam Katun Floral (Isi 3)', 129000, null, $dalam, ['pink'], 'Isi 3 celana dalam katun motif bunga dan warna lembut. Elastis di pinggang dan nyaman dipakai seharian.'],
                ['Celana Dalam Katun Navy & Merah (Isi 3)', 119000, null, $dalam, ['navy'], 'Isi 3 celana dalam katun warna navy, kotak-kotak, dan merah. Bahan adem dan tidak mudah melar.'],
                ['Celana Dalam Katun Putih Daisy (Isi 3)', 119000, null, $dalam, ['white'], 'Isi 3 celana dalam katun warna biru muda, putih, dan motif bunga daisy. Lembut dan menyerap keringat.'],
                ['Celana Dalam Katun Motif Ceria (Isi 3)', 125000, null, $dalam, ['red'], 'Isi 3 celana dalam katun motif ceria dengan aksen pita kecil. Nyaman untuk aktivitas sehari-hari.'],
                ['Bra Busa Tanpa Kawat Mauve', 199000, 239000, $bra, ['mauve'], 'Bra dengan busa tipis tanpa kawat warna mauve. Menopang dengan nyaman tanpa menekan.'],
                ['Bra Katun Tanpa Kawat Basic', 159000, null, $bra, ['white', 'cream', 'mocca'], 'Bra katun basic tanpa kawat dengan tali lebar. Tersedia warna putih, krem, dan mocca untuk pemakaian harian.'],
                ['Sport Bra Seamless Mauve', 189000, null, ['S', 'M', 'L'], ['mauve'], 'Sport bra seamless warna mauve dengan pad yang bisa dilepas. Elastis, cocok untuk olahraga ringan atau di rumah.'],
                ['Bra Satin Rib Pink', 179000, null, ['S', 'M', 'L'], ['pink'], 'Bra model sport bahan satin bertekstur rib warna pink. Tanpa kawat dan nyaman dipakai santai.'],
            ],
        ];
    }

    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->colors as $slug => $hex) {
                ProductColor::firstOrCreate(['slug' => $slug], ['name' => $slug, 'hex' => $hex]);
            }

            $weights = ['baju-tidur' => 300, 'kimono' => 350, 'pakaian-dalam' => 120];
            foreach ($this->products() as $categorySlug => $items) {
                $category = Category::where('slug', $categorySlug)->firstOrFail();

                foreach ($items as [$title, $price, $original, $sizes, $colors, $desc]) {
                    $slug = \Illuminate\Support\Str::slug($title);
                    $product = Product::updateOrCreate(['slug' => $slug], [
                        'category_id'    => $category->id,
                        'title'          => $title,
                        'price'          => $price,
                        'original_price' => $original,
                        'discount'       => $original ? '-' . round((1 - $price / $original) * 100) . '%' : null,
                        'rating'         => 0,
                        'review_count'   => 0,
                        'sales_count'    => 0,
                        'sizes'          => $sizes,
                        'colors'         => $colors,
                        'badge'          => 'BARU',
                        'short_desc'     => $desc,
                        'image'          => "images/products/{$slug}.jpg",
                        'weight'         => $weights[$categorySlug],
                        'is_active'      => true,
                    ]);
                    $product->syncVariants();
                }
            }

            // Lingerie disembunyikan selama pameran (aktifkan lagi per produk dari admin)
            Product::whereHas('category', fn ($q) => $q->where('slug', 'lingerie'))->update(['is_active' => false]);

            // Stok pameran: varian aktif yang masih 0 diisi 6–18 (angka tetap per varian, bukan acak tiap jalan)
            $active = Product::where('is_active', true)->get();
            foreach ($active as $product) {
                if (!$product->variants()->exists()) {
                    $product->syncVariants();
                }
                foreach ($product->variants()->where('stock', 0)->get() as $variant) {
                    $variant->update(['stock' => 6 + crc32($product->slug . $variant->size . $variant->color) % 13]);
                }
                $product->update(['stock' => (int) $product->variants()->sum('stock')]);
            }
        });

        $summary = Category::withCount(['products as aktif' => fn ($q) => $q->where('is_active', true)])->get()
            ->map(fn ($c) => "{$c->name}: {$c->aktif} produk aktif")->implode(' · ');
        $this->command?->info($summary . ' · total stok ' . ProductVariant::sum('stock'));
    }
}
