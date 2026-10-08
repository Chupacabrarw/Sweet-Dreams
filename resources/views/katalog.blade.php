@extends('layouts.app')

@section('title', 'Katalog Produk - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/katalog.css')
@endpush

{{-- PAGE HEADER --}}
<div class="katalog-header" id="katalog-header">
    <p class="section-eyebrow" style="margin-bottom:0.6rem;">Koleksi Sweet Dreams</p>
    <h1>Katalog Produk</h1>
    <p>Temukan busana tidur dan pakaian dalam premium untuk kenyamanan malam terbaikmu</p>
</div>

{{-- FILTER OVERLAY (MOBILE) --}}
<div class="filter-overlay" id="filter-overlay"></div>

{{-- KATALOG LAYOUT --}}
<div class="katalog-layout" id="katalog-layout">
    {{-- FILTER SIDEBAR --}}
    <aside class="filter-sidebar" id="filter-sidebar">
        <div class="filter-sidebar-close">
            <button id="filter-close-btn" aria-label="Tutup Filter">
                <i data-lucide="x" style="width:22px;height:22px;"></i>
            </button>
        </div>

        <div class="filter-header-row">
            <h2 class="filter-title">Filter</h2>
            <button type="button" class="filter-quick-clear" id="filter-quick-clear">Hapus Semua</button>
        </div>
        <p class="filter-subtitle">Sesuaikan preferensi untuk menemukan koleksi yang tepat</p>
        <p class="filter-stock-note">Semua ukuran dan warna yang diatur ditampilkan. Pilihan bertanda “Habis” belum bisa dipilih karena stoknya kosong.</p>

        {{-- Kategori --}}
        <div class="filter-group" id="filter-kategori">
            <p class="filter-group-label">
                <span>Kategori</span>
                <span class="filter-badge-count" id="cat-badge-count" style="display:none;">0</span>
            </p>
            @foreach($categories as $index => $category)
                <label class="filter-checkbox category-filter-option" data-category-slug="{{ $category->slug }}" data-category-name="{{ $category->name }}" @if($index >= 4) hidden @endif>
                    <input type="checkbox" name="kategori" value="{{ $category->slug }}" {{ $activeCategory === $category->slug ? 'checked' : '' }}>
                    <span>{{ $category->name }}</span>
                </label>
            @endforeach
            <button type="button" class="color-options-toggle category-options-toggle" id="category-options-toggle" aria-expanded="false" hidden>
                Kategori lainnya
            </button>
        </div>

        {{-- Ukuran --}}
        <div class="filter-group" id="filter-ukuran">
            <p class="filter-group-label">
                <span>Ukuran</span>
                <span class="filter-badge-count" id="size-badge-count" style="display:none;">0</span>
            </p>
            <div class="size-options" id="size-options-container">
            </div>
        </div>

        {{-- Warna --}}
        <div class="filter-group" id="filter-warna">
            <p class="filter-group-label">
                <span>Warna</span>
                <span class="filter-badge-count" id="color-badge-count" style="display:none;">0</span>
            </p>
            <div class="color-options" id="color-options-container">
            </div>
            <button type="button" class="color-options-toggle" id="color-options-toggle" aria-expanded="false" hidden>
                Warna lainnya
            </button>
        </div>

        {{-- Rentang Harga --}}
        <div class="filter-group" id="filter-harga">
            <p class="filter-group-label">Rentang Harga</p>
            <div class="price-inputs">
                <div class="price-input-group">
                    <label for="price-min-input">Min</label>
                    <input type="text" id="price-min-input" value="Rp 0">
                </div>
                <div class="price-input-group">
                    <label for="price-max-input">Max</label>
                    <input type="text" id="price-max-input" value="Rp 1.000.000">
                </div>
            </div>
            <div class="price-slider" id="price-slider">
                <div class="price-slider-track" id="price-slider-track"></div>
                <input type="range" id="price-range-min" min="0" max="1000000" value="0" step="10000" aria-label="Harga Minimal">
                <input type="range" id="price-range-max" min="0" max="1000000" value="1000000" step="10000" aria-label="Harga Maksimal">
            </div>
            <p class="price-range-label" id="price-range-label">Rp 0 - Rp 1.000.000</p>
        </div>

        {{-- Reset Button --}}
        <button type="button" class="btn-reset-filter" id="btn-reset-filter">
            <i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i>
            Reset Filter
        </button>
    </aside>

    {{-- PRODUCTS CONTENT --}}
    <div class="products-content" id="products-content">
        {{-- Mobile Filter Button --}}
        <button type="button" class="mobile-filter-toggle" id="mobile-filter-toggle">
            <i data-lucide="sliders-horizontal" style="width:18px;height:18px;"></i>
            Filter & Urutkan
        </button>

        <div class="products-content-header">
            <div class="category-title">
                <h2 id="active-category-title">
                    @if($activeCategory === 'baju-tidur') Baju Tidur
                    @elseif($activeCategory === 'lingerie') Lingerie
                    @elseif($activeCategory === 'kimono') Kimono
                    @elseif($activeCategory === 'pakaian-dalam') Pakaian Dalam
                    @else Semua Produk
                    @endif
                </h2>
                <p id="product-count">Memuat produk...</p>
            </div>
            <div class="sort-dropdown">
                <label for="sort-select">Urutkan:</label>
                <select id="sort-select">
                    <option value="newest" selected>Terbaru</option>
                    <option value="price-low">Harga Terendah</option>
                    <option value="price-high">Harga Tertinggi</option>
                    <option value="popular">Terpopuler</option>
                </select>
            </div>
        </div>

        {{-- Active Filter Chips --}}
        <div class="active-filter-chips" id="active-filter-chips"></div>

        {{-- Grid Produk --}}
        <div class="katalog-products-grid" id="katalog-products-grid">
            {{-- Initial server-side fallback rendered products --}}
            @php
                $initialList = $activeCategory 
                    ? array_values(array_filter($products, fn($p) => $p['category'] === $activeCategory))
                    : $products;
            @endphp
            @foreach($initialList as $prod)
                <div class="katalog-product-card" data-slug="{{ $prod['slug'] }}" onclick="window.location.href='/produk/{{ $prod['slug'] }}'">
                    <div class="katalog-product-card-img">
                        <img src="{{ asset($prod['image']) }}" alt="{{ $prod['title'] }}" loading="lazy">
                        @if(!empty($prod['discount']))
                            <span class="card-discount-badge">{{ $prod['discount'] }}</span>
                        @endif
                        <button type="button" class="card-wishlist-btn {{ in_array($prod['id'], $wishlistIds ?? []) ? 'active' : '' }}" data-product-id="{{ $prod['id'] }}" aria-label="Tambah ke Wishlist" onclick="toggleWishlist(event, {{ $prod['id'] }}, this);">
                            <i data-lucide="heart" style="width:18px;height:18px;"></i>
                        </button>
                    </div>
                    <div class="product-tags">
                        <span class="product-tag-badge">{{ $prod['badge'] ?? $prod['category_name'] }}</span>
                        <span class="product-tag-size">Ukuran {{ implode(', ', $prod['sizes']) }}</span>
                        <span class="product-tag-stock" style="font-size: 0.75rem; color: var(--ink-muted); white-space: nowrap;">Stok: {{ $prod['stock'] ?? 0 }}</span>
                    </div>
                    <div class="katalog-product-card-body">
                        <div class="product-rating">
                            <i data-lucide="star" style="width:14px;height:14px;"></i>
                            <span class="product-rating-score">{{ $prod['review_count'] > 0 ? $prod['rating'] : '—' }}</span>
                            <span>({{ $prod['review_count'] }})</span>
                        </div>
                        <h3><a href="/produk/{{ $prod['slug'] }}" style="color:inherit;text-decoration:none;" onclick="event.stopPropagation();">{{ $prod['title'] }}</a></h3>
                        <p class="product-desc">{{ $prod['short_desc'] }}</p>
                        <div class="katalog-product-card-footer">
                            <div class="price-wrapper">
                                <span class="price">{{ $prod['price'] }}</span>
                                @if(!empty($prod['original_price']))
                                    <span class="original-price">{{ $prod['original_price'] }}</span>
                                @endif
                            </div>
                            <a href="/produk/{{ $prod['slug'] }}" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="katalog-pagination" id="katalog-pagination"></div>
        {{-- Empty State (hidden by default) --}}
        <div class="catalog-empty-state" id="catalog-empty-state" style="display: none;">
            <div class="catalog-empty-icon">
                <i data-lucide="package-search" style="width:36px;height:36px;"></i>
            </div>
            <h3>Tidak Ada Produk yang Cocok</h3>
            <p>Tidak ditemukan produk yang memenuhi kombinasi filter yang Anda pilih. Coba sesuaikan rentang harga, ukuran, atau reset filter.</p>
            <button type="button" class="btn-reset-filter" id="btn-empty-reset">
                <i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i>
                Reset Filter
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. DATASET
    const rawProducts = @json($products ?? []);
    const initialCategoryParam = @json($activeCategory);
    const sizeOptionsContainer = document.getElementById('size-options-container');
    const colorOptionsContainer = document.getElementById('color-options-container');
    const colorOptionsToggle = document.getElementById('color-options-toggle');
    const colorOptionItems = [];
    const categoryOptionItems = [...document.querySelectorAll('.category-filter-option')];
    const categoryOptionsToggle = document.getElementById('category-options-toggle');
    const categoryNameBySlug = new Map(categoryOptionItems.map(option => [
        option.dataset.categorySlug,
        option.dataset.categoryName,
    ]));
    const hiddenCategoryCount = Math.max(0, categoryOptionItems.length - 4);
    categoryOptionsToggle.hidden = hiddenCategoryCount === 0;
    categoryOptionsToggle.textContent = `Kategori lainnya (+${hiddenCategoryCount})`;

    function setCategoryOptionsExpanded(expanded) {
        categoryOptionItems.forEach((option, index) => {
            option.hidden = !expanded && index >= 4;
        });
        categoryOptionsToggle.textContent = expanded
            ? 'Tampilkan lebih sedikit'
            : `Kategori lainnya (+${hiddenCategoryCount})`;
        categoryOptionsToggle.setAttribute('aria-expanded', String(expanded));
    }

    categoryOptionsToggle.addEventListener('click', () => {
        setCategoryOptionsExpanded(categoryOptionsToggle.getAttribute('aria-expanded') !== 'true');
    });

    const configuredVariants = rawProducts.flatMap(product => product.configured_variants || []);
    const sizeAvailability = new Map();
    configuredVariants.forEach(variant => {
        sizeAvailability.set(
            variant.size,
            (sizeAvailability.get(variant.size) || false) || variant.stock > 0
        );
    });
    const availableSizes = [...sizeAvailability.keys()];
    const commonSizeOrder = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL', 'ONE SIZE'];
    availableSizes.sort((a, b) => {
        const aIndex = commonSizeOrder.indexOf(a.toUpperCase());
        const bIndex = commonSizeOrder.indexOf(b.toUpperCase());
        if (aIndex !== -1 || bIndex !== -1) {
            return (aIndex === -1 ? commonSizeOrder.length : aIndex) -
                (bIndex === -1 ? commonSizeOrder.length : bIndex);
        }
        return a.localeCompare(b);
    });
    availableSizes.forEach(size => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'size-btn';
        button.dataset.size = size;
        button.textContent = size;
        button.disabled = !sizeAvailability.get(size);
        if (button.disabled) {
            button.title = `${size} — stok habis`;
            button.setAttribute('aria-label', `${size}, stok habis`);
        }
        sizeOptionsContainer.appendChild(button);
    });

    const colorHex = {
        pink: '#e8a0b0', gold: '#d4a854', white: '#f5f0ec',
        cream: '#eedfc8', grey: '#6a6a7a', gray: '#6a6a7a',
        black: '#272329', red: '#c53c50', blue: '#4c74a5',
        green: '#54836c', purple: '#8665a7', navy: '#27385d',
        brown: '#80604b',
    };
    const availableColors = new Map();
    configuredVariants.forEach(variant => {
        const color = availableColors.get(variant.color) || {
            name: variant.color_name,
            hex: variant.color_hex || colorHex[variant.color] || '#b58d97',
            inStock: false,
        };
        color.inStock = color.inStock || variant.stock > 0;
        availableColors.set(variant.color, color);
    });
    [...availableColors.entries()].sort(([a], [b]) => a.localeCompare(b)).forEach(([color, colorInfo], index) => {
        const option = document.createElement('div');
        option.className = 'color-filter-option';
        option.hidden = index >= 5;
        const displayName = colorInfo.name.replace(/\b\w/g, character => character.toUpperCase());

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'color-btn';
        button.dataset.color = color;
        button.dataset.colorName = displayName;
        button.style.backgroundColor = colorInfo.hex;
        button.disabled = !colorInfo.inStock;
        if (button.disabled) {
            button.title = `${displayName} — stok habis`;
            button.setAttribute('aria-label', `${displayName}, stok habis`);
        }
        if (color === 'white' || color === 'cream') {
            button.style.border = '1.5px solid rgba(180,140,150,0.35)';
        }
        if (!button.disabled) {
            button.title = displayName;
            button.setAttribute('aria-label', displayName);
        }

        const name = document.createElement('span');
        name.className = 'color-filter-name';
        name.textContent = button.disabled ? `${displayName} · Habis` : displayName;

        option.append(button, name);
        colorOptionsContainer.appendChild(option);
        colorOptionItems.push(option);
    });
    const hiddenColorCount = Math.max(0, colorOptionItems.length - 5);
    colorOptionsToggle.hidden = hiddenColorCount === 0;
    colorOptionsToggle.textContent = `Warna lainnya (+${hiddenColorCount})`;

    function setColorOptionsExpanded(expanded) {
        colorOptionItems.forEach((option, index) => {
            option.hidden = !expanded && index >= 5;
        });
        colorOptionsToggle.textContent = expanded ? 'Tampilkan lebih sedikit' : `Warna lainnya (+${hiddenColorCount})`;
        colorOptionsToggle.setAttribute('aria-expanded', String(expanded));
    }

    colorOptionsToggle.addEventListener('click', () => {
        setColorOptionsExpanded(colorOptionsToggle.getAttribute('aria-expanded') !== 'true');
    });

    // 2. DOM ELEMENTS
    const productsGrid = document.getElementById('katalog-products-grid');
    const emptyState = document.getElementById('catalog-empty-state');
    const activeCategoryTitle = document.getElementById('active-category-title');
    const productCountEl = document.getElementById('product-count');
    const activeFilterChips = document.getElementById('active-filter-chips');

    // Filter controls
    const categoryCheckboxes = document.querySelectorAll('input[name="kategori"]');
    const sizeButtons = document.querySelectorAll('.size-btn');
    const colorButtons = document.querySelectorAll('.color-btn');
    const rangeMin = document.getElementById('price-range-min');
    const rangeMax = document.getElementById('price-range-max');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');
    const priceRangeLabel = document.getElementById('price-range-label');
    const sliderTrack = document.getElementById('price-slider-track');
    const sortSelect = document.getElementById('sort-select');
    const btnResetFilter = document.getElementById('btn-reset-filter');
    const btnQuickClear = document.getElementById('filter-quick-clear');
    const btnEmptyReset = document.getElementById('btn-empty-reset');

    // Badge counts in sidebar
    const catBadgeCount = document.getElementById('cat-badge-count');
    const sizeBadgeCount = document.getElementById('size-badge-count');
    const colorBadgeCount = document.getElementById('color-badge-count');

    // Mobile filter elements
    const filterSidebar = document.getElementById('filter-sidebar');
    const filterOverlay = document.getElementById('filter-overlay');
    const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
    const filterCloseBtn = document.getElementById('filter-close-btn');

    // Search input from navbar
    const navSearchInput = document.getElementById('search-input');
    const mobileNavSearchInput = document.querySelector('.mobile-nav-search input');

    // 3. STATE
      const state = {
        categories: [],
        sizes: [],
        colors: [],
        minPrice: 0,
        maxPrice: 1000000,
        sort: 'newest',
        search: '',
        page: 1
    };

    const PRODUCTS_PER_PAGE = 8;
    window.initialWishlist = (@json($wishlistIds ?? [])).map(Number);

    // Wishlist tersimpan di database
    let wishlistIds = window.initialWishlist || [];
    function getWishlist() {
        return wishlistIds;
    }
    function updateWishlistBadge() {
        const badge = document.querySelector('#btn-wishlist .badge');
        if (badge) {
            badge.textContent = wishlistIds.length;
            badge.style.display = wishlistIds.length > 0 ? 'flex' : 'none';
        }
    }

    // Currency Formatter
    function formatRupiah(num) {
        return 'Rp ' + Number(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function parseRupiah(str) {
        if (!str) return 0;
        const cleaned = str.toString().replace(/[^0-9]/g, '');
        return cleaned ? parseInt(cleaned, 10) : 0;
    }

    // 4. URL SYNC & INITIALIZATION
    const urlParams = new URLSearchParams(window.location.search);

    // Initial search
    if (urlParams.has('q')) {
        state.search = urlParams.get('q').trim();
        if (navSearchInput) navSearchInput.value = state.search;
        if (mobileNavSearchInput) mobileNavSearchInput.value = state.search;
    } else if (urlParams.has('search')) {
        state.search = urlParams.get('search').trim();
        if (navSearchInput) navSearchInput.value = state.search;
        if (mobileNavSearchInput) mobileNavSearchInput.value = state.search;
    }

    // Initial categories: URL path /katalog/{category} takes precedence, then query param
    if (initialCategoryParam) {
        state.categories = [initialCategoryParam];
    } else if (urlParams.has('category')) {
        const cats = urlParams.get('category').split(',').map(s => s.trim()).filter(Boolean);
        if (cats.length) state.categories = cats;
    }

    // Sync checkboxes
    categoryCheckboxes.forEach(cb => {
        cb.checked = state.categories.includes(cb.value);
    });
    if (categoryOptionItems.some(option => option.hidden && option.querySelector('input').checked)) {
        setCategoryOptionsExpanded(true);
    }

    // Initial sizes
    if (urlParams.has('sizes')) {
        state.sizes = urlParams.get('sizes').split(',').map(s => s.trim()).filter(Boolean);
        sizeButtons.forEach(btn => {
            if (state.sizes.includes(btn.dataset.size)) {
                btn.classList.add('active');
            }
        });
    }

    // Initial colors
    if (urlParams.has('colors')) {
        state.colors = urlParams.get('colors').split(',').map(s => s.trim()).filter(Boolean);
        colorButtons.forEach(btn => {
            if (state.colors.includes(btn.dataset.color)) {
                btn.classList.add('active');
            }
        });
        if (colorOptionItems.some(option => option.hidden && option.querySelector('.color-btn.active'))) {
            setColorOptionsExpanded(true);
        }
    }

    // Initial price
    if (urlParams.has('min_price')) {
        state.minPrice = Math.max(0, parseInt(urlParams.get('min_price'), 10) || 0);
        rangeMin.value = state.minPrice;
    }
    if (urlParams.has('max_price')) {
        state.maxPrice = Math.min(1000000, parseInt(urlParams.get('max_price'), 10) || 1000000);
        rangeMax.value = state.maxPrice;
    }

    // Initial sort
    if (urlParams.has('sort')) {
        state.sort = urlParams.get('sort');
        sortSelect.value = state.sort;
    }

    // 5. SLIDER FUNCTIONS
    function updateSliderVisuals() {
        let minVal = parseInt(rangeMin.value, 10);
        let maxVal = parseInt(rangeMax.value, 10);

        if (minVal > maxVal) {
            [rangeMin.value, rangeMax.value] = [maxVal, minVal];
            minVal = parseInt(rangeMin.value, 10);
            maxVal = parseInt(rangeMax.value, 10);
        }

        const minPercent = (minVal / 1000000) * 100;
        const maxPercent = (maxVal / 1000000) * 100;
        sliderTrack.style.left = minPercent + '%';
        sliderTrack.style.right = (100 - maxPercent) + '%';

        priceMinInput.value = formatRupiah(minVal);
        priceMaxInput.value = formatRupiah(maxVal);
        priceRangeLabel.textContent = formatRupiah(minVal) + ' - ' + formatRupiah(maxVal);

        state.minPrice = minVal;
        state.maxPrice = maxVal;
    }

    rangeMin.addEventListener('input', () => {
        updateSliderVisuals();
        renderProducts();
    });

    rangeMax.addEventListener('input', () => {
        updateSliderVisuals();
        renderProducts();
    });

    priceMinInput.addEventListener('change', () => {
        let val = parseRupiah(priceMinInput.value);
        if (val < 0) val = 0;
        if (val > parseInt(rangeMax.value, 10)) val = parseInt(rangeMax.value, 10);
        rangeMin.value = val;
        updateSliderVisuals();
        renderProducts();
    });

    priceMaxInput.addEventListener('change', () => {
        let val = parseRupiah(priceMaxInput.value);
        if (val > 1000000) val = 1000000;
        if (val < parseInt(rangeMin.value, 10)) val = parseInt(rangeMin.value, 10);
        rangeMax.value = val;
        updateSliderVisuals();
        renderProducts();
    });

    updateSliderVisuals();

    // 6. EVENT LISTENERS FOR CONTROLS
    // Category checkboxes
    categoryCheckboxes.forEach(cb => {
        cb.addEventListener('change', () => {
            const selected = [];
            categoryCheckboxes.forEach(c => {
                if (c.checked) selected.push(c.value);
            });
            state.categories = selected;
            renderProducts();
        });
    });

    // Size buttons
    sizeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const selected = [];
            sizeButtons.forEach(b => {
                if (b.classList.contains('active')) selected.push(b.dataset.size);
            });
            state.sizes = selected;
            renderProducts();
        });
    });

    // Color buttons
    colorButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            const selected = [];
            colorButtons.forEach(b => {
                if (b.classList.contains('active')) selected.push(b.dataset.color);
            });
            state.colors = selected;
            renderProducts();
        });
    });

    // Sort select
    sortSelect.addEventListener('change', () => {
        state.sort = sortSelect.value;
        renderProducts();
    });

    // Reset Filter function
    function resetAllFilters() {
        state.categories = [];
        state.sizes = [];
        state.colors = [];
        state.minPrice = 0;
        state.maxPrice = 1000000;
        state.sort = 'newest';
        state.search = '';

        categoryCheckboxes.forEach(cb => cb.checked = false);
        sizeButtons.forEach(btn => btn.classList.remove('active'));
        colorButtons.forEach(btn => btn.classList.remove('active'));
        rangeMin.value = 0;
        rangeMax.value = 1000000;
        sortSelect.value = 'newest';
        if (navSearchInput) navSearchInput.value = '';
        if (mobileNavSearchInput) mobileNavSearchInput.value = '';

        updateSliderVisuals();
        renderProducts();
    }

    if (btnResetFilter) btnResetFilter.addEventListener('click', resetAllFilters);
    if (btnQuickClear) btnQuickClear.addEventListener('click', resetAllFilters);
    if (btnEmptyReset) btnEmptyReset.addEventListener('click', resetAllFilters);

    // Search bar debounce
    let searchDebounceTimeout = null;
    function handleSearchInput(e) {
        clearTimeout(searchDebounceTimeout);
        searchDebounceTimeout = setTimeout(() => {
            state.search = e.target.value.trim();
            renderProducts();
        }, 250);
    }
    if (navSearchInput) navSearchInput.addEventListener('input', handleSearchInput);
    if (mobileNavSearchInput) mobileNavSearchInput.addEventListener('input', handleSearchInput);

    // 7. FILTER & SORT LOGIC
    function filterProducts() {
        return rawProducts.filter(item => {
            // Category check
            if (state.categories.length > 0) {
                if (!state.categories.includes(item.category)) {
                    return false;
                }
            }

            // Size and color must belong to the same in-stock variant.
            if (state.sizes.length > 0 || state.colors.length > 0) {
                const hasAvailableVariant = (item.variants || []).some(variant =>
                    (state.sizes.length === 0 || state.sizes.includes(variant.size)) &&
                    (state.colors.length === 0 || state.colors.includes(variant.color))
                );
                if (!hasAvailableVariant) return false;
            }

            // Price check
            const rawPrice = item.price_raw || 0;
            if (rawPrice < state.minPrice || rawPrice > state.maxPrice) {
                return false;
            }

            // Search query check
            if (state.search) {
                const q = state.search.toLowerCase();
                const matchTitle = item.title && item.title.toLowerCase().includes(q);
                const matchDesc = item.short_desc && item.short_desc.toLowerCase().includes(q);
                const matchCat = item.category_name && item.category_name.toLowerCase().includes(q);
                const matchBadge = item.badge && item.badge.toLowerCase().includes(q);
                if (!matchTitle && !matchDesc && !matchCat && !matchBadge) {
                    return false;
                }
            }

            return true;
        });
    }

    function sortProductsList(list) {
        const sorted = [...list];
        switch (state.sort) {
            case 'price-low':
                sorted.sort((a, b) => (a.price_raw || 0) - (b.price_raw || 0));
                break;
            case 'price-high':
                sorted.sort((a, b) => (b.price_raw || 0) - (a.price_raw || 0));
                break;
            case 'popular':
                sorted.sort((a, b) => {
                    const scoreA = (parseFloat(a.rating) || 0) * (a.sales_count || 100);
                    const scoreB = (parseFloat(b.rating) || 0) * (b.sales_count || 100);
                    return scoreB - scoreA;
                });
                break;
            case 'newest':
            default:
                sorted.sort((a, b) => (b.id || 0) - (a.id || 0));
                break;
        }
        return sorted;
    }

    // 8. RENDER FUNCTION
    function renderProducts() {
        const filtered = filterProducts();
        const sorted = sortProductsList(filtered);
        const wishlist = getWishlist();

        // Update badge counters
        catBadgeCount.textContent = state.categories.length;
        catBadgeCount.style.display = state.categories.length > 0 ? 'inline-block' : 'none';

        sizeBadgeCount.textContent = state.sizes.length;
        sizeBadgeCount.style.display = state.sizes.length > 0 ? 'inline-block' : 'none';

        colorBadgeCount.textContent = state.colors.length;
        colorBadgeCount.style.display = state.colors.length > 0 ? 'inline-block' : 'none';

        // Update Title
        if (state.search) {
            activeCategoryTitle.textContent = `Pencarian: "${state.search}"`;
        } else if (state.categories.length === 1) {
            activeCategoryTitle.textContent = categoryNameBySlug.get(state.categories[0]) || 'Katalog';
        } else if (state.categories.length > 1) {
            activeCategoryTitle.textContent = 'Koleksi Terpilih';
        } else {
            activeCategoryTitle.textContent = 'Semua Produk';
        }

        // Update Count
        productCountEl.textContent = `${sorted.length} produk ditemukan`;

        // Render Active Filter Chips
        renderChips();

        // Update URL query (smooth replaceState)
        updateUrlParams();

        // Show / hide Empty State
                if (sorted.length === 0) {
            productsGrid.style.display = 'none';
            emptyState.style.display = 'flex';
            lucide.createIcons();
            renderPagination(0);
            return;
        }

                emptyState.style.display = 'none';
        productsGrid.style.display = 'grid';

        // Pagination slicing
        const totalPages = Math.ceil(sorted.length / PRODUCTS_PER_PAGE);
        if (state.page > totalPages) state.page = totalPages;
        if (state.page < 1) state.page = 1;
        const pageStart = (state.page - 1) * PRODUCTS_PER_PAGE;
        const pageItems = sorted.slice(pageStart, pageStart + PRODUCTS_PER_PAGE);

        // Build HTML
        const html = pageItems.map((prod, index) => {
            const isWishlisted = wishlist.map(Number).includes(Number(prod.id));
            const discountBadge = prod.discount 
                ? `<span class="card-discount-badge">${prod.discount}</span>` 
                : '';
            const origPrice = prod.original_price 
                ? `<span class="original-price">${prod.original_price}</span>` 
                : '';
            const sizeList = Array.isArray(prod.sizes) && prod.sizes.length
                ? prod.sizes.join(', ')
                : 'Belum diatur';
            const badgeLabel = prod.badge || prod.category_name;

            return `
                <div class="katalog-product-card" data-slug="${prod.slug}" onclick="window.location.href='/produk/${prod.slug}'">
                    <div class="katalog-product-card-img">
                        <img src="/${prod.image}" alt="${prod.title}" loading="lazy">
                        ${discountBadge}
                        <button type="button" 
                                class="card-wishlist-btn ${isWishlisted ? 'active' : ''}" 
                                data-product-id="${prod.id}" 
                                aria-label="Tambah ke Wishlist" 
                                onclick="toggleWishlist(event, ${prod.id}, this);">
                            <i data-lucide="heart" style="width:18px;height:18px;"></i>
                        </button>
                    </div>
                    <div class="product-tags">
                        <span class="product-tag-badge">${badgeLabel}</span>
                        <span class="product-tag-size">Ukuran ${sizeList}</span>
                        <span class="product-tag-stock" style="font-size: 0.75rem; color: var(--ink-muted); white-space: nowrap;">Stok: ${prod.stock || 0}</span>
                    </div>
                    <div class="katalog-product-card-body">
                        <div class="product-rating">
                            <i data-lucide="star" style="width:14px;height:14px;"></i>
                            <span class="product-rating-score">${prod.review_count > 0 ? prod.rating : '—'}</span>
                            <span>(${prod.review_count})</span>
                        </div>
                        <h3><a href="/produk/${prod.slug}" style="color:inherit;text-decoration:none;" onclick="event.stopPropagation();">${prod.title}</a></h3>
                        <p class="product-desc">${prod.short_desc}</p>
                        <div class="katalog-product-card-footer">
                            <div class="price-wrapper">
                                <span class="price">${prod.price}</span>
                                ${origPrice}
                            </div>
                            <a href="/produk/${prod.slug}" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

                productsGrid.innerHTML = html;
        lucide.createIcons();
        renderPagination(sorted.length);
    }

    function renderPagination(totalItems) {
        const container = document.getElementById('katalog-pagination');
        if (!container) return;

        const totalPages = Math.ceil(totalItems / PRODUCTS_PER_PAGE);
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let buttons = `<button type="button" class="pagination-btn" data-page="${state.page - 1}" ${state.page === 1 ? 'disabled' : ''}>‹</button>`;
        for (let i = 1; i <= totalPages; i++) {
            buttons += `<button type="button" class="pagination-btn ${i === state.page ? 'active' : ''}" data-page="${i}">${i}</button>`;
        }
        buttons += `<button type="button" class="pagination-btn" data-page="${state.page + 1}" ${state.page === totalPages ? 'disabled' : ''}>›</button>`;

        container.innerHTML = buttons;

        container.querySelectorAll('.pagination-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPage = parseInt(this.getAttribute('data-page'));
                if (!targetPage || targetPage < 1 || targetPage > totalPages) return;
                state.page = targetPage;
                renderProducts();
                document.getElementById('katalog-products-grid').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    }


    // 9. FILTER CHIPS
    function renderChips() {
        const chips = [];

        // Search chip
        if (state.search) {
            chips.push(`
                <span class="filter-chip">
                    Cari: "${state.search}"
                    <button type="button" class="filter-chip-btn" onclick="clearSearchFilter()" aria-label="Hapus pencarian">&times;</button>
                </span>
            `);
        }

        // Category chips
        state.categories.forEach(cat => {
            const categoryName = categoryNameBySlug.get(cat) || cat;
            const safeCategoryName = categoryName.replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            })[character]);
            chips.push(`
                <span class="filter-chip">
                    ${safeCategoryName}
                    <button type="button" class="filter-chip-btn" onclick="removeCategoryFilter('${cat}')" aria-label="Hapus kategori ${safeCategoryName}">&times;</button>
                </span>
            `);
        });

        // Size chips
        state.sizes.forEach(size => {
            chips.push(`
                <span class="filter-chip">
                    Ukuran ${size}
                    <button type="button" class="filter-chip-btn" onclick="removeSizeFilter('${size}')" aria-label="Hapus ukuran ${size}">&times;</button>
                </span>
            `);
        });

        // Color chips
        state.colors.forEach(col => {
            const capColor = col.charAt(0).toUpperCase() + col.slice(1);
            chips.push(`
                <span class="filter-chip">
                    Warna ${capColor}
                    <button type="button" class="filter-chip-btn" onclick="removeColorFilter('${col}')" aria-label="Hapus warna ${col}">&times;</button>
                </span>
            `);
        });

        // Price chip (if different from bounds)
        if (state.minPrice > 0 || state.maxPrice < 1000000) {
            chips.push(`
                <span class="filter-chip">
                    ${formatRupiah(state.minPrice)} - ${formatRupiah(state.maxPrice)}
                    <button type="button" class="filter-chip-btn" onclick="resetPriceFilter()" aria-label="Reset rentang harga">&times;</button>
                </span>
            `);
        }

        if (chips.length > 0) {
            chips.push(`
                <button type="button" class="clear-all-chips" onclick="resetAllFiltersFromChip()">Reset Semua</button>
            `);
            activeFilterChips.innerHTML = chips.join('');
            activeFilterChips.style.display = 'flex';
        } else {
            activeFilterChips.innerHTML = '';
            activeFilterChips.style.display = 'none';
        }
    }

    // Window helpers for chip clicks
    window.clearSearchFilter = function() {
        state.search = '';
        if (navSearchInput) navSearchInput.value = '';
        if (mobileNavSearchInput) mobileNavSearchInput.value = '';
        renderProducts();
    };

    window.removeCategoryFilter = function(cat) {
        state.categories = state.categories.filter(c => c !== cat);
        categoryCheckboxes.forEach(cb => {
            if (cb.value === cat) cb.checked = false;
        });
        renderProducts();
    };

    window.removeSizeFilter = function(size) {
        state.sizes = state.sizes.filter(s => s !== size);
        sizeButtons.forEach(btn => {
            if (btn.dataset.size === size) btn.classList.remove('active');
        });
        renderProducts();
    };

    window.removeColorFilter = function(color) {
        state.colors = state.colors.filter(c => c !== color);
        colorButtons.forEach(btn => {
            if (btn.dataset.color === color) btn.classList.remove('active');
        });
        renderProducts();
    };

    window.resetPriceFilter = function() {
        rangeMin.value = 0;
        rangeMax.value = 1000000;
        updateSliderVisuals();
        renderProducts();
    };

    window.resetAllFiltersFromChip = function() {
        resetAllFilters();
    };

    // Wishlist toggle
    const isLoggedIn = @json(auth()->check());
    window.toggleWishlist = function(e, productId, btn) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        if (!isLoggedIn) {
            window.location.href = '/login';
            return;
        }

        const pid = Number(productId);
        fetch('/api/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: pid })
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
            if (data.is_wishlisted) {
                if (!wishlistIds.map(Number).includes(pid)) {
                    wishlistIds.push(pid);
                }
                btn.classList.add('active');
                btn.style.transform = 'scale(1.25)';
                setTimeout(() => { btn.style.transform = ''; }, 200);
            } else {
                wishlistIds = wishlistIds.filter(id => Number(id) !== pid);
                btn.classList.remove('active');
            }
            updateWishlistBadge();
            try {
                localStorage.setItem('sweetdreams_wishlist_sync', Date.now().toString());
            } catch(err) {}
        })
        .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
    };

    // Re-sync wishlist state (e.g. back from product detail or tab switch)
    function syncWishlistFromServer() {
        if (!isLoggedIn) return;
        fetch('/api/wishlist/ids', {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => {
            if (res.ok) return res.json();
        })
        .then(data => {
            if (data && Array.isArray(data.ids)) {
                wishlistIds = data.ids.map(Number);
                updateWishlistBadge();
                document.querySelectorAll('.card-wishlist-btn').forEach(b => {
                    const id = Number(b.getAttribute('data-product-id'));
                    if (wishlistIds.includes(id)) {
                        b.classList.add('active');
                    } else {
                        b.classList.remove('active');
                    }
                });
            }
        })
        .catch(() => {});
    }

    window.addEventListener('pageshow', function() {
        syncWishlistFromServer();
    });

    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            syncWishlistFromServer();
        }
    });

    window.addEventListener('storage', function(e) {
        if (e.key === 'sweetdreams_wishlist_sync') {
            syncWishlistFromServer();
        }
    });

    // URL parameter updater
    function updateUrlParams() {
        const params = new URLSearchParams();
        if (state.search) params.set('search', state.search);
        if (state.categories.length > 0) params.set('category', state.categories.join(','));
        if (state.sizes.length > 0) params.set('sizes', state.sizes.join(','));
        if (state.colors.length > 0) params.set('colors', state.colors.join(','));
        if (state.minPrice > 0) params.set('min_price', state.minPrice);
        if (state.maxPrice < 1000000) params.set('max_price', state.maxPrice);
        if (state.sort !== 'newest') params.set('sort', state.sort);

        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.replaceState({}, '', newUrl);
    }

    // 10. MOBILE DRAWER FILTER
    function openFilter() {
        filterSidebar.classList.add('open');
        filterOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeFilter() {
        filterSidebar.classList.remove('open');
        filterOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }

    if (mobileFilterToggle) mobileFilterToggle.addEventListener('click', openFilter);
    if (filterCloseBtn) filterCloseBtn.addEventListener('click', closeFilter);
    if (filterOverlay) filterOverlay.addEventListener('click', closeFilter);

    // Initial render & wishlist count
    updateWishlistBadge();
    renderProducts();
    lucide.createIcons();
});
</script>
@endsection
