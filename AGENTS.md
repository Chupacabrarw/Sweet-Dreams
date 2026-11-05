# Sweet Dreams — Gambaran Proyek (untuk AI / developer baru)

Toko online **sleepwear wanita** (baju tidur, kimono, pakaian dalam, lingerie) berbahasa Indonesia.
Pemilik: Faisal Billijuan Oliver. Repo: `github.com/Chupacabrarw/Sweet-Dreams` (branch `main`).
Situs live: **https://sweet-dreams-omega.vercel.app**

Bahasa UI, komentar kode, dan pesan commit: **Bahasa Indonesia**.

---

## 1. Tech stack

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 12, PHP ^8.4 |
| Frontend | Blade + Vite (CSS per halaman di `resources/css/pages/*`), ikon Lucide, JS vanilla di dalam Blade |
| Font | Inter, Manrope, Nunito Sans, Cormorant Garamond, Parisienne (Google Fonts di `layouts/app.blade.php`) |
| Database | Lokal: MySQL (Laragon). Produksi: **PostgreSQL Supabase** |
| File upload | Lokal: folder `public/`. Produksi: **Supabase Storage** (protokol S3) |
| Hosting | **Vercel** (runtime komunitas `vercel-php@0.8.0`, region `fra1`) |
| AI chatbot | Google Gemini (REST `generateContent`) |
| Ongkir | API RajaOngkir versi Komerce |
| Pembayaran | QRIS statis → dinamis + verifikasi manual admin (Midtrans sudah dihapus) |
| Admin | Panel custom di `/admin` (controller `App\Http\Controllers\Admin\*`). Filament 3.3 juga terpasang di `/filament` (resource lama; bukan admin utama) |

---

## 2. Struktur penting

```
app/
  Http/Controllers/            # toko: Cart, Checkout, Payment, Product, Profile, Shipping, Chatbot, Order, Wishlist...
  Http/Controllers/Admin/      # admin: Dashboard, Product, Stock, Order, Customer, Content, Voucher, Report, ContactMessage
  Http/Middleware/IsAdmin.php  # alias 'admin' (cek users.role === 'admin')
  Models/                      # Product, ProductVariant, ProductColor, Category, Order, OrderItem, CartItem, Voucher,
                               # SiteContent, Address, Wishlist, ProductReview, ContactMessage, User
  Services/QrisService.php     # QRIS statis→dinamis (nominal + kode unik + CRC), kedaluwarsa pesanan
  Services/InventoryService.php# reservasi/kembalikan stok varian dengan lockForUpdate
  Support/PublicUpload.php     # simpan gambar upload ke disk publik (lokal / Supabase)
routes/web.php                 # semua route (toko + /admin + /api/*)
routes/console.php             # command orders:expire-qris (scheduler tiap 5 menit)
resources/views/               # welcome, katalog, produk-detail, keranjang, checkout, pesanan-detail, profil, tentang, kontak
resources/views/admin/         # layout + halaman admin
api/index.php                  # entry point Vercel (storage → /tmp, perbaikan SCRIPT_NAME)
vercel.json / .vercelignore    # konfigurasi deploy Vercel
tests/Feature/QrisPaymentTest.php
```

---

## 3. Fitur & alur utama

- **Katalog & produk**: produk punya `sizes` dan `colors` (JSON). Stok disimpan **per varian** di `product_variants` (size × color). `products.stock` = total varian. `Product::syncVariants()` membuat/menghapus varian sesuai sizes×colors.
- **Halaman Stok admin** (`/admin/stok`): kartu per produk → `/admin/stok/produk/{id}` grid ukuran × warna, simpan sekaligus. Produk tanpa varian otomatis dibuatkan variannya saat halaman stok dibuka.
- **Checkout**: alamat → ongkir real-time (`ShippingController`, kurir aktif diatur admin) → voucher → pesanan dibuat, stok direservasi (`InventoryService`).
- **Pembayaran QRIS**: `QrisService::makeDynamic()` mengubah QRIS statis toko (`QRIS_PAYLOAD` / diatur di admin) jadi QR bernominal (total + kode unik). Pembeli upload bukti → admin verifikasi di `/admin/pesanan`. Pesanan lewat batas (`QRIS_EXPIRY_MINUTES`) dibatalkan otomatis dan stok dikembalikan.
- **Chatbot "Dreamy"** (`ChatbotController`, `POST /api/chatbot`): system prompt diisi data toko asli (produk relevan + stok + kategori + voucher aktif). Model utama `GEMINI_MODEL`, cadangan `GEMINI_FALLBACK_MODELS` (dipakai kalau 404/429/5xx/timeout). `GEMINI_THINKING_LEVEL=minimal` (default) → balasan ±2–4 dtk. API key dikirim lewat header, bukan URL.
- **CMS Konten** (`/admin/konten`): hub kartu per bagian → `/admin/konten/{banner|sections|tentang|kontak|ekspedisi|qris}`. Data disimpan di tabel `site_contents` (key/body/image). Default teks halaman Tentang/Kontak ada di `ContentController::pageDefaults()`.
- **Landing page**: judul hero dari banner; baris/kata terakhir otomatis jadi aksen script (Parisienne).
- **Upload gambar admin**: dikecilkan di browser sebelum dikirim (maks sisi 3200 px, JPEG 85%) — skrip di `admin/layout.blade.php`. Banner wajib ≥ 1920×800 px (dicek di browser dan server).
- **Pengaturan Profil Admin** (`/admin/profile`, `Admin\ProfileController`): ganti email dengan OTP 6 angka yang dikirim ke **email baru** (`App\Mail\SendOtpMail`, view `emails/otp`) dan ganti password. Data OTP disimpan di **Cache** (key `admin-email-otp:{userId}`, hash kode, 10 menit, maks 5 salah, jeda kirim ulang 60 dtk) — tanpa migrasi.

---

## 4. Deploy produksi (Vercel + Supabase)

- **Vercel project**: `sweet-dreams` (akun Vercel `chupacabrarw`, team `billy-cc00`), domain `sweet-dreams-omega.vercel.app`.
  Deploy manual dari laptop: `vercel deploy --prod --yes` (repo GitHub **belum** disambungkan ke Vercel, jadi push tidak auto-deploy).
- **Supabase**: organisasi **"Sweet Dreams"**, project **`sweet-dreams`** (ref `ghdkqyspaowdinkbgqbo`, region Frankfurt `eu-central-1`), akun Supabase milik pemilik (login via GitHub).
  Koneksi lewat **session pooler** `aws-1-eu-central-1.pooler.supabase.com:5432`, user `postgres.ghdkqyspaowdinkbgqbo`, `DB_SSLMODE=require`.
  Data API Supabase **dimatikan**; semua tabel public sudah RLS on + revoke anon/authenticated.
  Bucket Storage: `images` (publik, gambar upload) dan `private` (bukti bayar QRIS).
- Env produksi diset di Vercel (nilai rahasia **tidak** ada di repo): `APP_KEY`, `DB_*`, `SUPABASE_URL`, `SUPABASE_S3_KEY/SECRET/REGION`, `GEMINI_API_KEY`, `GEMINI_MODEL`, `RAJAONGKIR_*`, `QRIS_PAYLOAD`, `QRIS_EXPIRY_MINUTES`.
  `vercel.json` sudah mengatur: cache Laravel ke `/tmp`, `LOG_CHANNEL=stderr`, `SESSION_DRIVER=database`, `CACHE_STORE=database`, `UPLOAD_DISK=supabase`, `PAYMENT_PROOF_DISK=supabase_private`.
- Migrasi skema ke Supabase **tidak** jalan otomatis saat deploy. Jalankan `php artisan migrate` dengan koneksi pgsql ke pooler (butuh `-d extension=pdo_pgsql` di PHP Laragon).

### Batasan Vercel yang wajib diingat
- Filesystem **read-only** (kecuali `/tmp`) → jangan simpan file ke `public/` atau `storage/` di kode produksi; pakai `PublicUpload` / disk dari config.
- Body request maks **4,5 MB** → upload besar harus dikecilkan dulu.
- Log runtime paket gratis hanya ±1 jam (`vercel logs`).
- Supabase free di-**pause** kalau 7 hari tidak aktif → Restore dari dashboard.

---

## 5. Development lokal

- Jalankan via **Laragon** (PHP 8.5 di PATH, MySQL root/root, DB `sweetdreams`). `.env` lokal pakai MySQL.
- `public/hot` mungkin tertinggal dari `npm run dev` → aset mengarah ke Vite dev server yang mati. Hapus/rename file itu, atau jalankan `npm run dev`; untuk build: `npm run build`.
- Server cepat tanpa Laragon: `php -S 127.0.0.1:8124 vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php` dari folder `public/` (`php artisan serve` sempat error APP_KEY di mesin ini).
- **Tes**: PHP lokal tidak punya driver SQLite, jadi jalankan dengan MySQL:
  `DB_CONNECTION=mysql DB_DATABASE=sweetdreams_test php artisan test` (14 tes, semuanya harus lolos).
- Email lokal: `MAIL_MAILER=log` → isi email (mis. kode OTP) tertulis di `storage/logs/laravel.log`.
- Akun admin seeder: `admin@sweetdreams.com` (lihat `database/seeders/AdminSeeder.php`).

---

## 6. Aturan kode yang dipakai di proyek ini

- **Harus kompatibel MySQL dan PostgreSQL**: jangan pakai fungsi khusus MySQL (`FIELD()`, `DATE_FORMAT`, dll.). Pengurutan khusus dilakukan di PHP.
- **Path gambar** bisa relatif (`images/products/x.jpg`) **atau URL penuh** Supabase. Di Blade selalu pakai `asset($path)` (URL penuh dikembalikan apa adanya). Di JS jangan menempel `'/' + path` tanpa cek `^https?://`.
- Upload gambar publik: `App\Support\PublicUpload::store()`. Bukti bayar: disk `config('filesystems.proof_disk')`.
- Stok selalu lewat varian; ubah stok dalam `DB::transaction` + `lockForUpdate`, lalu sinkronkan `products.stock`.
- Setelah simpan di admin, redirect kembali ke halaman bagian yang sama (`admin.content.section`, `admin.stock.product`).
- Pesan commit Bahasa Indonesia, ringkas, satu topik per commit.

---

## 7. Status & catatan terbuka (per 5 Nov 2026)

- **Email produksi belum diset**: di Vercel `MAIL_MAILER=log`, jadi OTP ganti email admin belum benar-benar terkirim. Perlu isi env `MAIL_*` (SMTP Gmail App Password / Resend / Brevo) di Vercel sebelum fitur ganti email dipakai di situs live.
- Folder `promo/` (video promo MP4) tidak untuk di-commit.
- Sisa infrastruktur lama yang tidak dipakai: Render (`render.yaml`, `Dockerfile`, `docker/`, `DEPLOY-RENDER.md`) + MySQL Aiven, dan project Supabase kosong di organisasi "NodeSync" (ref `zrcxkjgeokjaxioldhmq`). Aman dimatikan/dihapus pemilik.
- `README.md` masih README bawaan Laravel.
- Ide lanjutan: sambungkan GitHub ke Vercel (auto-deploy), streaming jawaban chatbot, isi SKU produk lama, ulasan/testimoni asli.
