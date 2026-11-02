# Deploy gratis: Render (web) + Aiven (MySQL)

Dua-duanya gratis tanpa kartu kredit. Catatan free tier:

- Web Render **tidur setelah 15 menit** tanpa pengunjung; buka pertama butuh ±1 menit.
- **File upload tidak permanen** (bukti bayar QRIS & gambar produk baru hilang saat redeploy/restart).
  Gambar bawaan yang ada di repo (`public/images`) aman.
- Pesanan QRIS yang lewat batas bayar tetap dibatalkan saat pesanan dibuka (tanpa cron).

## 1. Buat database MySQL di Aiven

1. Daftar di <https://console.aiven.io> → **Create service** → **MySQL** → plan **Free**.
2. Tunggu status **Running**, buka tab **Overview → Connection information**, catat:
   `Host`, `Port`, `User` (biasanya `avnadmin`), `Password`, `Database` (biasanya `defaultdb`).
3. Di bagian yang sama, klik **CA certificate → Show / Download** dan salin seluruh isinya
   (mulai `-----BEGIN CERTIFICATE-----` sampai `-----END CERTIFICATE-----`).

## 2. Deploy ke Render

1. Daftar di <https://dashboard.render.com> pakai akun GitHub.
2. **New → Blueprint** → pilih repo `Sweet-Dreams` → Render membaca `render.yaml`.
3. Isi variabel yang diminta:

| Variabel | Isi |
| --- | --- |
| `APP_URL` | `https://sweet-dreams.onrender.com` (ganti sesuai URL yang dikasih Render; bisa diisi ulang setelah deploy pertama) |
| `DB_HOST` / `DB_PORT` / `DB_PASSWORD` | dari Aiven |
| `DB_SSL_CA_PEM` | isi CA certificate Aiven (tempel utuh, multi-baris boleh) |
| `QRIS_PAYLOAD` | teks QRIS toko (sama dengan `.env` lokal, **tanpa** tanda kutip) |
| `RAJAONGKIR_API_KEY`, `RAJAONGKIR_ORIGIN_CITY_ID`, `GEMINI_API_KEY` | sama dengan `.env` lokal |

Kalau `DB_DATABASE`/`DB_USERNAME` di Aiven beda dari `defaultdb`/`avnadmin`, ubah di
**Environment** service setelah blueprint dibuat.

4. **Apply** → Render build `Dockerfile` (±5–10 menit). Saat container start, otomatis:
   `migrate --force`, `config:cache`, `route:cache`, `view:cache`.

## 3. Isi data awal (sekali saja)

Database Aiven masih kosong. Dari laptop, arahkan `.env` sementara ke Aiven lalu seed,
atau import dump dari MySQL lokal:

```bash
mysqldump -u root -p sweetdreams > sweetdreams.sql
mysql --host=HOST --port=PORT -u avnadmin -p --ssl-mode=REQUIRED defaultdb < sweetdreams.sql
```

## Update berikutnya

Cukup `git push origin main` → Render auto-deploy.
