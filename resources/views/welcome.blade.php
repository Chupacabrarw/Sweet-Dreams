@extends('layouts.app')

@section('title', 'Sweet Dreams - Premium Sleepwear & Lingerie')

@section('content')
    {{-- HERO SECTION --}}
    <section class="hero" id="hero-section">
               <img src="{{ asset($banner->image) }}" alt="Sweet Dreams" class="hero-img" loading="eager">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <span class="hero-eyebrow">Koleksi Terbaru 2024</span>
            <h1>{!! nl2br(e($banner->title)) !!}</h1>
            <p>{{ $banner->subtitle }}</p>
            <a href="{{ $banner->link }}" class="btn-shop-now" id="btn-shop-hero">
                {{ $banner->button_text }}
                <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
            </a>
        </div>
    </section>

    {{-- KATEGORI SECTION --}}
    <section class="section" id="kategori-section">
        <div class="section-header reveal">
            <div>
                <p class="section-eyebrow">Koleksi Kami</p>
                <h2>Kategori</h2>
            </div>
            <a href="/katalog" class="view-all">Lihat Semua</a>
        </div>

        <div class="categories-grid">
            @forelse($homeCategories as $i => $category)
            <a href="/katalog/{{ $category->slug }}"
               class="category-card reveal reveal-delay-{{ ($i % 4) + 1 }}"
               id="cat-{{ $category->slug }}">
                <div class="category-card-img">
                    <img src="{{ asset('images/cat-' . $category->slug . '.jpg') }}"
                         alt="{{ $category->name }}" loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                </div>
                <div class="category-card-body">
                    <h3>{{ $category->name }}</h3>
                    <span>Lihat Produk</span>
                </div>
            </a>
            @empty
            <p style="grid-column:1/-1;text-align:center;color:var(--ink-muted);padding:2rem 0;">Belum ada kategori yang dipilih.</p>
            @endforelse
        </div>
    </section>

    {{-- PRODUK UNGGULAN SECTION --}}
    <section class="section" id="produk-unggulan-section" style="padding-top:0;">
        <div class="section-header reveal">
            <div>
                <p class="section-eyebrow">Pilihan Editor</p>
                <h2>Produk Unggulan</h2>
            </div>
            <a href="/katalog" class="view-all">Lihat Semua</a>
        </div>

        <div class="products-grid">
            @forelse($homeProducts as $i => $product)
            <a href="/produk/{{ $product->slug }}"
               class="product-card reveal reveal-delay-{{ ($i % 3) + 1 }}"
               id="product-{{ $product->id }}">
                <div class="product-card-img">
                    <img src="{{ asset($product->image) }}"
                         alt="{{ $product->title }}" loading="lazy"
                         onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                    <button class="product-card-wishlist" aria-label="Tambah ke Wishlist">
                        <i data-lucide="heart" style="width:15px;height:15px;"></i>
                    </button>
                </div>
                <div class="product-card-body">
                    <h3>{{ $product->title }}</h3>
                    <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
            </a>
            @empty
            <p style="grid-column:1/-1;text-align:center;color:var(--ink-muted);padding:2rem 0;">Belum ada produk unggulan yang dipilih.</p>
            @endforelse
        </div>
    </section>

    {{-- TESTIMONIALS SECTION --}}
    <section class="testimonials-section" id="testimonials-section">
        <div class="testimonials-inner">
            <div class="testimonials-header reveal">
                <div>
                    <p class="section-eyebrow">Ulasan Pelanggan</p>
                    <h2 class="testimonials-title">Apa Kata Mereka?</h2>
                </div>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card reveal reveal-delay-1" id="testimonial-1">
                    <div class="testimonial-stars">
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                    </div>
                    <p>"Baju tidur dari Sweet Dreams benar-benar membuat saya merasa seperti di hotel bintang lima setiap malam. Kualitasnya sangat premium!"</p>
                    <div class="testimonial-author">
                        <div class="avatar">AL</div>
                        <div class="info">
                            <h4>Ayu Lestari</h4>
                            <span>Pelanggan Setia</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card reveal reveal-delay-2" id="testimonial-2">
                    <div class="testimonial-stars">
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                    </div>
                    <p>"Kimono silk-nya sangat nyaman dipakai. Desainnya elegan, cocok untuk dipakai saat santai di rumah maupun saat tidur."</p>
                    <div class="testimonial-author">
                        <div class="avatar">RW</div>
                        <div class="info">
                            <h4>Rina Wijaya</h4>
                            <span>Pelanggan</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card reveal reveal-delay-3" id="testimonial-3">
                    <div class="testimonial-stars">
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                        <i data-lucide="star" style="width:14px;height:14px;fill:currentColor;"></i>
                    </div>
                    <p>"Pengiriman cepat dan kemasan yang cantik. Saya sangat puas dengan pelayanan Sweet Dreams — pasti akan beli lagi!"</p>
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
