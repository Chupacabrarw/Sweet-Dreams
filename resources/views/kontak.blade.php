@extends('layouts.app')

@section('title', 'Hubungi Kami - Sweet Dreams')
@section('description', 'Hubungi Sweet Dreams untuk pertanyaan seputar produk, pesanan, atau kerjasama. Kami siap membantu Anda.')

@section('content')
<style>
    /* ===== HERO ===== */
    .kontak-hero {
        text-align: center;
        padding: 4.5rem 1.5rem 3rem;
        background: linear-gradient(180deg, #fdf2f5 0%, #fff 100%);
    }
    .kontak-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--blush);
        margin-bottom: 1rem;
    }
    .kontak-hero-badge::before,
    .kontak-hero-badge::after {
        content: '';
        width: 32px;
        height: 1.5px;
        background: var(--blush);
        border-radius: 2px;
    }
    .kontak-hero h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1rem 0;
        line-height: 1.15;
    }
    .kontak-hero p {
        font-size: 1rem;
        color: var(--ink-muted);
        max-width: 540px;
        margin: 0 auto;
        line-height: 1.7;
    }

    /* ===== MAIN GRID ===== */
    .kontak-main-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem 5rem;
    }
    .kontak-grid {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* ===== FORM CARD ===== */
    .kontak-form-card {
        background: #ffffff;
        border: 1.5px solid #f4dbe2;
        border-radius: 24px;
        padding: 2.25rem 2.25rem 2rem;
        box-shadow: 0 6px 28px rgba(201,122,140, 0.06);
    }
    .form-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.55rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1.75rem 0;
    }
    .kontak-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .kontak-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }
    .kontak-form-group.full { grid-column: 1 / -1; }
    .kontak-form-group label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--ink-muted);
    }
    .kontak-form-group label .req { color: var(--blush); }
    .kontak-form-group input,
    .kontak-form-group select,
    .kontak-form-group textarea {
        padding: 0.7rem 1rem;
        border: 1.5px solid #e8d0d6;
        border-radius: 12px;
        font-size: 0.9rem;
        font-family: 'DM Sans', sans-serif;
        color: var(--ink);
        background: #fff;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .kontak-form-group input::placeholder,
    .kontak-form-group textarea::placeholder { color: var(--ink-faint); }
    .kontak-form-group input:focus,
    .kontak-form-group select:focus,
    .kontak-form-group textarea:focus {
        border-color: var(--blush);
        box-shadow: 0 0 0 3px rgba(201,122,140, 0.08);
    }
    .kontak-form-group select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%23d44d6e' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 1rem center; padding-right: 2.5rem; cursor: pointer; }
    .kontak-form-group textarea { resize: vertical; min-height: 110px; }

    .kontak-privacy-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin: 1.25rem 0 1.5rem;
        font-size: 0.82rem;
        color: var(--ink-muted);
        line-height: 1.6;
    }
    .kontak-privacy-row input[type="checkbox"] {
        width: 16px; height: 16px;
        accent-color: var(--blush);
        flex-shrink: 0;
        margin-top: 2px;
        cursor: pointer;
    }

    .btn-kirim {
        width: 100%;
        height: 52px;
        background: linear-gradient(135deg, #e87b94 0%, var(--blush) 100%);
        border: none;
        border-radius: 8px;
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        letter-spacing: 0.03em;
        box-shadow: 0 6px 22px rgba(201,122,140, 0.35);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-kirim:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(201,122,140, 0.45);
    }
    .btn-kirim:active { transform: translateY(0); }

    /* ===== SUCCESS TOAST ===== */
    .kontak-success-toast {
        display: none;
        background: linear-gradient(135deg, #e3f9ee 0%, #d1f5e7 100%);
        border: 1.5px solid #a8e6c8;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        font-size: 0.88rem;
        color: #1a7a50;
        font-weight: 600;
        align-items: center;
        gap: 0.6rem;
    }
    .kontak-success-toast.show { display: flex; }

    /* ===== RIGHT SIDEBAR ===== */
    .kontak-sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: sticky;
        top: 88px;
    }

    /* Kontak Langsung Card */
    .kontak-langsung-card {
        background: #fff;
        border: 1.5px solid #f4dbe2;
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        box-shadow: 0 6px 24px rgba(201,122,140, 0.05);
    }
    .sidebar-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1.25rem 0;
    }
    .kontak-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #fdf0f3;
    }
    .kontak-info-item:last-child { border-bottom: none; }
    .kontak-info-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fce7ee;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--blush);
    }
    .kontak-info-text .info-label {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--ink-faint);
        margin: 0 0 0.2rem 0;
    }
    .kontak-info-text .info-value {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
        text-decoration: none;
    }
    .kontak-info-text .info-value:hover { color: var(--blush); }
    .kontak-info-text .info-sub {
        font-size: 0.78rem;
        color: var(--ink-muted);
        margin: 0.1rem 0 0;
    }

    /* Lokasi Workshop Card */
    .lokasi-workshop-card {
        background: #fff;
        border: 1.5px solid #f4dbe2;
        border-radius: var(--radius-lg);
        padding: 1.75rem;
        box-shadow: 0 6px 24px rgba(201,122,140, 0.05);
    }
    .lokasi-address {
        font-size: 0.85rem;
        color: #6a4a52;
        line-height: 1.65;
        margin: 0 0 1rem 0;
    }
    .map-iframe-wrapper {
        border-radius: var(--radius);
        overflow: hidden;
        border: 1.5px solid #f4dbe2;
        width: 100%;
        position: relative;
        box-shadow: 0 4px 16px rgba(201,122,140, 0.08);
        transition: box-shadow 0.3s ease;
    }
    .map-iframe-wrapper:hover {
        box-shadow: 0 8px 24px rgba(201,122,140, 0.16);
    }
    .map-iframe-wrapper iframe {
        width: 100%;
        height: 220px;
        border: none;
        display: block;
    }
    .btn-buka-maps {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        margin-top: 0.85rem;
        background: #fce7ee;
        color: var(--blush);
        font-weight: 700;
        font-size: 0.82rem;
        padding: 0.5rem 1.1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .btn-buka-maps:hover { background: var(--blush); color: #fff; }

    /* ===== FAQ SECTION ===== */
    .faq-section {
        background: #fdf2f5;
        padding: 5rem 2rem 5.5rem;
    }
    .faq-inner {
        max-width: 820px;
        margin: 0 auto;
    }
    .faq-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .faq-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--blush);
        margin-bottom: 0.85rem;
    }
    .faq-badge::before,
    .faq-badge::after {
        content: '';
        width: 28px;
        height: 1.5px;
        background: var(--blush);
        border-radius: 2px;
    }
    .faq-header h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.3rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }

    /* Accordion */
    .faq-item {
        background: #fff;
        border: 1.5px solid #f4dbe2;
        border-radius: var(--radius);
        margin-bottom: 0.85rem;
        overflow: hidden;
        transition: box-shadow 0.25s;
    }
    .faq-item.open {
        box-shadow: 0 8px 24px rgba(201,122,140, 0.09);
        border-color: var(--blush);
    }
    .faq-question {
        width: 100%;
        background: none;
        border: none;
        padding: 1.25rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        gap: 1rem;
        text-align: left;
    }
    .faq-question-text {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.4;
    }
    .faq-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #fce7ee;
        color: var(--blush);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .faq-item.open .faq-icon {
        background: var(--blush);
        color: #fff;
        transform: rotate(45deg);
    }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.3s ease;
    }
    .faq-answer-inner {
        padding: 0 1.5rem 1.25rem;
        font-size: 0.9rem;
        color: #6a4a52;
        line-height: 1.75;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .kontak-grid { grid-template-columns: 1fr; }
        .kontak-sidebar { position: static; }
    }
    @media (max-width: 640px) {
        .kontak-hero h1 { font-size: 2rem; }
        .kontak-form-grid { grid-template-columns: 1fr; }
        .kontak-form-group.full { grid-column: auto; }
        .kontak-main-wrapper { padding: 0 1rem 4rem; }
        .faq-section { padding: 3.5rem 1rem 4rem; }
        .faq-header h2 { font-size: 1.7rem; }
    }
</style>

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

            <div class="kontak-success-toast" id="kontak-success-toast">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Pesan Anda berhasil terkirim! Kami akan merespons dalam 1&times;24 jam kerja.
            </div>

            <form id="kontak-form" novalidate>
                <div class="kontak-form-grid">
                    <div class="kontak-form-group">
                        <label for="input-nama-kontak">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" id="input-nama-kontak" placeholder="Contoh: Ayu Putri" required>
                    </div>
                    <div class="kontak-form-group">
                        <label for="input-email-kontak">Alamat Email <span class="req">*</span></label>
                        <input type="email" id="input-email-kontak" placeholder="ayu@emailsaya.com" required>
                    </div>
                    <div class="kontak-form-group">
                        <label for="input-wa-kontak">Nomor WhatsApp <span class="req">*</span></label>
                        <input type="tel" id="input-wa-kontak" placeholder="0812 ****" required>
                    </div>
                    <div class="kontak-form-group">
                        <label for="input-topik-kontak">Topik Pertanyaan <span class="req">*</span></label>
                        <select id="input-topik-kontak" required>
                            <option value="" disabled selected>Tanya Spesifikasi &amp; Bahan Produk</option>
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
                        <input type="text" id="input-order-kontak" placeholder="Contoh: #SD-26001-AB12 (jika ada pesanan terkait)">
                    </div>
                    <div class="kontak-form-group full">
                        <label for="input-pesan-kontak">Isi Pesan <span class="req">*</span></label>
                        <textarea id="input-pesan-kontak" placeholder="Tuliskan pesan Anda secara detail di sini, tim kami akan memberikan respons terbaiknya..." required></textarea>
                    </div>
                </div>

                <div class="kontak-privacy-row">
                    <input type="checkbox" id="cb-privacy-kontak" required>
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

            <div class="faq-item">
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

            <div class="faq-item">
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

            // Simulate sending (can be replaced with real fetch/AJAX)
            btnKirim.disabled = true;
            btnKirim.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg> Mengirim...';

            setTimeout(function () {
                form.reset();
                btnKirim.disabled = false;
                btnKirim.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg> Kirim Pesan Sekarang';

                toast.classList.add('show');
                window.scrollTo({ top: 0, behavior: 'smooth' });
                setTimeout(function () { toast.classList.remove('show'); }, 6000);
            }, 1200);
        });
    }
});
</script>
@endsection
