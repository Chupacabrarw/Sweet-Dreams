@extends('layouts.app')

@section('title', 'Sweet Dreams - Premium Sleepwear & Lingerie')

@section('content')
    {{-- HERO SECTION --}}
    <section class="hero" id="hero-section">
               <img src="{{ asset($banner->image) }}" alt="Sweet Dreams" class="hero-img" loading="eager">
        <div class="hero-overlay"></div>
        <div class="hero-content">
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
                    <button class="product-card-wishlist {{ in_array($product->id, $wishlistIds ?? []) ? 'active' : '' }}" 
                            data-product-id="{{ $product->id }}" 
                            aria-label="Tambah ke Wishlist" 
                            onclick="toggleHomeWishlist(event, {{ $product->id }}, this);">
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
                @forelse($featuredReviews as $review)
                    <a class="testimonial-card reveal reveal-delay-{{ $loop->iteration }}" href="{{ route('produk.detail', $review->product->slug) }}">
                        <div class="testimonial-stars" aria-label="{{ $review->rating }} dari 5 bintang">
                            @for($star = 1; $star <= 5; $star++)
                                <i data-lucide="star" style="width:14px;height:14px;fill:{{ $star <= $review->rating ? 'currentColor' : 'none' }};"></i>
                            @endfor
                        </div>
                        <p>“{{ $review->body }}”</p>
                        <div class="testimonial-author">
                            <div class="avatar">{{ mb_strtoupper(mb_substr($review->user->name, 0, 2)) }}</div>
                            <div class="info">
                                <h4>{{ $review->user->name }}</h4>
                                <span>Verified Buyer · {{ $review->product->title }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="testimonials-empty">Ulasan pelanggan akan tampil di sini setelah ada pembeli yang menyelesaikan pesanan dan menulis ulasan.</p>
                @endforelse
            </div>
        </div>
    </section>

@push('page-styles')
    @vite('resources/css/pages/welcome.css')
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    const isLoggedIn = @json(auth()->check());
    window.toggleHomeWishlist = function(e, productId, btn) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!isLoggedIn) {
            window.location.href = '/login';
            return;
        }
        fetch('/api/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: Number(productId) })
        })
        .then(res => {
            if (res.status === 401) {
                window.location.href = '/login';
                return;
            }
            return res.json();
        })
        .then(data => {
            if (!data) return;
            btn.classList.toggle('active', data.is_wishlisted);
            const navBadge = document.querySelector('#btn-wishlist .badge');
            if (navBadge && data.wishlist_count !== undefined) {
                navBadge.textContent = data.wishlist_count;
                navBadge.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
            }
            try {
                localStorage.setItem('sweetdreams_wishlist_sync', Date.now().toString());
            } catch(e) {}
        })
        .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
    };

    function syncHomeWishlist() {
        if (!isLoggedIn) return;
        fetch('/api/wishlist/ids', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.ok ? res.json() : null)
        .then(data => {
            if (data && Array.isArray(data.ids)) {
                const ids = data.ids.map(Number);
                const navBadge = document.querySelector('#btn-wishlist .badge');
                if (navBadge && data.count !== undefined) {
                    navBadge.textContent = data.count;
                    navBadge.style.display = data.count > 0 ? 'flex' : 'none';
                }
                document.querySelectorAll('.product-card-wishlist').forEach(b => {
                    const id = Number(b.getAttribute('data-product-id'));
                    if (ids.includes(id)) {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });
            }
        })
        .catch(() => {});
    }

    window.addEventListener('pageshow', syncHomeWishlist);
    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') syncHomeWishlist();
    });
    window.addEventListener('storage', (e) => {
        if (e.key === 'sweetdreams_wishlist_sync') syncHomeWishlist();
    });
});
</script>
@endsection
