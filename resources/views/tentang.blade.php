@extends('layouts.app')

@section('title', 'Tentang Kami - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/tentang.css')
@endpush

{{-- HERO --}}
<section class="tentang-hero" id="tentang-hero">
    <img src="{{ asset('images/tentang-hero.jpg') }}" alt="Sweet Dreams - Tentang Kami" class="tentang-hero-img" loading="eager">
    <div class="tentang-hero-overlay">
        <div class="tentang-breadcrumb">
            <a href="/">Home</a>
            <span class="separator">/</span>
            <span>Tentang Kami</span>
        </div>
        <h1>Tentang Sweet Dreams</h1>
        <p>Menghadirkan kenyamanan dan keanggunan dalam setiap momen istirahatmu sejak 2019.</p>
    </div>
</section>

{{-- STORY --}}
<section class="tentang-story reveal" id="tentang-story">
    <div class="tentang-story-img">
        <img src="{{ asset('images/cat-lingerie.jpg') }}" alt="Sweet Dreams Story" loading="lazy">
    </div>
    <div class="tentang-story-text">
        <span class="label">Cerita Kami</span>
        <h2>Bermula dari Cinta untuk Kenyamanan</h2>
        <p>
            <span class="highlight">Sweet Dreams</span> lahir dari sebuah keyakinan sederhana: setiap wanita berhak merasakan kenyamanan dan kemewahan saat beristirahat. Didirikan pada tahun 2019 di Jakarta, kami memulai perjalanan dengan koleksi kecil sleepwear berbahan sutra pilihan.
        </p>
        <p>
            Kini, Sweet Dreams telah berkembang menjadi brand sleepwear & lingerie premium terpercaya yang melayani ribuan pelanggan di seluruh Indonesia. Setiap produk kami dirancang dengan perhatian penuh pada detail — mulai dari pemilihan bahan terbaik, proses jahitan presisi, hingga desain yang mengikuti tren mode terkini.
        </p>
        <p>
            Kami percaya bahwa pakaian tidur bukan sekadar pakaian, melainkan <span class="highlight">bentuk self-care</span> yang layak dimiliki setiap wanita.
        </p>
    </div>
</section>

{{-- VALUES --}}
<section class="tentang-values" id="tentang-values">
    <div class="tentang-values-inner">
        <div class="tentang-values-header reveal">
            <h2>Nilai-Nilai Kami</h2>
            <p>Tiga pilar yang menjadi fondasi setiap produk Sweet Dreams</p>
        </div>
        <div class="tentang-values-grid">
            <div class="value-card reveal reveal-delay-1" id="value-kualitas">
                <div class="icon-wrap">
                    <i data-lucide="gem" style="width:28px;height:28px;"></i>
                </div>
                <h3>Kualitas Premium</h3>
                <p>Kami hanya menggunakan bahan terpilih — sutra mulberry, modal Austria, dan katun organik — untuk memastikan kelembutan dan ketahanan di setiap helai benang.</p>
            </div>
            <div class="value-card reveal reveal-delay-2" id="value-kenyamanan">
                <div class="icon-wrap">
                    <i data-lucide="heart" style="width:28px;height:28px;"></i>
                </div>
                <h3>Kenyamanan Utama</h3>
                <p>Setiap potongan dirancang ergonomis agar tidak mengganggu tidurmu. Ringan, adem, dan lembut memeluk tubuh sepanjang malam.</p>
            </div>
            <div class="value-card reveal reveal-delay-3" id="value-desain">
                <div class="icon-wrap">
                    <i data-lucide="sparkles" style="width:28px;height:28px;"></i>
                </div>
                <h3>Desain Elegan</h3>
                <p>Perpaduan estetika modern dengan sentuhan feminin — detail renda, warna lembut, dan siluet anggun yang membuat kamu tampil menawan bahkan saat bersantai.</p>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="tentang-stats reveal" id="tentang-stats">
    <div class="tentang-stats-grid">
        <div class="stat-item" id="stat-pelanggan">
            <div class="stat-number">15K+</div>
            <div class="stat-label">Pelanggan Setia</div>
        </div>
        <div class="stat-item" id="stat-produk">
            <div class="stat-number">200+</div>
            <div class="stat-label">Desain Produk</div>
        </div>
        <div class="stat-item" id="stat-kota">
            <div class="stat-number">120+</div>
            <div class="stat-label">Kota Terjangkau</div>
        </div>
        <div class="stat-item" id="stat-rating">
            <div class="stat-number">4.9</div>
            <div class="stat-label">Rating Pelanggan</div>
        </div>
    </div>
</section>

{{-- VISION & MISSION --}}
<section class="tentang-vimi" id="tentang-vimi">
    <div class="tentang-vimi-inner">
        <div class="vimi-card reveal reveal-delay-1" id="vimi-visi">
            <div class="vimi-icon">
                <i data-lucide="eye" style="width:22px;height:22px;"></i>
            </div>
            <h3>Visi Kami</h3>
            <p>Menjadi brand sleepwear & lingerie nomor satu di Indonesia yang dikenal akan kualitas premium, kenyamanan luar biasa, dan desain yang memberdayakan kepercayaan diri setiap wanita.</p>
        </div>
        <div class="vimi-card reveal reveal-delay-2" id="vimi-misi">
            <div class="vimi-icon">
                <i data-lucide="target" style="width:22px;height:22px;"></i>
            </div>
            <h3>Misi Kami</h3>
            <p>Menghadirkan koleksi sleepwear berkualitas tinggi dengan harga terjangkau, memberikan pelayanan belanja yang menyenangkan, dan terus berinovasi mengikuti kebutuhan wanita Indonesia modern.</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="tentang-cta reveal" id="tentang-cta">
    <div class="tentang-cta-card">
        <h2>Siap Merasakan Kenyamanan Sweet Dreams?</h2>
        <p>Jelajahi koleksi terbaru kami dan temukan sleepwear impianmu. Gratis ongkir untuk pembelian pertama!</p>
        <a href="/katalog" class="tentang-cta-btn" id="btn-cta-katalog">
            Jelajahi Koleksi
            <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
        </a>
    </div>
</section>
@endsection
