@extends('layouts.app')

@section('title', 'Tentang Kami - Sweet Dreams')

@section('content')
<style>
    /* ===== TENTANG HERO ===== */
    .tentang-hero {
        position: relative;
        width: 100%;
        height: 420px;
        overflow: hidden;
    }
    .tentang-hero-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center 40%;
    }
    .tentang-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg,
            rgba(212,77,110,0.82) 0%,
            rgba(184,58,88,0.70) 40%,
            rgba(212,77,110,0.45) 70%,
            rgba(244,141,168,0.30) 100%
        );
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .tentang-hero-overlay h1 {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 0.75rem 0;
        text-shadow: 0 2px 16px rgba(0,0,0,0.12);
        opacity: 0;
        transform: translateY(20px);
        animation: tentangFadeUp 0.8s ease 0.2s forwards;
    }
    .tentang-hero-overlay p {
        font-size: 1.05rem;
        color: rgba(255,255,255,0.92);
        margin: 0;
        max-width: 520px;
        text-align: center;
        line-height: 1.6;
        opacity: 0;
        transform: translateY(20px);
        animation: tentangFadeUp 0.8s ease 0.4s forwards;
    }
    .tentang-breadcrumb {
        position: absolute;
        top: 24px;
        left: 2rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.75);
        z-index: 2;
    }
    .tentang-breadcrumb a {
        color: rgba(255,255,255,0.75);
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .tentang-breadcrumb a:hover {
        color: #fff;
    }
    .tentang-breadcrumb .separator {
        opacity: 0.5;
    }

    @keyframes tentangFadeUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== STORY SECTION ===== */
    .tentang-story {
        max-width: 1280px;
        margin: 0 auto;
        padding: 4rem 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }
    .tentang-story-img {
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(212,77,110,0.10);
        position: relative;
    }
    .tentang-story-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .tentang-story-img::after {
        content: '';
        position: absolute;
        bottom: -8px;
        right: -8px;
        width: 60%;
        height: 60%;
        border: 3px solid #fbd5df;
        border-radius: 20px;
        z-index: -1;
    }
    .tentang-story-text .label {
        display: inline-block;
        font-family: 'Inter', sans-serif;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #d44d6e;
        background: #fef5f7;
        padding: 6px 14px;
        border-radius: 50px;
        margin-bottom: 1.25rem;
    }
    .tentang-story-text h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #3a2a2e;
        line-height: 1.25;
        margin: 0 0 1.25rem 0;
    }
    .tentang-story-text p {
        font-size: 0.92rem;
        color: #5a3a42;
        line-height: 1.8;
        margin: 0 0 1rem 0;
    }
    .tentang-story-text .highlight {
        font-weight: 600;
        color: #d44d6e;
    }

    /* ===== VALUES SECTION ===== */
    .tentang-values {
        background: #fef5f7;
        padding: 4.5rem 0;
    }
    .tentang-values-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
    }
    .tentang-values-header {
        text-align: center;
        margin-bottom: 3rem;
    }
    .tentang-values-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
    }
    .tentang-values-header p {
        font-size: 0.92rem;
        color: #8a6a72;
        margin: 0;
    }
    .tentang-values-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
    .value-card {
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem 2rem;
        border: 1.5px solid #fbd5df;
        text-align: center;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
    }
    .value-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(90deg, #d44d6e, #f48da8);
        border-radius: 0 0 4px 4px;
    }
    .value-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 36px rgba(212,77,110,0.12);
        border-color: #f48da8;
    }
    .value-card .icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #fef5f7, #fdedf1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #d44d6e;
        transition: all 0.3s ease;
    }
    .value-card:hover .icon-wrap {
        background: linear-gradient(135deg, #d44d6e, #f48da8);
        color: #fff;
        transform: scale(1.08);
    }
    .value-card h3 {
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.75rem 0;
    }
    .value-card p {
        font-size: 0.85rem;
        color: #7a5a62;
        line-height: 1.7;
        margin: 0;
    }

    /* ===== STATS SECTION ===== */
    .tentang-stats {
        max-width: 1280px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }
    .tentang-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2rem;
    }
    .stat-item {
        text-align: center;
        padding: 2rem 1rem;
        border-radius: 16px;
        background: #fff;
        border: 1.5px solid #fbd5df;
        transition: all 0.3s ease;
    }
    .stat-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(212,77,110,0.10);
        border-color: #f48da8;
    }
    .stat-item .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #d44d6e;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .stat-item .stat-label {
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 500;
        color: #8a6a72;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    /* ===== VISION MISSION ===== */
    .tentang-vimi {
        background: linear-gradient(135deg, #3a2a2e 0%, #5a3a42 100%);
        padding: 4.5rem 0;
        position: relative;
        overflow: hidden;
    }
    .tentang-vimi::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(212,77,110,0.08);
    }
    .tentang-vimi::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(244,141,168,0.06);
    }
    .tentang-vimi-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        position: relative;
        z-index: 1;
    }
    .vimi-card {
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 20px;
        padding: 2.5rem;
        transition: all 0.4s ease;
    }
    .vimi-card:hover {
        background: rgba(255,255,255,0.10);
        transform: translateY(-4px);
    }
    .vimi-card .vimi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #d44d6e, #f48da8);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        margin-bottom: 1.25rem;
    }
    .vimi-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin: 0 0 1rem 0;
    }
    .vimi-card p {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.75);
        line-height: 1.8;
        margin: 0;
    }

    /* ===== CTA SECTION ===== */
    .tentang-cta {
        max-width: 1280px;
        margin: 0 auto;
        padding: 4rem 2rem 5rem;
        text-align: center;
    }
    .tentang-cta-card {
        background: linear-gradient(135deg, #fef5f7, #fdedf1);
        border-radius: 24px;
        padding: 3.5rem 2rem;
        border: 1.5px solid #fbd5df;
        position: relative;
        overflow: hidden;
    }
    .tentang-cta-card::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(212,77,110,0.06);
    }
    .tentang-cta-card::after {
        content: '';
        position: absolute;
        bottom: -40px;
        left: -40px;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(244,141,168,0.08);
    }
    .tentang-cta-card h2 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.75rem 0;
        position: relative;
        z-index: 1;
    }
    .tentang-cta-card p {
        font-size: 0.95rem;
        color: #7a5a62;
        line-height: 1.7;
        margin: 0 0 2rem 0;
        max-width: 520px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
        z-index: 1;
    }
    .tentang-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #d44d6e;
        color: #fff;
        padding: 14px 36px;
        border-radius: 50px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(212,77,110,0.25);
        position: relative;
        z-index: 1;
    }
    .tentang-cta-btn:hover {
        background: #b83a58;
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(212,77,110,0.35);
    }
    .tentang-cta-btn svg {
        transition: transform 0.3s ease;
    }
    .tentang-cta-btn:hover svg {
        transform: translateX(4px);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .tentang-values-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .tentang-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .tentang-hero {
            height: 320px;
        }
        .tentang-hero-overlay h1 {
            font-size: 2rem;
        }
        .tentang-hero-overlay p {
            font-size: 0.9rem;
            padding: 0 1.5rem;
        }
        .tentang-story {
            grid-template-columns: 1fr;
            gap: 2rem;
            padding: 3rem 1.5rem;
        }
        .tentang-story-img {
            order: -1;
            max-height: 280px;
        }
        .tentang-story-text h2 {
            font-size: 1.6rem;
        }
        .tentang-values-grid {
            grid-template-columns: 1fr;
        }
        .tentang-vimi-inner {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        .tentang-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .tentang-cta-card h2 {
            font-size: 1.5rem;
        }
    }
    @media (max-width: 480px) {
        .tentang-hero {
            height: 260px;
        }
        .tentang-hero-overlay h1 {
            font-size: 1.6rem;
        }
        .tentang-stats-grid {
            grid-template-columns: 1fr 1fr;
        }
        .stat-item .stat-number {
            font-size: 2rem;
        }
    }
</style>

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
