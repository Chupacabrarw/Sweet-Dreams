@extends('layouts.app')

@section('title', 'Katalog Produk - Sweet Dreams')

@section('content')
<style>
    /* ===== KATALOG PAGE ===== */
    .katalog-header {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2rem 2rem 1rem;
    }
    .katalog-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.35rem 0;
    }
    .katalog-header p {
        font-size: 0.9rem;
        color: #8a6a72;
        margin: 0;
    }

    /* ===== KATALOG LAYOUT ===== */
    .katalog-layout {
        max-width: 1280px;
        margin: 0 auto;
        padding: 1rem 2rem 3rem;
        display: grid;
        grid-template-columns: 260px 1fr;
        gap: 2rem;
        align-items: start;
    }

    /* ===== FILTER SIDEBAR ===== */
    .filter-sidebar {
        background: #fff;
        border: 1.5px solid #fbd5df;
        border-radius: 16px;
        padding: 1.75rem;
        position: sticky;
        top: 88px;
    }
    .filter-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.25rem 0;
    }
    .filter-subtitle {
        font-size: 0.8rem;
        color: #b48a92;
        margin: 0 0 1.5rem 0;
        line-height: 1.5;
    }

    /* Filter Group */
    .filter-group {
        margin-bottom: 1.5rem;
    }
    .filter-group-label {
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.75rem 0;
    }

    /* Checkbox Filter */
    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        font-size: 0.85rem;
        color: #5a3a42;
        user-select: none;
    }
    .filter-checkbox input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #d4b8c0;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .filter-checkbox input[type="checkbox"]:checked {
        background: #d44d6e;
        border-color: #d44d6e;
    }
    .filter-checkbox input[type="checkbox"]:checked::after {
        content: '';
        display: block;
        width: 5px;
        height: 9px;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
        margin-top: -1px;
    }
    .filter-checkbox:hover input[type="checkbox"] {
        border-color: #d44d6e;
    }

    /* Size Filter */
    .size-options {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }
    .size-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid #d4b8c0;
        background: #fff;
        color: #5a3a42;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-family: 'Inter', sans-serif;
    }
    .size-btn:hover {
        border-color: #d44d6e;
        color: #d44d6e;
    }
    .size-btn.active {
        background: #d44d6e;
        border-color: #d44d6e;
        color: #fff;
    }

    /* Color Filter */
    .color-options {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }
    .color-btn {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2.5px solid transparent;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0;
        outline: none;
    }
    .color-btn:hover,
    .color-btn.active {
        border-color: #d44d6e;
        box-shadow: 0 0 0 2px rgba(212,77,110,0.2);
    }

    /* Price Range Filter */
    .price-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .price-input-group label {
        font-size: 0.72rem;
        color: #8a6a72;
        display: block;
        margin-bottom: 0.25rem;
    }
    .price-input-group input {
        width: 100%;
        border: 1.5px solid #d4b8c0;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 0.82rem;
        color: #3a2a2e;
        background: #fef5f7;
        outline: none;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.2s ease;
    }
    .price-input-group input:focus {
        border-color: #d44d6e;
    }

    /* Range Slider */
    .price-slider {
        position: relative;
        height: 6px;
        background: #fbd5df;
        border-radius: 3px;
        margin: 0.5rem 0;
    }
    .price-slider-track {
        position: absolute;
        height: 100%;
        background: linear-gradient(90deg, #d44d6e, #f48da8);
        border-radius: 3px;
        left: 0%;
        right: 0%;
    }
    .price-slider input[type="range"] {
        position: absolute;
        width: 100%;
        height: 6px;
        appearance: none;
        -webkit-appearance: none;
        background: transparent;
        pointer-events: none;
        top: -4px;
    }
    .price-slider input[type="range"]::-webkit-slider-thumb {
        appearance: none;
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #d44d6e;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(212,77,110,0.3);
        cursor: pointer;
        pointer-events: all;
    }
    .price-slider input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #d44d6e;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(212,77,110,0.3);
        cursor: pointer;
        pointer-events: all;
    }
    .price-range-label {
        font-size: 0.78rem;
        color: #8a6a72;
        margin-top: 0.25rem;
    }

    /* Reset Button */
    .btn-reset-filter {
        width: 100%;
        padding: 10px;
        border: 1.5px solid #d44d6e;
        border-radius: 50px;
        background: transparent;
        color: #d44d6e;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        margin-top: 0.5rem;
    }
    .btn-reset-filter:hover {
        background: #d44d6e;
        color: #fff;
    }

    /* ===== PRODUCT CONTENT AREA ===== */
    .products-content-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .products-content-header .category-title h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .products-content-header .category-title p {
        font-size: 0.82rem;
        color: #8a6a72;
        margin: 0.15rem 0 0 0;
    }
    .sort-dropdown {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .sort-dropdown label {
        font-size: 0.82rem;
        color: #8a6a72;
        white-space: nowrap;
    }
    .sort-dropdown select {
        appearance: none;
        -webkit-appearance: none;
        background: #fff;
        border: 1.5px solid #d4b8c0;
        border-radius: 8px;
        padding: 8px 36px 8px 12px;
        font-size: 0.85rem;
        color: #3a2a2e;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%233a2a2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        transition: border-color 0.2s ease;
    }
    .sort-dropdown select:focus {
        border-color: #d44d6e;
    }

    /* ===== PRODUCT GRID (CATALOG) ===== */
    .katalog-products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    /* Catalog Product Card */
    .katalog-product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1.5px solid #fbd5df;
        transition: all 0.4s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }
    .katalog-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(212,77,110,0.10);
        border-color: #f48da8;
    }
    .katalog-product-card-img {
        width: 100%;
        aspect-ratio: 3/4;
        overflow: hidden;
        background: #fef5f7;
        position: relative;
    }
    .katalog-product-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .katalog-product-card:hover .katalog-product-card-img img {
        transform: scale(1.05);
    }

    /* Tags row */
    .product-tags {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem 0;
    }
    .product-tag-badge {
        display: inline-block;
        background: #3a2a2e;
        color: #fff;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 50px;
        letter-spacing: 0.02em;
    }
    .product-tag-size {
        font-size: 0.75rem;
        color: #8a6a72;
    }

    .katalog-product-card-body {
        padding: 0.5rem 1rem 1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .katalog-product-card-body h3 {
        font-family: 'Playfair Display', serif;
        font-size: 0.95rem;
        font-weight: 600;
        margin: 0 0 0.3rem 0;
        color: #3a2a2e;
        line-height: 1.3;
    }
    .katalog-product-card-body .product-desc {
        font-size: 0.78rem;
        color: #8a6a72;
        line-height: 1.5;
        margin: 0 0 0.75rem 0;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .katalog-product-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .katalog-product-card-footer .price {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: #d44d6e;
        color: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }
    .btn-detail:hover {
        background: #b83a58;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(212,77,110,0.25);
    }

    /* ===== MOBILE FILTER TOGGLE ===== */
    .mobile-filter-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 10px;
        border: 1.5px solid #d44d6e;
        border-radius: 50px;
        background: #fff;
        color: #d44d6e;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        margin-bottom: 1rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .katalog-products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 768px) {
        .katalog-layout {
            grid-template-columns: 1fr;
            padding: 1rem;
        }
        .katalog-header {
            padding: 1.5rem 1rem 0.5rem;
        }
        .filter-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 300px;
            height: 100vh;
            z-index: 200;
            border-radius: 0 16px 16px 0;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 4px 0 24px rgba(0,0,0,0.15);
        }
        .filter-sidebar.open {
            transform: translateX(0);
        }
        .filter-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 199;
            background: rgba(58,42,46,0.4);
            backdrop-filter: blur(4px);
        }
        .filter-overlay.open {
            display: block;
        }
        .mobile-filter-toggle {
            display: flex;
        }
        .filter-sidebar-close {
            display: flex !important;
        }
        .products-content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .katalog-products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
    @media (max-width: 480px) {
        .katalog-products-grid {
            grid-template-columns: 1fr;
        }
        .katalog-header h1 {
            font-size: 1.5rem;
        }
    }

    /* Filter close btn (mobile only) */
    .filter-sidebar-close {
        display: none;
        align-items: center;
        justify-content: flex-end;
        margin-bottom: 0.5rem;
    }
    .filter-sidebar-close button {
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        cursor: pointer;
        color: #3a2a2e;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

{{-- PAGE HEADER --}}
<div class="katalog-header" id="katalog-header">
    <h1>Katalog Produk</h1>
    <p>Temukan baju tidur yang nyaman untuk setiap malam</p>
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

        <h2 class="filter-title">Filter</h2>
        <p class="filter-subtitle">Sesuaikan pilihanmu untuk menemukan produk yang tepat</p>

        {{-- Kategori --}}
        <div class="filter-group" id="filter-kategori">
            <p class="filter-group-label">Kategori</p>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="baju-tidur" checked>
                Baju Tidur
            </label>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="lingerie">
                Lingerie
            </label>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="kimono">
                Kimono
            </label>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="pakaian-dalam">
                Pakaian Dalam
            </label>
        </div>

        {{-- Ukuran --}}
        <div class="filter-group" id="filter-ukuran">
            <p class="filter-group-label">Ukuran</p>
            <div class="size-options">
                <button class="size-btn active" data-size="S">S</button>
                <button class="size-btn" data-size="M">M</button>
                <button class="size-btn" data-size="L">L</button>
                <button class="size-btn" data-size="XL">XL</button>
                <button class="size-btn" data-size="XXL">XXL</button>
            </div>
        </div>

        {{-- Warna --}}
        <div class="filter-group" id="filter-warna">
            <p class="filter-group-label">Warna</p>
            <div class="color-options">
                <button class="color-btn active" data-color="pink" style="background: #e8a0b0;" aria-label="Pink"></button>
                <button class="color-btn" data-color="gold" style="background: #d4a854;" aria-label="Gold"></button>
                <button class="color-btn" data-color="white" style="background: #f5f0ec; border: 1.5px solid #d4b8c0;" aria-label="White"></button>
                <button class="color-btn" data-color="cream" style="background: #e8d8c8;" aria-label="Cream"></button>
                <button class="color-btn" data-color="grey" style="background: #6a6a7a;" aria-label="Grey"></button>
            </div>
        </div>

        {{-- Rentang Harga --}}
        <div class="filter-group" id="filter-harga">
            <p class="filter-group-label">Rentang Harga</p>
            <div class="price-inputs">
                <div class="price-input-group">
                    <label>Min</label>
                    <input type="text" id="price-min-input" value="Rp 200.000">
                </div>
                <div class="price-input-group">
                    <label>Max</label>
                    <input type="text" id="price-max-input" value="Rp 600.000">
                </div>
            </div>
            <div class="price-slider" id="price-slider">
                <div class="price-slider-track" id="price-slider-track"></div>
                <input type="range" id="price-range-min" min="0" max="1000000" value="200000" step="10000">
                <input type="range" id="price-range-max" min="0" max="1000000" value="600000" step="10000">
            </div>
            <p class="price-range-label" id="price-range-label">Rp 200.000 - Rp 600.000</p>
        </div>

        {{-- Reset --}}
        <button class="btn-reset-filter" id="btn-reset-filter">Reset Filter</button>
    </aside>

    {{-- PRODUCTS CONTENT --}}
    <div class="products-content" id="products-content">
        {{-- Mobile Filter Button --}}
        <button class="mobile-filter-toggle" id="mobile-filter-toggle">
            <i data-lucide="sliders-horizontal" style="width:18px;height:18px;"></i>
            Filter Produk
        </button>

        <div class="products-content-header">
            <div class="category-title">
                <h2 id="active-category-title">Baju Tidur</h2>
                <p id="product-count">24 produk ditemukan</p>
            </div>
            <div class="sort-dropdown">
                <label for="sort-select">Urutkan:</label>
                <select id="sort-select">
                    <option value="newest">Terbaru</option>
                    <option value="price-low">Harga Terendah</option>
                    <option value="price-high">Harga Tertinggi</option>
                    <option value="popular">Terpopuler</option>
                </select>
            </div>
        </div>

        <div class="katalog-products-grid" id="katalog-products-grid">
            {{-- Product 1 --}}
            <div class="katalog-product-card reveal reveal-delay-1" id="katalog-product-1" onclick="window.location.href='/produk/baju-tidur-modal-soft-peach'" style="cursor: pointer;">
                <div class="katalog-product-card-img">
                    <img src="{{ asset('images/katalog-product-1.jpg') }}" alt="Baju Tidur Modal Soft Peach" loading="lazy">
                </div>
                <div class="product-tags">
                    <span class="product-tag-badge">Baju Tidur</span>
                    <span class="product-tag-size">Ukuran S - XL</span>
                </div>
                <div class="katalog-product-card-body">
                    <h3><a href="/produk/baju-tidur-modal-soft-peach" style="color:inherit;text-decoration:none;">Baju Tidur Modal Soft Peach</a></h3>
                    <p class="product-desc">Bahan modal lembut, potongan longgar, dan warna pastel yang menenangkan.</p>
                    <div class="katalog-product-card-footer">
                        <span class="price">Rp 280.000</span>
                        <a href="/produk/baju-tidur-modal-soft-peach" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                    </div>
                </div>
            </div>

            {{-- Product 2 --}}
            <div class="katalog-product-card reveal reveal-delay-2" id="katalog-product-2" onclick="window.location.href='/produk/sailor-rabbit-set'" style="cursor: pointer;">
                <div class="katalog-product-card-img">
                    <img src="{{ asset('images/katalog-product-2.jpg') }}" alt="Baju Tidur Cotton Floral" loading="lazy">
                </div>
                <div class="product-tags">
                    <span class="product-tag-badge">Baju Tidur</span>
                    <span class="product-tag-size">Ukuran S - XL</span>
                </div>
                <div class="katalog-product-card-body">
                    <h3><a href="/produk/sailor-rabbit-set" style="color:inherit;text-decoration:none;">Baju Tidur Cotton Floral</a></h3>
                    <p class="product-desc">Motif bunga kecil yang elegan dengan kancing depan dan kerah yang nyaman.</p>
                    <div class="katalog-product-card-footer">
                        <span class="price">Rp 310.000</span>
                        <a href="/produk/sailor-rabbit-set" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                    </div>
                </div>
            </div>

            {{-- Product 3 --}}
            <div class="katalog-product-card reveal reveal-delay-3" id="katalog-product-3" onclick="window.location.href='/produk/kimono-silk-premium'" style="cursor: pointer;">
                <div class="katalog-product-card-img">
                    <img src="{{ asset('images/katalog-product-3.jpg') }}" alt="Kimono Silk Premium" loading="lazy">
                </div>
                <div class="product-tags">
                    <span class="product-tag-badge">Kimono</span>
                    <span class="product-tag-size">Ukuran S - XL</span>
                </div>
                <div class="katalog-product-card-body">
                    <h3><a href="/produk/kimono-silk-premium" style="color:inherit;text-decoration:none;">Kimono Silk Premium</a></h3>
                    <p class="product-desc">Kimono ringan dengan bahan silk yang lembut dan tampilan elegan untuk santai.</p>
                    <div class="katalog-product-card-footer">
                        <span class="price">Rp 450.000</span>
                        <a href="/produk/kimono-silk-premium" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                    </div>
                </div>
            </div>

            {{-- Product 4 --}}
            <div class="katalog-product-card reveal reveal-delay-1" id="katalog-product-4" onclick="window.location.href='/produk/sailor-rabbit-set'" style="cursor: pointer;">
                <div class="katalog-product-card-img">
                    <img src="{{ asset('images/katalog-product-4.jpg') }}" alt="Lingerie Set Lace" loading="lazy">
                </div>
                <div class="product-tags">
                    <span class="product-tag-badge">Lingerie</span>
                    <span class="product-tag-size">Ukuran S - XL</span>
                </div>
                <div class="katalog-product-card-body">
                    <h3><a href="/produk/sailor-rabbit-set" style="color:inherit;text-decoration:none;">Lingerie Set Lace</a></h3>
                    <p class="product-desc">Set lingerie dengan detail lace yang cantik dan tampilan yang feminin.</p>
                    <div class="katalog-product-card-footer">
                        <span class="price">Rp 320.000</span>
                        <a href="/produk/sailor-rabbit-set" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                    </div>
                </div>
            </div>

            {{-- Product 5 --}}
            <div class="katalog-product-card reveal reveal-delay-2" id="katalog-product-5" onclick="window.location.href='/produk/kimono-silk-premium'" style="cursor: pointer;">
                <div class="katalog-product-card-img">
                    <img src="{{ asset('images/katalog-product-5.jpg') }}" alt="Kimono Silk Premium" loading="lazy">
                </div>
                <div class="product-tags">
                    <span class="product-tag-badge">Baju Tidur</span>
                    <span class="product-tag-size">Ukuran S - XL</span>
                </div>
                <div class="katalog-product-card-body">
                    <h3><a href="/produk/kimono-silk-premium" style="color:inherit;text-decoration:none;">Kimono Silk Premium</a></h3>
                    <p class="product-desc">Kimono ringan dengan bahan silk yang lembut dan tampilan elegan untuk santai.</p>
                    <div class="katalog-product-card-footer">
                        <span class="price">Rp 450.000</span>
                        <a href="/produk/kimono-silk-premium" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                    </div>
                </div>
            </div>

            {{-- Product 6 --}}
            <div class="katalog-product-card reveal reveal-delay-3" id="katalog-product-6" onclick="window.location.href='/produk/sailor-rabbit-set'" style="cursor: pointer;">
                <div class="katalog-product-card-img">
                    <img src="{{ asset('images/sailor-rabbit-main.jpg') }}" alt="Sailor Rabbit Set" loading="lazy">
                </div>
                <div class="product-tags">
                    <span class="product-tag-badge">Baju Tidur</span>
                    <span class="product-tag-size">Ukuran S - XL</span>
                </div>
                <div class="katalog-product-card-body">
                    <h3><a href="/produk/sailor-rabbit-set" style="color:inherit;text-decoration:none;">Sailor Rabbit Set</a></h3>
                    <p class="product-desc">Bahan katun yang lembut dan adem, dilengkapi bordir kelinci manis untuk kenyamanan tidurmu.</p>
                    <div class="katalog-product-card-footer">
                        <span class="price">Rp 280.000</span>
                        <a href="/produk/sailor-rabbit-set" class="btn-detail" onclick="event.stopPropagation();">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide for this page
    lucide.createIcons();

    // ===== SIZE BUTTONS =====
    const sizeBtns = document.querySelectorAll('.size-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
        });
    });

    // ===== COLOR BUTTONS =====
    const colorBtns = document.querySelectorAll('.color-btn');
    colorBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
        });
    });

    // ===== PRICE RANGE SLIDER =====
    const rangeMin = document.getElementById('price-range-min');
    const rangeMax = document.getElementById('price-range-max');
    const priceMinInput = document.getElementById('price-min-input');
    const priceMaxInput = document.getElementById('price-max-input');
    const priceRangeLabel = document.getElementById('price-range-label');
    const sliderTrack = document.getElementById('price-slider-track');

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateSlider() {
        let minVal = parseInt(rangeMin.value);
        let maxVal = parseInt(rangeMax.value);

        if (minVal > maxVal) {
            [rangeMin.value, rangeMax.value] = [maxVal, minVal];
            minVal = parseInt(rangeMin.value);
            maxVal = parseInt(rangeMax.value);
        }

        const minPercent = (minVal / 1000000) * 100;
        const maxPercent = (maxVal / 1000000) * 100;
        sliderTrack.style.left = minPercent + '%';
        sliderTrack.style.right = (100 - maxPercent) + '%';

        priceMinInput.value = formatRupiah(minVal);
        priceMaxInput.value = formatRupiah(maxVal);
        priceRangeLabel.textContent = formatRupiah(minVal) + ' - ' + formatRupiah(maxVal);
    }

    rangeMin.addEventListener('input', updateSlider);
    rangeMax.addEventListener('input', updateSlider);
    updateSlider();

    // ===== RESET FILTER =====
    document.getElementById('btn-reset-filter').addEventListener('click', () => {
        document.querySelectorAll('.filter-checkbox input').forEach(cb => cb.checked = false);
        sizeBtns.forEach(btn => btn.classList.remove('active'));
        colorBtns.forEach(btn => btn.classList.remove('active'));
        rangeMin.value = 0;
        rangeMax.value = 1000000;
        updateSlider();
    });

    // ===== MOBILE FILTER =====
    const filterSidebar = document.getElementById('filter-sidebar');
    const filterOverlay = document.getElementById('filter-overlay');
    const mobileFilterToggle = document.getElementById('mobile-filter-toggle');
    const filterCloseBtn = document.getElementById('filter-close-btn');

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

    // Re-observe reveal elements for this page
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    revealElements.forEach(el => revealObserver.observe(el));
});
</script>
@endsection
