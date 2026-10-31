<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    protected function defaults(): array
    {
        return [
            'banner_homepage' => [
                'title' => "Sweet Dreams\nStart Here",
                'subtitle' => 'Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu.',
                'button_text' => 'Shop Now',
                'link' => '/katalog',
                'image' => 'images/hero-banner.jpg',
            ],
            'info_toko' => [
                'body' => 'Sweet Dreams adalah brand sleepwear wanita yang menghadirkan kenyamanan premium sejak 2019.',
            ],
            'kebijakan_retur' => [
                'body' => 'Produk dapat diretur maksimal 7 hari setelah diterima, dengan tag dan kemasan utuh.',
            ],
            'panduan_ukuran' => [
                'body' => 'Ukur lingkar dada, pinggang, dan pinggul. Cocokkan hasil dengan tabel ukuran kami.',
            ],
        ];
    }

    protected function getOrCreate(string $key): SiteContent
    {
        $defaults = $this->defaults()[$key] ?? [];
        return SiteContent::firstOrCreate(['key' => $key], $defaults);
    }

    public function index()
    {
        $categories = \App\Models\Category::orderBy('name')->get();
        $paidSales = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.status', '!=', 'cancelled')
            ->whereNotNull('order_items.product_id')
            ->selectRaw('order_items.product_id as product_id, SUM(order_items.quantity) as total_sold')
            ->groupBy('order_items.product_id')
            ->pluck('total_sold', 'product_id');

        $products = Product::orderBy('title')->get()
            ->each(function (Product $product) use ($paidSales) {
                $product->paid_sales_count = (int) $paidSales->get($product->id, 0);
            })
            ->sortByDesc('paid_sales_count')
            ->values();

        $featuredCategories = json_decode(
            SiteContent::where('key', 'home_categories')->value('body') ?? '[]', true
        ) ?: [];

        $featuredProducts = json_decode(
            SiteContent::where('key', 'home_products')->value('body') ?? '[]', true
        ) ?: [];

        return view('admin.content', [
            'banner'             => $this->getOrCreate('banner_homepage'),
            'categories'         => $categories,
            'products'           => $products,
            'featuredCategories' => $featuredCategories,
            'featuredProducts'   => $featuredProducts,
            'couriers'           => \App\Http\Controllers\ShippingController::COURIERS,
            'enabledCouriers'    => \App\Http\Controllers\ShippingController::enabledCouriers(),
            'payMethods'         => \App\Http\Controllers\CheckoutController::PAYMENT_METHODS,
            'enabledPayMethods'  => \App\Http\Controllers\CheckoutController::enabledPaymentMethods(),
            'tentang'            => self::pageData('tentang_'),
            'kontak'             => self::pageData('kontak_'),
            'faqs'               => self::faqs(),
        ]);
    }

    public function updateFeatured(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'products'   => 'nullable|array',
        ]);

        $this->getOrCreate('home_categories')->update([
            'body' => json_encode($request->input('categories', [])),
        ]);

        $this->getOrCreate('home_products')->update([
            'body' => json_encode($request->input('products', [])),
        ]);

        return redirect()->route('admin.content')
            ->with('success', 'Pengaturan landing page berhasil disimpan.');
    }

    public function updateBanner(Request $request)
    {
        $data = $request->validate([
    'title'       => 'required|string|max:255',
    'subtitle'    => 'nullable|string|max:255',
    'button_text' => 'nullable|string|max:100',
    'link'        => 'nullable|string|max:255',
    'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=1920,min_height=800',
], [
    'image.dimensions' => 'Gambar banner minimal 1920×800 px (disarankan 2400×1000 px, landscape).',
]);

        $banner = $this->getOrCreate('banner_homepage');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'banner-' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $data['image'] = 'images/' . $filename;
        }

        $banner->update($data);

        return redirect()->route('admin.content')->with('success', 'Banner homepage berhasil diperbarui.');
    }

    public function updateCouriers(Request $request)
    {
        $data = $request->validate([
            'couriers'   => 'required|array|min:1',
            'couriers.*' => 'in:' . implode(',', array_keys(\App\Http\Controllers\ShippingController::COURIERS)),
        ], [
            'couriers.min' => 'Pilih minimal 1 ekspedisi agar checkout tidak kosong.',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'shipping_couriers'],
            ['body' => json_encode(array_values($data['couriers']))]
        );

        // Tarif di-cache 24 jam per kota -> bersihkan agar pilihan baru langsung berlaku
        \Illuminate\Support\Facades\Cache::flush();

        return redirect()->route('admin.content')
            ->with('success', 'Ekspedisi yang tampil di checkout berhasil disimpan.');
    }

    public function updateText(Request $request, string $key)
    {
        $request->validate(['body' => 'required|string']);

        $content = $this->getOrCreate($key);
        $content->update(['body' => $request->body]);

        return redirect()->route('admin.content')->with('success', 'Konten berhasil diperbarui.');
    }

    public function updatePayMethods(Request $request)
    {
        $data = $request->validate([
            'pay_methods'   => 'required|array|min:1',
            'pay_methods.*' => 'in:' . implode(',', array_keys(\App\Http\Controllers\CheckoutController::PAYMENT_METHODS)),
        ], [
            'pay_methods.min' => 'Pilih minimal 1 metode bayar agar checkout tidak kosong.',
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'payment_methods'],
            ['body' => json_encode(array_values($data['pay_methods']))]
        );

        return redirect()->route('admin.content')
            ->with('success', 'Metode pembayaran di checkout berhasil disimpan.');
    }

    /* ===== Halaman Tentang & Kontak (CMS per halaman) ===== */

    public static function pageDefaults(): array
    {
        return [
            // Tentang — hero
            'tentang_hero_title' => 'Tentang Sweet Dreams',
            'tentang_hero_subtitle' => 'Menghadirkan kenyamanan dan keanggunan dalam setiap momen istirahatmu sejak 2019.',
            'tentang_hero_image' => 'images/tentang-hero.jpg',
            // Tentang — cerita
            'tentang_story_label' => 'Cerita Kami',
            'tentang_story_title' => 'Bermula dari Cinta untuk Kenyamanan',
            'tentang_story_p1' => 'Sweet Dreams lahir dari sebuah keyakinan sederhana: setiap wanita berhak merasakan kenyamanan dan kemewahan saat beristirahat. Didirikan pada tahun 2019 di Jakarta, kami memulai perjalanan dengan koleksi kecil sleepwear berbahan sutra pilihan.',
            'tentang_story_p2' => 'Kini, Sweet Dreams telah berkembang menjadi brand sleepwear & lingerie premium terpercaya yang melayani ribuan pelanggan di seluruh Indonesia. Setiap produk kami dirancang dengan perhatian penuh pada detail — mulai dari pemilihan bahan terbaik, proses jahitan presisi, hingga desain yang mengikuti tren mode terkini.',
            'tentang_story_p3' => 'Kami percaya bahwa pakaian tidur bukan sekadar pakaian, melainkan bentuk self-care yang layak dimiliki setiap wanita.',
            'tentang_story_image' => 'images/cat-lingerie.jpg',
            // Tentang — nilai
            'tentang_values_title' => 'Nilai-Nilai Kami',
            'tentang_values_subtitle' => 'Tiga pilar yang menjadi fondasi setiap produk Sweet Dreams',
            'tentang_value1_title' => 'Kualitas Premium',
            'tentang_value1_body' => 'Kami hanya menggunakan bahan terpilih — sutra mulberry, modal Austria, dan katun organik — untuk memastikan kelembutan dan ketahanan di setiap helai benang.',
            'tentang_value2_title' => 'Kenyamanan Utama',
            'tentang_value2_body' => 'Setiap potongan dirancang ergonomis agar tidak mengganggu tidurmu. Ringan, adem, dan lembut memeluk tubuh sepanjang malam.',
            'tentang_value3_title' => 'Desain Elegan',
            'tentang_value3_body' => 'Perpaduan estetika modern dengan sentuhan feminin — detail renda, warna lembut, dan siluet anggun yang membuat kamu tampil menawan bahkan saat bersantai.',
            // Tentang — statistik
            'tentang_stat1_num' => '15K+', 'tentang_stat1_label' => 'Pelanggan Setia',
            'tentang_stat2_num' => '200+', 'tentang_stat2_label' => 'Desain Produk',
            'tentang_stat3_num' => '120+', 'tentang_stat3_label' => 'Kota Terjangkau',
            'tentang_stat4_num' => '4.9', 'tentang_stat4_label' => 'Rating Pelanggan',
            // Tentang — visi misi
            'tentang_visi_title' => 'Visi Kami',
            'tentang_visi_body' => 'Menjadi brand sleepwear & lingerie nomor satu di Indonesia yang dikenal akan kualitas premium, kenyamanan luar biasa, dan desain yang memberdayakan kepercayaan diri setiap wanita.',
            'tentang_misi_title' => 'Misi Kami',
            'tentang_misi_body' => 'Menghadirkan koleksi sleepwear berkualitas tinggi dengan harga terjangkau, memberikan pelayanan belanja yang menyenangkan, dan terus berinovasi mengikuti kebutuhan wanita Indonesia modern.',
            // Tentang — CTA
            'tentang_cta_title' => 'Siap Merasakan Kenyamanan Sweet Dreams?',
            'tentang_cta_body' => 'Jelajahi koleksi terbaru kami dan temukan sleepwear impianmu. Gratis ongkir untuk pembelian pertama!',
            'tentang_cta_button' => 'Jelajahi Koleksi',
            // Kontak — hero
            'kontak_hero_title' => 'Hubungi Kami',
            'kontak_hero_body' => 'Kami selalu senang mendengar dari Anda. Ada kendala pesanan, ingin tanya ketersediaan stok produk, atau sekedar ingin menyapa? Sampaikan kepada kami di bawah ini.',
            // Kontak — langsung
            'kontak_wa_number' => '6281234567890',
            'kontak_wa_display' => '+62 812 ****',
            'kontak_wa_sub' => 'Chat langsung, respons cepat',
            'kontak_email' => 'halo@sweetdream.id',
            'kontak_email_sub' => 'Untuk urusan resmi & kerjasama',
            'kontak_hours_title' => 'Senin–Sabtu',
            'kontak_hours_sub' => '10.00–19.00 WIB  |  Minggu 10.00–16.00',
            // Kontak — workshop
            'kontak_address' => "H6FR+H7, Purwokerto Lor,\nKabupaten Banyumas, Jawa Tengah",
            'kontak_maps_query' => 'H6FR+H7 Purwokerto Lor, Kabupaten Banyumas, Jawa Tengah',
        ];
    }

    public static function defaultFaqs(): array
    {
        return [
            ['q' => 'Berapa lama estimasi pengiriman ke rumah saya?', 'a' => 'Untuk area Jawa Tengah, estimasi pengiriman 2–4 hari kerja tergantung kurir yang Anda pilih. Setiap order senilai Rp500.000 atau lebih akan dikirim gratis tanpa biaya tambahan. Untuk area di luar Pulau Jawa, estimasi pengiriman bisa mencapai 5–10 hari kerja.'],
            ['q' => 'Apakah piyama atau pakaian dalam bisa ditukar ukuran?', 'a' => 'Bisa! Kami memberikan kebijakan Tukar Ukuran Gratis dalam waktu 7 hari setelah barang diterima untuk kategori piyama, kimono, dan daster. Untuk lingerie, penukaran tidak berlaku karena alasan dasar & higienitas kami.'],
            ['q' => 'Bagaimana cara memilih ukuran yang tepat?', 'a' => 'Buka halaman detail produk dan pilih “Lihat tabel ukuran” untuk melihat panduan ukuran produk tersebut. Jika masih ragu, hubungi tim kami melalui halaman kontak.'],
            ['q' => 'Apakah kemasan pengiriman benar-benar polos?', 'a' => 'Betul sekali. Pesanan dikemas ke dalam kotak polos atau mailer doff tanpa embel-embel tulisan "lingerie" atau indikasi konten, untuk menjaga privasi dan kenyamanan Anda dan keluarga Anda.'],
            ['q' => 'Metode pembayaran apa saja yang didukung?', 'a' => 'Kami menerima berbagai metode pembayaran: transfer dan QRIS seluruh bank & e-wallet (GoPay, OVO, ShopeePay), Transfer Virtual Account (BCA, BNI, Mandiri, BRI), hingga kartu kredit. Semua transaksi terenkripsi dan aman.'],
            ['q' => 'Bagaimana cara & syarat mendaftar jadi reseller resmi?', 'a' => 'Sangat mudah! Anda hanya perlu melakukan pembelian awal minimal 10 pcs (bebas campur model & ukuran) untuk mendapatkan paket harga reseller khusus hingga 25% diskon, serta materi promosi foto berkualitas tinggi. Hubungi kami via WhatsApp untuk memulai!'],
        ];
    }

    /** Ambil konten satu halaman (prefix tentang_/kontak_): DB menang bila terisi, else default. */
    public static function pageData(string $prefix): array
    {
        $defaults = array_filter(
            self::pageDefaults(),
            fn ($v, $k) => str_starts_with($k, $prefix),
            ARRAY_FILTER_USE_BOTH
        );
        $rows = SiteContent::where('key', 'like', $prefix . '%')->get()->keyBy('key');
        $out = [];
        foreach ($defaults as $key => $default) {
            $row = $rows->get($key);
            if (str_ends_with($key, '_image')) {
                $out[$key] = ($row && filled($row->image)) ? $row->image : $default;
            } else {
                $out[$key] = ($row && filled($row->body)) ? $row->body : $default;
            }
        }
        return $out;
    }

    public static function faqs(): array
    {
        $raw = SiteContent::where('key', 'kontak_faq')->value('body');
        $decoded = $raw ? json_decode($raw, true) : null;
        if (is_array($decoded) && $decoded !== []) {
            return array_values(array_filter($decoded, fn ($f) => filled($f['q'] ?? null) && filled($f['a'] ?? null)));
        }
        return self::defaultFaqs();
    }

    public function updateAbout(Request $request)
    {
        $data = $request->validate([
            'tentang_hero_title' => 'required|string|max:255',
            'tentang_hero_subtitle' => 'nullable|string|max:500',
            'tentang_hero_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tentang_story_label' => 'nullable|string|max:100',
            'tentang_story_title' => 'nullable|string|max:255',
            'tentang_story_p1' => 'nullable|string|max:2000',
            'tentang_story_p2' => 'nullable|string|max:2000',
            'tentang_story_p3' => 'nullable|string|max:2000',
            'tentang_story_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tentang_values_title' => 'nullable|string|max:255',
            'tentang_values_subtitle' => 'nullable|string|max:500',
            'tentang_value1_title' => 'nullable|string|max:255',
            'tentang_value1_body' => 'nullable|string|max:1000',
            'tentang_value2_title' => 'nullable|string|max:255',
            'tentang_value2_body' => 'nullable|string|max:1000',
            'tentang_value3_title' => 'nullable|string|max:255',
            'tentang_value3_body' => 'nullable|string|max:1000',
            'tentang_stat1_num' => 'nullable|string|max:50',
            'tentang_stat1_label' => 'nullable|string|max:100',
            'tentang_stat2_num' => 'nullable|string|max:50',
            'tentang_stat2_label' => 'nullable|string|max:100',
            'tentang_stat3_num' => 'nullable|string|max:50',
            'tentang_stat3_label' => 'nullable|string|max:100',
            'tentang_stat4_num' => 'nullable|string|max:50',
            'tentang_stat4_label' => 'nullable|string|max:100',
            'tentang_visi_title' => 'nullable|string|max:255',
            'tentang_visi_body' => 'nullable|string|max:2000',
            'tentang_misi_title' => 'nullable|string|max:255',
            'tentang_misi_body' => 'nullable|string|max:2000',
            'tentang_cta_title' => 'nullable|string|max:255',
            'tentang_cta_body' => 'nullable|string|max:1000',
            'tentang_cta_button' => 'nullable|string|max:100',
        ]);

        foreach (['tentang_hero_image' => 'hero', 'tentang_story_image' => 'story'] as $field => $prefix) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = 'tentang-' . $prefix . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $filename);
                SiteContent::updateOrCreate(['key' => $field], ['image' => 'images/' . $filename]);
            }
            unset($data[$field]);
        }

        foreach ($data as $key => $value) {
            SiteContent::updateOrCreate(['key' => $key], ['body' => $value]);
        }

        return redirect()->route('admin.content')->with('success', 'Halaman Tentang berhasil diperbarui.');
    }

    public function updateContact(Request $request)
    {
        $data = $request->validate([
            'kontak_hero_title' => 'required|string|max:255',
            'kontak_hero_body' => 'nullable|string|max:2000',
            'kontak_wa_number' => 'nullable|string|max:30',
            'kontak_wa_display' => 'nullable|string|max:100',
            'kontak_wa_sub' => 'nullable|string|max:255',
            'kontak_email' => 'nullable|string|max:255',
            'kontak_email_sub' => 'nullable|string|max:255',
            'kontak_hours_title' => 'nullable|string|max:255',
            'kontak_hours_sub' => 'nullable|string|max:500',
            'kontak_address' => 'nullable|string|max:1000',
            'kontak_maps_query' => 'nullable|string|max:500',
            'faq_q' => 'nullable|array|max:30',
            'faq_q.*' => 'nullable|string|max:500',
            'faq_a' => 'nullable|array|max:30',
            'faq_a.*' => 'nullable|string|max:2000',
        ]);

        $faqs = [];
        foreach ((array) ($data['faq_q'] ?? []) as $i => $q) {
            $a = $data['faq_a'][$i] ?? '';
            if (filled(trim((string) $q)) && filled(trim((string) $a))) {
                $faqs[] = ['q' => trim($q), 'a' => trim($a)];
            }
        }
        unset($data['faq_q'], $data['faq_a']);

        foreach ($data as $key => $value) {
            SiteContent::updateOrCreate(['key' => $key], ['body' => $value]);
        }
        SiteContent::updateOrCreate(['key' => 'kontak_faq'], ['body' => json_encode($faqs, JSON_UNESCAPED_UNICODE)]);

        return redirect()->route('admin.content')->with('success', 'Halaman Kontak berhasil diperbarui.');
    }
}