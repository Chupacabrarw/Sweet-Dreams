@extends('layouts.app')

@section('title', 'Katalog Produk - Sweet Dreams')

@section('content')
<style>
    /* ===== KATALOG PAGE ===== */
    .katalog-header {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.25rem 2rem 1rem;
    }
    .katalog-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.35rem 0;
        letter-spacing: -0.01em;
    }
    .katalog-header p {
        font-size: 0.95rem;
        color: #8a6a72;
        margin: 0;
    }

    /* ===== KATALOG LAYOUT ===== */
    .katalog-layout {
        max-width: 1280px;
        margin: 0 auto;
        padding: 1rem 2rem 4rem;
        display: grid;
        grid-template-columns: 270px 1fr;
        gap: 2rem;
        align-items: start;
    }

    /* ===== FILTER SIDEBAR ===== */
    .filter-sidebar {
        background: #fff;
        border: 1.5px solid #fbd5df;
        border-radius: 18px;
        padding: 1.75rem;
        position: sticky;
        top: 88px;
        box-shadow: 0 8px 24px rgba(212, 77, 110, 0.04);
        transition: all 0.3s ease;
    }
    .filter-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.25rem;
    }
    .filter-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .filter-quick-clear {
        background: none;
        border: none;
        color: #d44d6e;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        padding: 0;
        text-decoration: underline;
        font-family: 'Inter', sans-serif;
    }
    .filter-quick-clear:hover {
        color: #a82e4e;
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
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #fae4ea;
    }
    .filter-group:last-of-type {
        border-bottom: none;
        margin-bottom: 1rem;
        padding-bottom: 0;
    }
    .filter-group-label {
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.85rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .filter-group-label .filter-badge-count {
        font-size: 0.72rem;
        color: #d44d6e;
        background: #fef5f7;
        border: 1px solid #fbd5df;
        padding: 1px 7px;
        border-radius: 50px;
        font-weight: 600;
    }

    /* Checkbox Filter */
    .filter-checkbox {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-bottom: 0.65rem;
        cursor: pointer;
        font-size: 0.86rem;
        color: #5a3a42;
        user-select: none;
        transition: color 0.2s ease;
    }
    .filter-checkbox:hover {
        color: #d44d6e;
    }
    .filter-checkbox input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 19px;
        height: 19px;
        border: 2px solid #d4b8c0;
        border-radius: 5px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
        background: #fff;
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
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 1.5px solid #d4b8c0;
        background: #fff;
        color: #5a3a42;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-family: 'Inter', sans-serif;
    }
    .size-btn:hover {
        border-color: #d44d6e;
        color: #d44d6e;
        transform: translateY(-2px);
    }
    .size-btn.active {
        background: #d44d6e;
        border-color: #d44d6e;
        color: #fff;
        box-shadow: 0 4px 10px rgba(212, 77, 110, 0.3);
        transform: scale(1.05);
    }

    /* Color Filter */
    .color-options {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    .color-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid transparent;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        padding: 0;
        outline: none;
        position: relative;
    }
    .color-btn:hover {
        transform: scale(1.15);
    }
    .color-btn.active {
        border-color: #d44d6e;
        box-shadow: 0 0 0 3px rgba(212,77,110,0.3);
        transform: scale(1.1);
    }
    .color-btn.active::after {
        content: '';
        position: absolute;
        inset: 4px;
        border-radius: 50%;
        border: 1.5px solid #fff;
        pointer-events: none;
    }

    /* Price Range Filter */
    .price-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }
    .price-input-group label {
        font-size: 0.72rem;
        color: #8a6a72;
        display: block;
        margin-bottom: 0.3rem;
        font-weight: 500;
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
        font-weight: 600;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .price-input-group input:focus {
        border-color: #d44d6e;
        box-shadow: 0 0 0 3px rgba(212, 77, 110, 0.15);
    }

    /* Range Slider */
    .price-slider {
        position: relative;
        height: 6px;
        background: #fbd5df;
        border-radius: 3px;
        margin: 0.75rem 0 0.5rem;
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
        left: 0;
        margin: 0;
    }
    .price-slider input[type="range"]::-webkit-slider-thumb {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #d44d6e;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(212,77,110,0.35);
        cursor: pointer;
        pointer-events: all;
        transition: transform 0.15s ease;
    }
    .price-slider input[type="range"]::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }
    .price-slider input[type="range"]::-moz-range-thumb {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #d44d6e;
        border: 2px solid #fff;
        box-shadow: 0 2px 6px rgba(212,77,110,0.35);
        cursor: pointer;
        pointer-events: all;
        transition: transform 0.15s ease;
    }
    .price-range-label {
        font-size: 0.78rem;
        color: #8a6a72;
        margin-top: 0.4rem;
        font-weight: 500;
        text-align: center;
    }

    /* Reset Button */
    .btn-reset-filter {
        width: 100%;
        padding: 10px 16px;
        border: 1.5px solid #d44d6e;
        border-radius: 50px;
        background: transparent;
        color: #d44d6e;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: all 0.25s ease;
        margin-top: 0.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-reset-filter:hover {
        background: #d44d6e;
        color: #fff;
        box-shadow: 0 4px 14px rgba(212, 77, 110, 0.25);
    }

    /* ===== PRODUCT CONTENT AREA ===== */
    .products-content {
        min-width: 0;
    }
    .products-content-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .products-content-header .category-title h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .products-content-header .category-title p {
        font-size: 0.85rem;
        color: #8a6a72;
        margin: 0.2rem 0 0 0;
    }
    .sort-dropdown {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }
    .sort-dropdown label {
        font-size: 0.84rem;
        color: #8a6a72;
        white-space: nowrap;
        font-weight: 500;
    }
    .sort-dropdown select {
        appearance: none;
        -webkit-appearance: none;
        background: #fff;
        border: 1.5px solid #d4b8c0;
        border-radius: 10px;
        padding: 9px 36px 9px 14px;
        font-size: 0.86rem;
        font-weight: 500;
        color: #3a2a2e;
        font-family: 'Inter', sans-serif;
        cursor: pointer;
        outline: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%233a2a2e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }
    .sort-dropdown select:focus {
        border-color: #d44d6e;
        box-shadow: 0 0 0 3px rgba(212, 77, 110, 0.12);
    }

    /* ===== ACTIVE FILTER CHIPS BAR ===== */
    .active-filter-chips {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
    }
    .active-filter-chips:empty {
        display: none;
    }
    .filter-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: #fef5f7;
        border: 1px solid #fbd5df;
        color: #d44d6e;
        padding: 5px 11px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .filter-chip:hover {
        background: #fde8ee;
        border-color: #f7a8be;
    }
    .filter-chip-btn {
        background: none;
        border: none;
        cursor: pointer;
        color: #d44d6e;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin: 0;
        font-size: 0.95rem;
        line-height: 1;
        transition: transform 0.15s ease;
    }
    .filter-chip-btn:hover {
        transform: scale(1.25);
    }
    .clear-all-chips {
        background: none;
        border: none;
        color: #8a6a72;
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        text-decoration: underline;
        padding: 4px 6px;
        font-family: 'Inter', sans-serif;
    }
    .clear-all-chips:hover {
        color: #d44d6e;
    }

    /* ===== PRODUCT GRID (CATALOG) ===== */
    .katalog-products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        transition: opacity 0.2s ease;
    }

    /* Catalog Product Card */
    .katalog-product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1.5px solid #fbd5df;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.35s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.35s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        position: relative;
        cursor: pointer;
    }
    .katalog-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 14px 34px rgba(212,77,110,0.12);
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
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .katalog-product-card:hover .katalog-product-card-img img {
        transform: scale(1.06);
    }

    /* Wishlist Button */
    .card-wishlist-btn {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(251, 213, 223, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #8a6a72;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
    }
    .card-wishlist-btn:hover {
        transform: scale(1.12);
        color: #d44d6e;
    }
    .card-wishlist-btn.active {
        background: #d44d6e;
        color: #fff;
        border-color: #d44d6e;
        box-shadow: 0 3px 10px rgba(212, 77, 110, 0.35);
    }

    /* Discount Badge */
    .card-discount-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #d44d6e;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 6px;
        z-index: 2;
        letter-spacing: 0.02em;
        box-shadow: 0 2px 8px rgba(212, 77, 110, 0.3);
    }

    /* Tags row */
    .product-tags {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1rem 0;
        gap: 0.5rem;
    }
    .product-tag-badge {
        display: inline-block;
        background: #3a2a2e;
        color: #fff;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 50px;
        letter-spacing: 0.02em;
        white-space: nowrap;
    }
    .product-tag-size {
        font-size: 0.75rem;
        color: #8a6a72;
        white-space: nowrap;
    }

    .katalog-product-card-body {
        padding: 0.6rem 1rem 1.1rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .product-rating {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.75rem;
        color: #8a6a72;
        margin-bottom: 0.35rem;
    }
    .product-rating svg {
        fill: #f59e0b;
        color: #f59e0b;
    }
    .product-rating-score {
        font-weight: 700;
        color: #3a2a2e;
    }
    .katalog-product-card-body h3 {
        font-family: 'Playfair Display', serif;
        font-size: 0.98rem;
        font-weight: 600;
        margin: 0 0 0.35rem 0;
        color: #3a2a2e;
        line-height: 1.35;
    }
    .katalog-product-card-body .product-desc {
        font-size: 0.78rem;
        color: #8a6a72;
        line-height: 1.5;
        margin: 0 0 0.85rem 0;
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
        gap: 0.5rem;
        margin-top: auto;
    }
    .price-wrapper {
        display: flex;
        flex-direction: column;
    }
    .katalog-product-card-footer .price {
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .katalog-product-card-footer .original-price {
        font-size: 0.75rem;
        color: #b48a92;
        text-decoration: line-through;
        font-weight: 400;
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
        transition: all 0.25s ease;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }
    .btn-detail:hover {
        background: #b83a58;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(212,77,110,0.25);
    }

    /* ===== EMPTY STATE ===== */
    .catalog-empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4.5rem 2rem;
        background: #fff;
        border: 1.5px dashed #fbd5df;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .catalog-empty-icon {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: #fef5f7;
        color: #d44d6e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
        border: 1px solid #fbd5df;
    }
    .catalog-empty-state h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.45rem;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
        font-weight: 700;
    }
    .catalog-empty-state p {
        font-size: 0.9rem;
        color: #8a6a72;
        max-width: 420px;
        margin: 0 0 1.5rem 0;
        line-height: 1.55;
    }
    .catalog-empty-state .btn-reset-filter {
        width: auto;
        padding: 10px 24px;
        margin-top: 0;
    }

    /* ===== MOBILE FILTER TOGGLE ===== */
    .mobile-filter-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 11px;
        border: 1.5px solid #d44d6e;
        border-radius: 50px;
        background: #fff;
        color: #d44d6e;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(212,77,110,0.08);
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
            width: 320px;
            max-width: 85vw;
            height: 100vh;
            z-index: 200;
            border-radius: 0 20px 20px 0;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 6px 0 28px rgba(0,0,0,0.18);
        }
        .filter-sidebar.open {
            transform: translateX(0);
        }
        .filter-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 199;
            background: rgba(58,42,46,0.45);
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
            font-size: 1.6rem;
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
        border-radius: 50%;
        transition: background 0.2s;
    }
    .filter-sidebar-close button:hover {
        background: #fef5f7;
        color: #d44d6e;
    }
</style>

{{-- PAGE HEADER --}}
<div class="katalog-header" id="katalog-header">
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

        {{-- Kategori --}}
        <div class="filter-group" id="filter-kategori">
            <p class="filter-group-label">
                <span>Kategori</span>
                <span class="filter-badge-count" id="cat-badge-count" style="display:none;">0</span>
            </p>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="baju-tidur" {{ $activeCategory === 'baju-tidur' ? 'checked' : '' }}>
                <span>Baju Tidur</span>
            </label>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="lingerie" {{ $activeCategory === 'lingerie' ? 'checked' : '' }}>
                <span>Lingerie</span>
            </label>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="kimono" {{ $activeCategory === 'kimono' ? 'checked' : '' }}>
                <span>Kimono</span>
            </label>
            <label class="filter-checkbox">
                <input type="checkbox" name="kategori" value="pakaian-dalam" {{ $activeCategory === 'pakaian-dalam' ? 'checked' : '' }}>
                <span>Pakaian Dalam</span>
            </label>
        </div>

        {{-- Ukuran --}}
        <div class="filter-group" id="filter-ukuran">
            <p class="filter-group-label">
                <span>Ukuran</span>
                <span class="filter-badge-count" id="size-badge-count" style="display:none;">0</span>
            </p>
            <div class="size-options" id="size-options-container">
                <button type="button" class="size-btn" data-size="S">S</button>
                <button type="button" class="size-btn" data-size="M">M</button>
                <button type="button" class="size-btn" data-size="L">L</button>
                <button type="button" class="size-btn" data-size="XL">XL</button>
                <button type="button" class="size-btn" data-size="XXL">XXL</button>
            </div>
        </div>

        {{-- Warna --}}
        <div class="filter-group" id="filter-warna">
            <p class="filter-group-label">
                <span>Warna</span>
                <span class="filter-badge-count" id="color-badge-count" style="display:none;">0</span>
            </p>
            <div class="color-options" id="color-options-container">
                <button type="button" class="color-btn" data-color="pink" data-color-name="Pink" style="background: #e8a0b0;" title="Pink" aria-label="Pink"></button>
                <button type="button" class="color-btn" data-color="gold" data-color-name="Gold" style="background: #d4a854;" title="Gold" aria-label="Gold"></button>
                <button type="button" class="color-btn" data-color="white" data-color-name="White" style="background: #f5f0ec; border: 1.5px solid #d4b8c0;" title="White" aria-label="White"></button>
                <button type="button" class="color-btn" data-color="cream" data-color-name="Cream" style="background: #eedfc8;" title="Cream" aria-label="Cream"></button>
                <button type="button" class="color-btn" data-color="grey" data-color-name="Grey" style="background: #6a6a7a;" title="Grey" aria-label="Grey"></button>
            </div>
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
                        <button type="button" class="card-wishlist-btn" data-product-id="{{ $prod['id'] }}" aria-label="Tambah ke Wishlist" onclick="event.stopPropagation();">
                            <i data-lucide="heart" style="width:18px;height:18px;"></i>
                        </button>
                    </div>
                    <div class="product-tags">
                        <span class="product-tag-badge">{{ $prod['badge'] ?? $prod['category_name'] }}</span>
                        <span class="product-tag-size">Ukuran {{ implode(', ', $prod['sizes']) }}</span>
                    </div>
                    <div class="katalog-product-card-body">
                        <div class="product-rating">
                            <i data-lucide="star" style="width:14px;height:14px;"></i>
                            <span class="product-rating-score">{{ $prod['rating'] }}</span>
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
        search: ''
    };

    // Wishlist stored in localStorage
    function getWishlist() {
        try {
            return JSON.parse(localStorage.getItem('sweet_dreams_wishlist')) || [];
        } catch (e) {
            return [];
        }
    }
    function setWishlist(list) {
        localStorage.setItem('sweet_dreams_wishlist', JSON.stringify(list));
        updateWishlistBadge();
    }
    function updateWishlistBadge() {
        const list = getWishlist();
        const badge = document.querySelector('#btn-wishlist .badge');
        if (badge) {
            badge.textContent = list.length;
            badge.style.display = list.length > 0 ? 'flex' : 'none';
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

            // Size check
            if (state.sizes.length > 0) {
                const hasSize = item.sizes && item.sizes.some(s => state.sizes.includes(s));
                if (!hasSize) return false;
            }

            // Color check
            if (state.colors.length > 0) {
                const hasColor = item.colors && item.colors.some(c => state.colors.includes(c));
                if (!hasColor) return false;
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
            const catMap = {
                'baju-tidur': 'Baju Tidur',
                'lingerie': 'Lingerie',
                'kimono': 'Kimono',
                'pakaian-dalam': 'Pakaian Dalam'
            };
            activeCategoryTitle.textContent = catMap[state.categories[0]] || 'Katalog';
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
            return;
        }

        emptyState.style.display = 'none';
        productsGrid.style.display = 'grid';

        // Build HTML
        const html = sorted.map((prod, index) => {
            const isWishlisted = wishlist.includes(prod.id);
            const discountBadge = prod.discount 
                ? `<span class="card-discount-badge">${prod.discount}</span>` 
                : '';
            const origPrice = prod.original_price 
                ? `<span class="original-price">${prod.original_price}</span>` 
                : '';
            const sizeList = prod.sizes ? prod.sizes.join(', ') : 'S, M, L';
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
                                onclick="event.stopPropagation(); toggleWishlist(${prod.id}, this);">
                            <i data-lucide="heart" style="width:18px;height:18px;"></i>
                        </button>
                    </div>
                    <div class="product-tags">
                        <span class="product-tag-badge">${badgeLabel}</span>
                        <span class="product-tag-size">Ukuran ${sizeList}</span>
                    </div>
                    <div class="katalog-product-card-body">
                        <div class="product-rating">
                            <i data-lucide="star" style="width:14px;height:14px;"></i>
                            <span class="product-rating-score">${prod.rating}</span>
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
        const catMap = {
            'baju-tidur': 'Baju Tidur',
            'lingerie': 'Lingerie',
            'kimono': 'Kimono',
            'pakaian-dalam': 'Pakaian Dalam'
        };
        state.categories.forEach(cat => {
            chips.push(`
                <span class="filter-chip">
                    ${catMap[cat] || cat}
                    <button type="button" class="filter-chip-btn" onclick="removeCategoryFilter('${cat}')" aria-label="Hapus kategori ${cat}">&times;</button>
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
    window.toggleWishlist = function(productId, btn) {
        let wishlist = getWishlist();
        const index = wishlist.indexOf(productId);
        if (index > -1) {
            wishlist.splice(index, 1);
            btn.classList.remove('active');
        } else {
            wishlist.push(productId);
            btn.classList.add('active');
            // Little bounce animation
            btn.style.transform = 'scale(1.3)';
            setTimeout(() => { btn.style.transform = ''; }, 200);
        }
        setWishlist(wishlist);
    };

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
