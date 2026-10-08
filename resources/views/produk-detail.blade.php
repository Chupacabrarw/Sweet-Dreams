@extends('layouts.app')

@section('title', $product['title'] . ' - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/produk-detail.css')
@endpush

{{-- BREADCRUMB --}}
<nav class="breadcrumb-nav" id="breadcrumb-nav">
    <div class="breadcrumb-container">
        <a href="/">Home</a>
        <i data-lucide="chevron-right"></i>
        <a href="/katalog">Koleksi</a>
        <i data-lucide="chevron-right"></i>
        <a href="/katalog/{{ $product['category_slug'] }}">{{ $product['category'] }}</a>
        <i data-lucide="chevron-right"></i>
        <span class="active">{{ $product['title'] }}</span>
    </div>
</nav>

{{-- MAIN PRODUCT SECTION --}}
<section class="product-main-section" id="product-main-section">
    <div class="product-main-grid">
        
        {{-- Left: Gallery --}}
        <div class="product-gallery-wrapper" id="product-gallery">
            {{-- Vertical Thumbnails --}}
            <div class="product-thumbnails-col" id="product-thumbnails">
                @foreach($product['gallery'] as $index => $image)
                    <button class="gallery-thumb-btn {{ $index === 0 ? 'active' : '' }}" data-img-src="{{ asset($image) }}" aria-label="Thumbnail {{ $index + 1 }}">
                        <img src="{{ asset($image) }}" alt="{{ $product['title'] }} foto {{ $index + 1 }}">
                    </button>
                @endforeach
            </div>

            {{-- Main Image Box --}}
            <div class="product-main-img-box" id="product-main-img-box">
                <span class="badge-bestseller">{{ $product['badge'] ?? 'BEST SELLER' }}</span>
                                <button class="btn-wishlist-float {{ ($product['is_wishlisted'] ?? false) ? 'active' : '' }}" id="btn-wishlist-toggle" aria-label="Tambah ke Wishlist">
                    <i data-lucide="heart" style="width:20px;height:20px;"></i>
                </button>
                <img id="main-product-img" src="{{ asset($product['gallery'][0] ?? $product['main_image']) }}" alt="{{ $product['title'] }}" loading="eager">
            </div>
        </div>

        {{-- Right: Product Details --}}
        <div class="product-info-wrapper" id="product-info">
            {{-- Rating & Collection --}}
            <div class="product-meta-row">
                <div class="rating-stars">
                    @for($star = 1; $star <= 5; $star++)
                        <i data-lucide="star" style="width:16px;height:16px;fill:{{ $product['review_count'] > 0 && $star <= round((float) $product['rating']) ? '#eab308' : 'none' }};"></i>
                    @endfor
                </div>
                <span class="rating-text">{{ $product['review_count'] > 0 ? $product['rating'] . ' | ' . $product['review_count'] . ' ulasan' : 'Belum ada ulasan' }}</span>
            </div>

            <span class="collection-tag">{{ $product['collection'] }}</span>

            <h1 class="product-title">{{ $product['title'] }}</h1>

            <div class="product-price-box">
                <span class="product-current-price">{{ $product['price'] }}</span>
                @if(!empty($product['original_price']))
                    <span class="product-original-price">{{ $product['original_price'] }}</span>
                @endif
            </div>

            <p class="product-short-desc">{{ $product['short_desc'] }}</p>

            <hr class="product-section-divider">

            {{-- Color Selection --}}
            <div class="option-group" id="group-color">
                <div class="option-label-row">
                    <span class="option-title">Warna: <span class="option-value-name" id="selected-color-label">{{ $product['colors'][0]['name'] ?? 'Tidak tersedia' }}</span></span>
                </div>
                <div class="color-swatch-list">
                    @foreach($product['colors'] as $color)
                        <button 
                            class="color-swatch-item {{ $color['active'] ? 'active' : '' }}" 
                            style="background-color: {{ $color['hex'] }};" 
                            data-color-name="{{ $color['name'] }}"
                            data-color-slug="{{ $color['slug'] }}"
                            aria-label="Pilih warna {{ $color['name'] }}">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Size Selection --}}
            <div class="option-group" id="group-size">
                <div class="option-label-row">
                    <span class="option-title">Pilih ukuran</span>
                    <a href="#panduan-ukuran" class="link-size-guide" id="btn-goto-size-guide">Lihat tabel ukuran</a>
                </div>
                <div class="size-btn-list">
                    @foreach($product['sizes'] as $size)
                        @php
                            $sizeStock = collect($product['variants'])
                                ->filter(fn ($variant) => strcasecmp($variant['color'], $product['colors'][0]['name'] ?? '') === 0 && strcasecmp($variant['size'], $size) === 0)
                                ->sum('stock');
                        @endphp
                        <button 
                            class="size-pill-btn {{ $size === $product['default_size'] && $sizeStock > 0 ? 'active' : '' }}"
                            data-size="{{ $size }}"
                            @disabled($sizeStock === 0)>
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
                <p class="variant-stock-message" id="variant-stock-message" role="status"></p>
            </div>

            {{-- Quantity and Add to Cart --}}
            <div class="product-action-row">
                <div class="quantity-counter">
                    <button class="qty-btn" id="qty-minus" aria-label="Kurangi jumlah" @disabled($product['stock'] <= 0)>&minus;</button>
                    <span class="qty-display" id="qty-val">1</span>
                    <button class="qty-btn" id="qty-plus" aria-label="Tambah jumlah" @disabled($product['stock'] <= 0)>&plus;</button>
                </div>
                <button class="btn-add-to-cart" id="btn-add-to-cart" @disabled($product['stock'] <= 0)>
                    <i data-lucide="shopping-bag" style="width:20px;height:20px;"></i>
                    Tambahkan ke Keranjang
                </button>
            </div>

            {{-- Trust & Quality Badges --}}
            <div class="trust-badges-row">
                <div class="trust-badge-item">
                    <i data-lucide="check-circle-2"></i>
                    <span>Satin premium</span>
                </div>
                <div class="trust-badge-item">
                    <i data-lucide="check-circle-2"></i>
                    <span>Tukar Ukuran 7 hari</span>
                </div>
                <div class="trust-badge-item">
                    <i data-lucide="check-circle-2"></i>
                    <span>Kemasan eksklusif</span>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- PANDUAN UKURAN (CM) --}}
<section class="size-guide-section" id="panduan-ukuran">
    <h2 class="size-guide-title">Panduan Ukuran (cm)</h2>
    <div class="size-table-container" id="size-table-box">
        <table class="size-table">
            <thead>
                <tr>
                    <th>Ukuran</th>
                    <th>Lingkar Dada</th>
                    <th>Lingkar Pinggang</th>
                    <th>Lingkar Pinggul</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>S</td>
                    <td>78 - 83</td>
                    <td>64 - 68</td>
                    <td>85 - 90</td>
                </tr>
                <tr>
                    <td>M</td>
                    <td>84 - 91</td>
                    <td>69 - 73</td>
                    <td>92 - 97</td>
                </tr>
                <tr>
                    <td>L</td>
                    <td>92 - 96</td>
                    <td>74 - 78</td>
                    <td>98 - 102</td>
                </tr>
                <tr>
                    <td>XL</td>
                    <td>97 - 101</td>
                    <td>79 - 83</td>
                    <td>103 - 107</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

{{-- TABS & CARE SECTION --}}
<section class="tabs-and-care-section" id="tabs-and-care-section">
    {{-- Left: Tabs --}}
    <div class="product-tabs-wrapper">
        <div class="product-tabs-header">
            <button class="tab-nav-btn active" data-tab="deskripsi" id="tab-btn-deskripsi">Deskripsi</button>
            <button class="tab-nav-btn" data-tab="ulasan" id="tab-btn-ulasan">Ulasan ({{ $product['review_count'] }})</button>
        </div>

        {{-- Tab Panel: Deskripsi --}}
        <div class="tab-panel active" id="tab-panel-deskripsi">
            <h3 class="tab-content-title">{{ $product['long_desc_title'] }}</h3>
            <p class="tab-content-text">{{ $product['long_desc'] }}</p>

            <h4 class="tab-features-title">Fitur Produk:</h4>
            <ul class="tab-features-list">
                @foreach($product['features'] as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </div>

        {{-- Tab Panel: Ulasan --}}
        <div class="tab-panel" id="tab-panel-ulasan">
            <div class="reviews-summary-card">
                <div>
                    <div class="reviews-score">{{ $product['review_count'] > 0 ? $product['rating'] : '—' }}</div>
                    <div class="rating-stars" style="margin-top: 4px;">
                        @for($star = 1; $star <= 5; $star++)
                            <i data-lucide="star" style="width:18px;height:18px;fill:{{ $product['review_count'] > 0 && $star <= round((float) $product['rating']) ? '#eab308' : 'none' }};"></i>
                        @endfor
                    </div>
                </div>
                <div style="font-size: 0.88rem; color: var(--ink-muted); line-height: 1.5;">
                    <strong>{{ $product['review_count'] > 0 ? $product['rating'] . ' / 5 dari ' . $product['review_count'] . ' ulasan' : 'Belum ada ulasan' }}</strong><br>
                    {{ $product['review_count'] > 0 ? $product['rating_distribution'] . '% memberi nilai 4 atau 5. Semua ukuran dan warna pada produk ini dihitung bersama.' : 'Jadilah pembeli pertama yang memberikan ulasan untuk produk ini.' }}
                </div>
            </div>

            @if($flashSuccess)
                <div class="review-form-notice success" role="status">{{ $flashSuccess }}</div>
            @endif
            @if($flashError)
                <div class="review-form-notice error" role="alert">{{ $flashError }}</div>
            @endif

            @if($product['can_review'])
                <form class="product-review-form" method="POST" action="{{ route('produk.reviews.store', $product['slug']) }}">
                    @csrf
                    <h3>{{ $product['user_review'] ? 'Perbarui ulasanmu' : 'Bagikan pengalamanmu' }}</h3>
                    <p>Satu ulasan berlaku untuk produk ini secara keseluruhan, termasuk semua pilihan warna dan ukuran.</p>
                    <label for="review-rating">Rating</label>
                    <select id="review-rating" name="rating" required>
                        @for($rating = 5; $rating >= 1; $rating--)
                            <option value="{{ $rating }}" @selected((int) old('rating', $product['user_review']?->rating ?? 5) === $rating)>{{ $rating }} bintang</option>
                        @endfor
                    </select>
                    @error('rating') <p class="review-field-error">{{ $message }}</p> @enderror
                    <label for="review-body">Ulasan</label>
                    <textarea id="review-body" name="body" rows="4" minlength="5" maxlength="1500" required placeholder="Ceritakan pengalamanmu menggunakan produk ini...">{{ old('body', $product['user_review']?->body) }}</textarea>
                    @error('body') <p class="review-field-error">{{ $message }}</p> @enderror
                    <button type="submit" class="btn-submit-review">{{ $product['user_review'] ? 'Simpan Perubahan' : 'Kirim Ulasan' }}</button>
                </form>
            @elseif(!$product['user_review'])
                <p class="review-purchase-note">Ulasan tersedia setelah kamu membeli produk ini dan pesanan berstatus selesai.</p>
            @endif

            @forelse($product['reviews'] as $review)
                <article class="review-item-card">
                    <div class="review-header">
                        <span class="reviewer-name">{{ $review->user->name }} &bull; <small style="color:#10b981;font-weight:normal;">Pembeli Terverifikasi</small></span>
                        <time class="review-date" datetime="{{ $review->created_at->toDateString() }}">{{ $review->created_at->translatedFormat('d M Y') }}</time>
                    </div>
                    <div class="rating-stars" style="margin-bottom: 0.45rem;" aria-label="{{ $review->rating }} dari 5 bintang">
                        @for($star = 1; $star <= 5; $star++)
                            <i data-lucide="star" style="width:14px;height:14px;fill:{{ $star <= $review->rating ? '#eab308' : 'none' }};"></i>
                        @endfor
                    </div>
                    <p style="font-size: 0.88rem; color: var(--ink-muted); margin: 0; line-height: 1.5;">{{ $review->body }}</p>
                </article>
            @empty
                <p class="review-purchase-note">Belum ada ulasan untuk produk ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Right: Care Instructions Card --}}
    <div class="care-instructions-card" id="care-instructions-card">
        <h3 class="care-card-title">Petunjuk Perawatan Singkat</h3>
        <div class="care-items-list">
            <div class="care-item">
                <div class="care-icon-circle">
                    <i data-lucide="droplets" style="width:20px;height:20px;"></i>
                </div>
                <p class="care-item-text">Cuci dengan tangan menggunakan air dingin</p>
            </div>
            <div class="care-item">
                <div class="care-icon-circle">
                    <i data-lucide="sun-dim" style="width:20px;height:20px;"></i>
                </div>
                <p class="care-item-text">Jangan diperas keras & jemur mendatar di tempat teduh</p>
            </div>
            <div class="care-item">
                <div class="care-icon-circle">
                    <i data-lucide="flame" style="width:20px;height:20px;"></i>
                </div>
                <p class="care-item-text">Setrika suhu rendah secara terbalik</p>
            </div>
        </div>
    </div>
</section>

{{-- PRODUK SERUPA --}}
<section class="related-products-section" id="related-products-section">
    <div class="related-section-header">
        <h2 class="related-section-title">Produk Serupa</h2>
        <a href="/katalog" class="related-view-all">Lihat Semua &rarr;</a>
    </div>

    <div class="related-products-grid">
        @foreach($relatedProducts as $idx => $rel)
            <a href="/produk/{{ $rel['slug'] }}" class="related-card" id="related-prod-{{ $idx + 1 }}">
                <div class="related-card-img-box">
                    <span class="related-badge-discount">{{ $rel['discount'] }}</span>
                    <button class="related-btn-wishlist {{ ($rel['is_wishlisted'] ?? false) ? 'active' : '' }}" 
                            aria-label="Wishlist" 
                            data-product-id="{{ $rel['id'] }}" 
                            onclick="toggleRelatedWishlist(event, {{ $rel['id'] }}, this);">
                        <i data-lucide="heart" style="width:16px;height:16px;"></i>
                    </button>
                    <img src="{{ asset($rel['image']) }}" alt="{{ $rel['name'] }}" loading="lazy">
                </div>
                <div class="related-card-body">
                    <h4 class="related-card-title">{{ $rel['name'] }}</h4>
                    <div class="related-price-row">
                        <span class="related-price-current">{{ $rel['price'] }}</span>
                        <span class="related-price-original">{{ $rel['original_price'] }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>

{{-- TOAST NOTIFICATION --}}
<div class="toast-notification" id="toast-notif">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <i data-lucide="check-circle" style="width:22px;height:22px;color:#10b981;"></i>
        <span id="toast-msg">Produk berhasil ditambahkan ke keranjang!</span>
    </div>
    <a href="/keranjang" class="btn-toast-cart" style="color:#f48da8;font-weight:600;text-decoration:none;font-size:0.85rem;padding:4px 12px;background:rgba(244,141,168,0.18);border-radius:20px;transition:all 0.2s;white-space:nowrap;margin-left:0.75rem;">Lihat Keranjang &rarr;</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide Icons
    lucide.createIcons();

    // 1. Gallery Thumbnail Switcher
    const thumbnails = document.getElementById('product-thumbnails');
    const mainImg = document.getElementById('main-product-img');
    const galleryByColor = @json($product['gallery_by_color']);
    const defaultGallery = @json($product['gallery']);
    const productAssetBase = @json(asset(''));

    function galleryImageUrl(path) {
        return new URL(path.replace(/^\/+/, ''), productAssetBase).href;
    }

    function renderProductGallery(paths) {
        const images = [...new Set((Array.isArray(paths) ? paths : defaultGallery).filter(Boolean))];
        thumbnails.replaceChildren();

        images.forEach((path, index) => {
            const imageUrl = galleryImageUrl(path);
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `gallery-thumb-btn${index === 0 ? ' active' : ''}`;
            button.dataset.imgSrc = imageUrl;
            button.setAttribute('aria-label', `Thumbnail ${index + 1}`);

            const image = document.createElement('img');
            image.src = imageUrl;
            image.alt = `Foto produk ${index + 1}`;

            button.appendChild(image);
            thumbnails.appendChild(button);
        });

        if (images.length > 0) {
            mainImg.src = galleryImageUrl(images[0]);
        }
    }

    thumbnails.addEventListener('click', event => {
        const button = event.target.closest('.gallery-thumb-btn');
        if (!button) return;

        thumbnails.querySelectorAll('.gallery-thumb-btn').forEach(item => item.classList.remove('active'));
        button.classList.add('active');
        mainImg.style.opacity = '0.3';
        setTimeout(() => {
            mainImg.src = button.dataset.imgSrc;
            mainImg.style.opacity = '1';
        }, 150);
    });

    // 2. Color Selection
    const colorSwatches = document.querySelectorAll('.color-swatch-item');
    const colorLabel = document.getElementById('selected-color-label');

    colorSwatches.forEach(swatch => {
        swatch.addEventListener('click', function() {
            colorSwatches.forEach(s => s.classList.remove('active'));
            this.classList.add('active');
            const colorName = this.getAttribute('data-color-name');
            if (colorLabel && colorName) {
                colorLabel.textContent = colorName;
            }
            renderProductGallery(galleryByColor[this.dataset.colorSlug] || defaultGallery);
            updateVariantAvailability();
        });
    });

    // 3. Size Selection
    const sizeBtns = document.querySelectorAll('.size-pill-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            sizeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            updateVariantAvailability();
        });
    });

    // 4. Quantity Counter
    const qtyVal = document.getElementById('qty-val');
    const qtyPlus = document.getElementById('qty-plus');
    const qtyMinus = document.getElementById('qty-minus');
    let currentQty = 1;

    qtyPlus.addEventListener('click', function() {
        if (currentQty < selectedVariantStock) currentQty++;
        qtyVal.textContent = currentQty;
        updateQuantityControls();
    });

    qtyMinus.addEventListener('click', function() {
        if (currentQty > 1) {
            currentQty--;
            qtyVal.textContent = currentQty;
            updateQuantityControls();
        }
    });

    // 5. Wishlist Float Button
    const wishlistBtn = document.getElementById('btn-wishlist-toggle');
    const isLoggedInUser = @json(auth()->check());

    function updateNavWishlistBadge(count) {
        const navBadge = document.querySelector('#btn-wishlist .badge');
        if (navBadge && count !== undefined) {
            navBadge.textContent = count;
            navBadge.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
            if (!isLoggedInUser) {
                window.location.href = '/login';
                return;
            }
            const productId = {{ $product['id'] }};
            fetch('/api/wishlist/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ product_id: productId })
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
                wishlistBtn.classList.toggle('active', data.is_wishlisted);
                updateNavWishlistBadge(data.wishlist_count);
                try {
                    localStorage.setItem('sweetdreams_wishlist_sync', Date.now().toString());
                } catch(e) {}
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }

    window.toggleRelatedWishlist = function(e, productId, btn) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!isLoggedInUser) {
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
            updateNavWishlistBadge(data.wishlist_count);
            try {
                localStorage.setItem('sweetdreams_wishlist_sync', Date.now().toString());
            } catch(err) {}
        })
        .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
    };
    // 6. Smooth Scroll to Size Guide Table
    const gotoSizeBtn = document.getElementById('btn-goto-size-guide');
    const sizeTableBox = document.getElementById('size-table-box');
    if (gotoSizeBtn && sizeTableBox) {
        gotoSizeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            sizeTableBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            sizeTableBox.classList.add('highlight');
            setTimeout(() => {
                sizeTableBox.classList.remove('highlight');
            }, 1500);
        });
    }

    // 7. Tabs Switching
    const tabBtns = document.querySelectorAll('.tab-nav-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));

            this.classList.add('active');
            const targetTab = this.getAttribute('data-tab');
            const activePanel = document.getElementById('tab-panel-' + targetTab);
            if (activePanel) {
                activePanel.classList.add('active');
            }
        });
    });

    if (@json($errors->has('rating') || $errors->has('body')) || window.location.hash === '#tab-panel-ulasan') {
        document.getElementById('tab-btn-ulasan')?.click();
    }

    // 8. Add to Cart Toast & Dynamic Cart Insertion
    const addToCartBtn = document.getElementById('btn-add-to-cart');
    const toast = document.getElementById('toast-notif');
    const toastMsg = document.getElementById('toast-msg');
    const productVariants = @json($product['variants']);
    const stockMessage = document.getElementById('variant-stock-message');
    let selectedVariantStock = 0;

    function updateQuantityControls() {
        qtyVal.textContent = currentQty;
        qtyMinus.disabled = selectedVariantStock <= 0 || currentQty <= 1;
        qtyPlus.disabled = selectedVariantStock <= 0 || currentQty >= selectedVariantStock;
    }

    function updateVariantAvailability() {
        const selectedColor = colorLabel?.textContent.trim() || '';

        sizeBtns.forEach(btn => {
            const size = btn.dataset.size;
            const matchingVariant = productVariants.find(variant =>
                variant.color.toLowerCase() === selectedColor.toLowerCase() &&
                variant.size.toLowerCase() === size.toLowerCase()
            );
            btn.disabled = !matchingVariant || Number(matchingVariant.stock) <= 0;
            if (btn.disabled) btn.classList.remove('active');
        });

        let activeSize = document.querySelector('.size-pill-btn.active:not(:disabled)');
        if (!activeSize) {
            activeSize = Array.from(sizeBtns).find(btn => !btn.disabled) || null;
            sizeBtns.forEach(btn => btn.classList.remove('active'));
            activeSize?.classList.add('active');
        }

        const selectedVariant = activeSize
            ? productVariants.find(variant =>
                variant.color.toLowerCase() === selectedColor.toLowerCase() &&
                variant.size.toLowerCase() === activeSize.dataset.size.toLowerCase()
            )
            : null;
        selectedVariantStock = Number(selectedVariant?.stock || 0);
        currentQty = selectedVariantStock > 0 ? Math.min(currentQty, selectedVariantStock) : 1;
        addToCartBtn.disabled = selectedVariantStock <= 0;

        if (stockMessage) {
            stockMessage.textContent = selectedVariantStock > 0
                ? `Stok tersedia: ${selectedVariantStock} unit`
                : 'Kombinasi warna dan ukuran ini sedang habis.';
            stockMessage.classList.toggle('is-unavailable', selectedVariantStock <= 0);
        }

        updateQuantityControls();
    }

    updateVariantAvailability();

    if (addToCartBtn && toast) {
        const isLoggedIn = @json(auth()->check());
        addToCartBtn.addEventListener('click', function() {
            if (!isLoggedIn) {
    window.location.href = '/login';
    return;
}
            const selectedSize = document.querySelector('.size-pill-btn.active')?.textContent.trim() || 'M';
            const selectedColor = colorLabel ? colorLabel.textContent.trim() : 'Red';
            const productPrice = {{ $product['price_raw'] ?? 280000 }};
            const productTitle = "{{ $product['title'] }}";
            const productSlug = "{{ $product['slug'] }}";
            const productImage = "{{ asset($product['main_image']) }}";

            // Add to dynamic cart
                        const productId = {{ $product['id'] }};

            fetch('/api/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    size: selectedSize,
                    color: selectedColor,
                    quantity: currentQty
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 201) {
                    const badge = document.querySelector('#btn-cart .badge');
                    if (badge) badge.textContent = body.cart_count;

                    toastMsg.textContent = `${currentQty}x ${productTitle} (${selectedColor}, ${selectedSize}) ditambahkan!`;
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 4000);
                } else {
                    alert(body.message || 'Gagal menambahkan ke keranjang.');
                }
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }
});
</script>
@endsection
