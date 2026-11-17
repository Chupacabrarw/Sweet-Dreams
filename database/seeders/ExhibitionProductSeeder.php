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
                ['Gaun Tidur Satin Terracotta', 349000, 399000, ['S', 'M', 'L'], ['terracotta'], 'Gaun tidur satin warna terracotta dengan aksen renda di bagian bawah. Jatuh lembut, ringan, dan adem dipakai tidur.'],
                ['Piyama Cream Piping Hitam', 429000, null, $tidur, ['cream'], 'Setelan piyama warna krem dengan aksen piping hitam di kerah, kancing, dan ujung lengan. Kemeja lengan panjang dan celana panjang.'],
                ['Piyama Satin Biru Langit', 389000, 449000, $tidur, ['blue'], 'Setelan piyama biru muda lengan panjang dengan piping putih dan kancing depan. Lembut di kulit dan tidak gerah.'],
                ['Piyama Satin Blush Renda', 459000, 520000, $tidur, ['pink'], 'Piyama satin warna blush pink dengan detail renda di kerah, saku, dan ujung lengan. Nyaman dan terlihat mewah.'],
                ['Celana Tidur Katun Garis Biru', 189000, null, $tidur, ['blue'], 'Celana tidur katun motif garis biru-putih dengan karet pinggang. Adem, ringan, dan bebas bergerak.'],
                ['Piyama Satin Motif Bunga', 419000, null, $tidur, ['cream'], 'Atasan piyama satin bermotif bunga dengan warna dasar krem yang lembut. Halus dan nyaman dipakai bersantai.'],
                ['Kemeja Tidur Linen Putih', 289000, 329000, $tidur, ['white'], 'Kemeja tidur bahan linen putih yang ringan dan menyerap keringat. Cocok dipadukan dengan celana tidur favoritmu.'],
            ],
            'kimono' => [
                ['Set Kimono Handuk Pink & Biru', 299000, 349000, $robe, ['pink', 'blue'], 'Kimono handuk tebal dan lembut, tersedia warna pink dan biru. Menyerap air dengan baik, cocok dipakai setelah mandi.'],
                ['Kimono Satin Floral Merah', 459000, 520000, $robe, ['red'], 'Kimono satin merah dengan motif bunga keemasan dan lengan lebar khas kimono. Panjang dan tampak anggun.'],
                ['Kimono Katun Motif Botani', 349000, null, $robe, ['cream'], 'Kimono katun krem dengan motif botani lembut dan tali pinggang. Ringan, nyaman sebagai luaran baju tidur.'],
                ['Kimono Rayon Aneka Motif', 329000, 379000, $robe, ['cream'], 'Kimono rayon dengan pilihan motif bunga yang ceria. Bahan ringan dan jatuh, pas dipakai santai di rumah.'],
                ['Kimono Silk Bordir Terracotta', 489000, null, $robe, ['terracotta'], 'Kimono silk warna terracotta dengan bordir bunga putih dan aksen keemasan. Halus, berkilau lembut, dan elegan.'],
                ['Kimono Flanel Kotak Hitam Putih', 359000, 409000, $robe, ['black'], 'Kimono flanel motif kotak hitam-putih yang hangat dengan tali pinggang. Nyaman dipakai di malam yang dingin.'],
                ['Kimono Waffle Putih Klasik', 369000, null, $robe, ['white'], 'Kimono bahan waffle putih dengan tali pinggang. Desain klasik dan bersih, menyerap air, nyaman dipakai kapan saja.'],
                ['Long Robe Katun Cream', 429000, null, $robe, ['cream'], 'Long robe katun warna krem dengan tali pinggang panjang. Lembut dan tampil rapi sebagai luaran baju tidur.'],
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

    /**
     * Produk yang namanya diganti agar sesuai foto produk tanpa model (slug lama => judul baru).
     * Baris lama di-rename (bukan dibuat produk baru), jadi id, pesanan, dan wishlist tetap aman.
     */
    protected array $renames = [
        'piyama-satin-terracotta'    => 'Gaun Tidur Satin Terracotta',
        'piyama-satin-hitam-piping'  => 'Piyama Cream Piping Hitam',
        'piyama-satin-champagne'     => 'Piyama Satin Blush Renda',
        'piyama-katun-hijau-emerald' => 'Celana Tidur Katun Garis Biru',
        'piyama-satin-motif-print'   => 'Piyama Satin Motif Bunga',
        'piyama-katun-lavender'      => 'Kemeja Tidur Linen Putih',
        'kimono-katun-pink-blush'    => 'Set Kimono Handuk Pink & Biru',
        'kimono-satin-bunga-putih'   => 'Kimono Katun Motif Botani',
        'kimono-motif-daun-tropis'   => 'Kimono Rayon Aneka Motif',
        'kimono-satin-biru-sakura'   => 'Kimono Silk Bordir Terracotta',
        'long-robe-satin-hitam'      => 'Kimono Flanel Kotak Hitam Putih',
        'kimono-satin-putih-klasik'  => 'Kimono Waffle Putih Klasik',
        'long-robe-satin-cream'      => 'Long Robe Katun Cream',
    ];

    /** Produk lama yang fotonya ada model: ganti foto (dan nama/warna bila perlu). */
    protected array $legacy = [
        'rose-satin-pajama-set' => ['image' => 'images/products/rose-satin-pajama-set.jpg'],
        'kimono-silk-premium'   => ['image' => 'images/products/kimono-silk-premium.jpg'],
        'sailor-rabbit-set'     => ['image' => 'images/products/piyama-fleece-navy-motif.jpg', 'title' => 'Piyama Fleece Navy Motif',
                                    'slug' => 'piyama-fleece-navy-motif', 'colors' => ['navy'],
                                    'short_desc' => 'Atasan piyama fleece navy bermotif lucu yang hangat dan lembut. Nyaman dipakai di malam yang sejuk.'],
    ];

    /** Hapus varian di luar kombinasi ukuran × warna produk (warna lama setelah rename), lalu lengkapi. */
    protected function resetVariants(Product $product): void
    {
        $sizes = collect($product->sizes ?? [])->map(fn ($s) => strtolower($s));
        $colors = collect($product->colors ?? [])->map(fn ($c) => strtolower($c));
        $product->variants()->get()
            ->reject(fn ($v) => $sizes->contains(strtolower($v->size)) && $colors->contains(strtolower($v->color)))
            ->each->delete();
        $product->syncVariants();
    }

    public function run(): void
    {
        DB::transaction(function () {
            foreach ($this->colors as $slug => $hex) {
                ProductColor::firstOrCreate(['slug' => $slug], ['name' => $slug, 'hex' => $hex]);
            }

            foreach ($this->renames as $oldSlug => $newTitle) {
                $newSlug = \Illuminate\Support\Str::slug($newTitle);
                if (!Product::where('slug', $newSlug)->exists()) {
                    Product::where('slug', $oldSlug)->update(['slug' => $newSlug, 'title' => $newTitle]);
                }
            }

            foreach ($this->legacy as $slug => $changes) {
                $product = Product::where('slug', $slug)->first()
                    ?? (isset($changes['slug']) ? Product::where('slug', $changes['slug'])->first() : null);
                if ($product) {
                    $product->update($changes);
                    $this->resetVariants($product);
                }
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
                    $this->resetVariants($product);
                }
            }

            // Lingerie & Pakaian Dalam disembunyikan selama pameran (aktifkan lagi per produk dari admin)
            Product::whereHas('category', fn ($q) => $q->whereIn('slug', ['lingerie', 'pakaian-dalam']))->update(['is_active' => false]);

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
