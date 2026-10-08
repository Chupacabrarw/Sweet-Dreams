@extends('layouts.app')

@section('title', 'Hubungi Kami - Sweet Dreams')
@section('description', 'Hubungi Sweet Dreams untuk pertanyaan seputar produk, pesanan, atau kerjasama. Kami siap membantu Anda.')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/kontak.css')
@endpush

{{-- HERO --}}
<section class="kontak-hero">
    <p class="section-eyebrow" style="margin-bottom:0.6rem;">Sweet Dreams</p>
    <h1>Hubungi Kami</h1>
    <p>Kami selalu senang mendengar dari Anda. Ada kendala pesanan, ingin tanya ketersediaan stok produk, atau sekedar ingin menyapa? Sampaikan kepada kami di bawah ini.</p>
</section>

{{-- MAIN CONTENT --}}
<div class="kontak-main-wrapper">
    <div class="kontak-grid">

        {{-- LEFT: FORM --}}
        <div class="kontak-form-card">
            <h2 class="form-card-title">Kirimkan Pesan Anda</h2>

            <div class="kontak-success-toast" id="kontak-success-toast" role="status" aria-live="polite">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Pesan Anda berhasil terkirim! Kami akan merespons dalam 1&times;24 jam kerja.
            </div>

            <form id="kontak-form" novalidate>
                @csrf
                <div class="kontak-form-error" id="kontak-form-error" role="alert" hidden></div>
                <div class="kontak-form-grid">
                    <div class="kontak-form-group">
                        <label for="input-nama-kontak">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" id="input-nama-kontak" name="name" placeholder="Contoh: Ayu Putri" maxlength="255" required>
                    </div>
                    <div class="kontak-form-group">
                        <label for="input-email-kontak">Alamat Email <span class="req">*</span></label>
                        <input type="email" id="input-email-kontak" name="email" placeholder="ayu@emailsaya.com" maxlength="255" required>
                    </div>
                    <div class="kontak-form-group">
                        <label for="input-wa-kontak">Nomor WhatsApp <span class="req">*</span></label>
                        <input type="tel" id="input-wa-kontak" name="phone" placeholder="0812 ****" maxlength="30" required>
                    </div>
                    <div class="kontak-form-group">
                        <label for="input-topik-kontak">Topik Pertanyaan <span class="req">*</span></label>
                        <select id="input-topik-kontak" name="topic" required>
                            <option value="" disabled selected>Pilih topik pertanyaan</option>
                            <option value="produk">Spesifikasi &amp; Bahan Produk</option>
                            <option value="pesanan">Status &amp; Informasi Pesanan</option>
                            <option value="pengiriman">Pengiriman &amp; Resi</option>
                            <option value="retur">Retur &amp; Penukaran Barang</option>
                            <option value="reseller">Daftar Jadi Reseller</option>
                            <option value="kerjasama">Kerjasama &amp; Kolaborasi</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="kontak-form-group full">
                        <label for="input-order-kontak">Nomor Pesanan (Opsional)</label>
                        <input type="text" id="input-order-kontak" name="order_number" maxlength="100" placeholder="Contoh: #SD-26001-AB12 (jika ada pesanan terkait)">
                    </div>
                    <div class="kontak-form-group full">
                        <label for="input-pesan-kontak">Isi Pesan <span class="req">*</span></label>
                        <textarea id="input-pesan-kontak" name="message" minlength="5" maxlength="5000" placeholder="Tuliskan pesan Anda secara detail di sini, tim kami akan memberikan respons terbaiknya..." required></textarea>
                    </div>
                </div>

                <div class="kontak-privacy-row">
                    <input type="checkbox" id="cb-privacy-kontak" name="privacy" value="1" required>
                    <label for="cb-privacy-kontak">
                        Saya menyetujui data di atas akan digunakan oleh tim Sweet Dreams untuk keperluan respons dan komunikasi layanan pelanggan.
                    </label>
                </div>

                <button type="submit" class="btn-kirim" id="btn-kirim-kontak">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Kirim Pesan Sekarang
                </button>
            </form>
        </div>

        {{-- RIGHT: SIDEBAR --}}
        <div class="kontak-sidebar">

            {{-- Kontak Langsung --}}
            <div class="kontak-langsung-card">
                <h3 class="sidebar-card-title">Kontak Langsung</h3>

                <div class="kontak-info-item">
                    <div class="kontak-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div class="kontak-info-text">
                        <p class="info-label">Nomor WhatsApp</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="info-value">+62 812 ****</a>
                        <p class="info-sub">Chat langsung, respons cepat</p>
                    </div>
                </div>

                <div class="kontak-info-item">
                    <div class="kontak-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    </div>
                    <div class="kontak-info-text">
                        <p class="info-label">Email Resmi</p>
                        <a href="mailto:halo@sweetdream.id" class="info-value">halo@sweetdream.id</a>
                        <p class="info-sub">Untuk urusan resmi & kerjasama</p>
                    </div>
                </div>

                <div class="kontak-info-item">
                    <div class="kontak-info-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <div class="kontak-info-text">
                        <p class="info-label">Jam Operasional</p>
                        <p class="info-value" style="text-decoration:none; cursor:default;">Senin–Sabtu</p>
                        <p class="info-sub">10.00–19.00 WIB &nbsp;|&nbsp; Minggu 10.00–16.00</p>
                    </div>
                </div>
            </div>

            {{-- Lokasi Workshop --}}
            <div class="lokasi-workshop-card">
                <h3 class="sidebar-card-title">Lokasi Workshop</h3>
                <p class="lokasi-address">
                    H6FR+H7, Purwokerto Lor,<br>
                    Kabupaten Banyumas, Jawa Tengah
                </p>
                <div class="map-iframe-wrapper">
                    <iframe
                        src="https://maps.google.com/maps?q=H6FR%2BH7+Purwokerto+Lor,+Kabupaten+Banyumas,+Jawa+Tengah&t=&z=17&ie=UTF8&iwloc=&output=embed"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi Workshop Sweet Dreams">
                    </iframe>
                </div>
                <a href="https://maps.google.com/?q=H6FR%2BH7+Purwokerto+Lor,+Kabupaten+Banyumas,+Jawa+Tengah" target="_blank" rel="noopener" class="btn-buka-maps">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Buka di Google Maps
                </a>
            </div>

        </div>
    </div>
</div>

{{-- FAQ SECTION --}}
<section class="faq-section">
    <div class="faq-inner">
        <div class="faq-header">
            <p class="faq-badge">Tanya Jawab</p>
            <h2>Pertanyaan yang Sering Diajukan</h2>
        </div>

        <div class="faq-list" id="faq-list">

            <div class="faq-item" id="faq-pengiriman">
                <button class="faq-question" aria-expanded="false">
                    <span class="faq-question-text">Berapa lama estimasi pengiriman ke rumah saya?</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Untuk area Jawa Tengah, estimasi pengiriman 2–4 hari kerja tergantung kurir yang Anda pilih. Setiap order senilai Rp500.000 atau lebih akan dikirim gratis tanpa biaya tambahan. Untuk area di luar Pulau Jawa, estimasi pengiriman bisa mencapai 5–10 hari kerja.
                    </div>
                </div>
            </div>

            <div class="faq-item" id="faq-penukaran">
                <button class="faq-question" aria-expanded="false">
                    <span class="faq-question-text">Apakah piyama atau pakaian dalam bisa ditukar ukuran?</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Bisa! Kami memberikan kebijakan Tukar Ukuran Gratis dalam waktu 7 hari setelah barang diterima untuk kategori piyama, kimono, dan daster. Untuk lingerie, penukaran tidak berlaku karena alasan dasar & higienitas kami.
                    </div>
                </div>
            </div>

            <div class="faq-item" id="faq-panduan-ukuran">
                <button class="faq-question" aria-expanded="false">
                    <span class="faq-question-text">Bagaimana cara memilih ukuran yang tepat?</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Buka halaman detail produk dan pilih “Lihat tabel ukuran” untuk melihat panduan ukuran produk tersebut. Jika masih ragu, hubungi tim kami melalui halaman kontak.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <span class="faq-question-text">Apakah kemasan pengiriman benar-benar polos?</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Betul sekali. Pesanan dikemas ke dalam kotak polos atau mailer doff tanpa embel-embel tulisan "lingerie" atau indikasi konten, untuk menjaga privasi dan kenyamanan Anda dan keluarga Anda.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <span class="faq-question-text">Metode pembayaran apa saja yang didukung?</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Kami menerima berbagai metode pembayaran: transfer dan QRIS seluruh bank & e-wallet (GoPay, OVO, ShopeePay), Transfer Virtual Account (BCA, BNI, Mandiri, BRI), hingga kartu kredit. Semua transaksi terenkripsi dan aman.
                    </div>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" aria-expanded="false">
                    <span class="faq-question-text">Bagaimana cara &amp; syarat mendaftar jadi reseller resmi?</span>
                    <span class="faq-icon">+</span>
                </button>
                <div class="faq-answer">
                    <div class="faq-answer-inner">
                        Sangat mudah! Anda hanya perlu melakukan pembelian awal minimal 10 pcs (bebas campur model &amp; ukuran) untuk mendapatkan paket harga reseller khusus hingga 25% diskon, serta materi promosi foto berkualitas tinggi. Hubungi kami via WhatsApp untuk memulai!
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===== FAQ Accordion =====
    document.querySelectorAll('.faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var item = this.closest('.faq-item');
            var answer = item.querySelector('.faq-answer');
            var isOpen = item.classList.contains('open');

            // Close all
            document.querySelectorAll('.faq-item').forEach(function (fi) {
                fi.classList.remove('open');
                fi.querySelector('.faq-answer').style.maxHeight = null;
                fi.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
            });

            // Open clicked if was closed
            if (!isOpen) {
                item.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // ===== Contact Form Submit =====
    var form = document.getElementById('kontak-form');
    var btnKirim = document.getElementById('btn-kirim-kontak');
    var toast = document.getElementById('kontak-success-toast');
    var formError = document.getElementById('kontak-form-error');
    var defaultButtonHtml = btnKirim ? btnKirim.innerHTML : '';

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var nama = document.getElementById('input-nama-kontak').value.trim();
            var email = document.getElementById('input-email-kontak').value.trim();
            var wa = document.getElementById('input-wa-kontak').value.trim();
            var topik = document.getElementById('input-topik-kontak').value;
            var pesan = document.getElementById('input-pesan-kontak').value.trim();
            var privacy = document.getElementById('cb-privacy-kontak').checked;

            if (!nama || !email || !wa || !topik || !pesan) {
                alert('Harap lengkapi semua kolom yang wajib diisi (*).');
                return;
            }
            if (!privacy) {
                alert('Harap centang persetujuan privasi sebelum mengirim pesan.');
                return;
            }

            btnKirim.disabled = true;
            btnKirim.textContent = 'Mengirim...';
            formError.hidden = true;
            formError.textContent = '';
            toast.classList.remove('show');

            fetch('{{ route('contact-messages.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: nama,
                    email: email,
                    phone: wa,
                    topic: topik,
                    order_number: document.getElementById('input-order-kontak').value.trim() || null,
                    message: pesan,
                    privacy: privacy ? 1 : null
                })
            })
            .then(async function (response) {
                var data = await response.json();
                if (!response.ok) {
                    var validationErrors = data.errors ? Object.values(data.errors).flat() : [];
                    throw new Error(validationErrors.join(' ') || data.message || 'Pesan gagal dikirim. Silakan coba lagi.');
                }

                form.reset();
                toast.textContent = data.message;
                toast.classList.add('show');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                setTimeout(function () { toast.classList.remove('show'); }, 6000);
            })
            .catch(function (error) {
                formError.textContent = error.message || 'Tidak dapat mengirim pesan. Periksa koneksi lalu coba lagi.';
                formError.hidden = false;
            })
            .finally(function () {
                btnKirim.disabled = false;
                btnKirim.innerHTML = defaultButtonHtml;
            });
        });
    }
});
</script>
@endsection
