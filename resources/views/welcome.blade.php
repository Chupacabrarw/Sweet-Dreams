@extends('layouts.app')

@section('title', 'Sweet Dreams - Premium Sleepwear & Lingerie')

@section('content')
    {{-- HERO SECTION --}}
    <section class="hero" id="hero-section">
        <img src="{{ asset('images/hero-banner.jpg') }}" alt="Sweet Dreams - Koleksi Sleepwear Premium" class="hero-img" loading="eager">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>Sweet Dreams<br>Start Here</h1>
            <p>Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu.</p>
            <a href="/katalog" class="btn-shop-now" id="btn-shop-hero">
                Shop Now
                <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
            </a>
        </div>
    </section>

    {{-- KATEGORI SECTION --}}
    <section class="section" id="kategori-section">
        <div class="section-header reveal">
            <div>
                <h2>Kategori</h2>
                <p>Temukan produk yang tepat untuk Anda</p>
            </div>
            <a href="/katalog" class="view-all">Lihat Semua</a>
        </div>

        <div class="categories-grid">
            <a href="/katalog/baju-tidur" class="category-card reveal reveal-delay-1" id="cat-baju-tidur">
                <div class="category-card-img">
                    <img src="{{ asset('images/cat-baju-tidur.jpg') }}" alt="Baju Tidur" loading="lazy">
                </div>
                <div class="category-card-body">
                    <h3>Baju Tidur</h3>
                    <span>Lihat Produk</span>
                </div>
            </a>
            <a href="/katalog/lingerie" class="category-card reveal reveal-delay-2" id="cat-lingerie">
                <div class="category-card-img">
                    <img src="{{ asset('images/cat-lingerie.jpg') }}" alt="Lingerie" loading="lazy">
                </div>
                <div class="category-card-body">
                    <h3>Lingerie</h3>
                    <span>Lihat Produk</span>
                </div>
            </a>
            <a href="/katalog/kimono" class="category-card reveal reveal-delay-3" id="cat-kimono">
                <div class="category-card-img">
                    <img src="{{ asset('images/cat-kimono.jpg') }}" alt="Kimono" loading="lazy">
                </div>
                <div class="category-card-body">
                    <h3>Kimono</h3>
                    <span>Lihat Produk</span>
                </div>
            </a>
            <a href="/katalog/pakaian-dalam" class="category-card reveal reveal-delay-4" id="cat-pakaian-dalam">
                <div class="category-card-img">
                    <img src="{{ asset('images/cat-pakaian-dalam.jpg') }}" alt="Pakaian Dalam" loading="lazy">
                </div>
                <div class="category-card-body">
                    <h3>Pakaian Dalam</h3>
                    <span>Lihat Produk</span>
                </div>
            </a>
        </div>
    </section>

    {{-- PRODUK UNGGULAN SECTION --}}
    <section class="section" id="produk-unggulan-section">
        <div class="section-header reveal">
            <div>
                <h2>Produk Unggulan</h2>
                <p>Pilihan terbaik untuk kenyamanan Anda</p>
            </div>
            <a href="/katalog" class="view-all">Lihat Semua</a>
        </div>

        <div class="products-grid">
            <a href="/produk/kimono-silk-premium" class="product-card reveal reveal-delay-1" id="product-1">
                <div class="product-card-img">
                    <img src="{{ asset('images/product-kimono-silk.jpg') }}" alt="Kimono Silk Premium" loading="lazy">
                    <button class="product-card-wishlist" aria-label="Tambah ke Wishlist">
                        <i data-lucide="heart" style="width:16px;height:16px;"></i>
                    </button>
                </div>
                <div class="product-card-body">
                    <h3>Kimono Silk Premium</h3>
                    <span class="price">Rp 410.000</span>
                </div>
            </a>
            <a href="/produk/lingerie-set-lace" class="product-card reveal reveal-delay-2" id="product-2">
                <div class="product-card-img">
                    <img src="{{ asset('images/product-lingerie-set.jpg') }}" alt="Lingerie Set Lace" loading="lazy">
                    <button class="product-card-wishlist" aria-label="Tambah ke Wishlist">
                        <i data-lucide="heart" style="width:16px;height:16px;"></i>
                    </button>
                </div>
                <div class="product-card-body">
                    <h3>Lingerie Set Lace</h3>
                    <span class="price">Rp 320.000</span>
                </div>
            </a>
            <a href="/produk/baju-tidur-modal" class="product-card reveal reveal-delay-3" id="product-3">
                <div class="product-card-img">
                    <img src="{{ asset('images/product-baju-tidur.jpg') }}" alt="Baju Tidur Modal" loading="lazy">
                    <button class="product-card-wishlist" aria-label="Tambah ke Wishlist">
                        <i data-lucide="heart" style="width:16px;height:16px;"></i>
                    </button>
                </div>
                <div class="product-card-body">
                    <h3>Baju Tidur Modal</h3>
                    <span class="price">Rp 280.000</span>
                </div>
            </a>
        </div>
    </section>

    {{-- TESTIMONIALS SECTION --}}
    <section class="testimonials-section" id="testimonials-section">
        <div class="testimonials-inner">
            <h2 class="testimonials-title reveal">Apa Kata Mereka?</h2>

            <div class="testimonials-grid">
                <div class="testimonial-card reveal reveal-delay-1" id="testimonial-1">
                    <div class="quote-icon">
                        <i data-lucide="quote" style="width:28px;height:28px;"></i>
                    </div>
                    <p>"Baju tidur dari Sweet Dreams benar-benar membuat saya merasa seperti di hotel bintang lima setiap malam. Kualitasnya sangat premium!"</p>
                    <div class="testimonial-author">
                        <div class="avatar">AL</div>
                        <div class="info">
                            <h4>Ayu Lestari</h4>
                            <span>Pelanggan</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card reveal reveal-delay-2" id="testimonial-2">
                    <div class="quote-icon">
                        <i data-lucide="quote" style="width:28px;height:28px;"></i>
                    </div>
                    <p>"Kimono silk-nya sangat komfort dan nyaman dipakai. Desainnya juga sangat elegan, cocok untuk dipakai saat santai di rumah."</p>
                    <div class="testimonial-author">
                        <div class="avatar">RW</div>
                        <div class="info">
                            <h4>Rina Wijaya</h4>
                            <span>Pelanggan</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card reveal reveal-delay-3" id="testimonial-3">
                    <div class="quote-icon">
                        <i data-lucide="quote" style="width:28px;height:28px;"></i>
                    </div>
                    <p>"Pengiriman cepat dan kemasan yang cantik. Saya sangat puas dengan pelayanan Sweet Dreams!"</p>
                    <div class="testimonial-author">
                        <div class="avatar">DS</div>
                        <div class="info">
                            <h4>Dina Sari</h4>
                            <span>Pelanggan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
