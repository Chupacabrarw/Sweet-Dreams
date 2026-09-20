@extends('layouts.app')

@section('title', $product['title'] . ' - Sweet Dreams')

@section('content')
<style>
    /* ===== BREADCRUMB ===== */
    .breadcrumb-nav {
        max-width: 1320px;
        margin: 0 auto;
        padding: 1.5rem 2rem 1rem;
    }
    .breadcrumb-container {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        color: var(--ink-muted);
    }
    .breadcrumb-container a {
        color: var(--ink-muted);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .breadcrumb-container a:hover {
        color: var(--blush);
    }
    .breadcrumb-container svg {
        width: 14px;
        height: 14px;
        color: rgba(180,140,150,0.35);
    }
    .breadcrumb-container span.active {
        color: var(--ink);
        font-weight: 600;
    }

    /* ===== PRODUCT MAIN SECTION ===== */
    .product-main-section {
        max-width: 1320px;
        margin: 0 auto;
        padding: 0.5rem 2rem 3rem;
    }
    .product-main-grid {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 3.5rem;
        align-items: start;
    }

    /* Gallery (Left) */
    .product-gallery-wrapper {
        display: flex;
        gap: 1.25rem;
        position: sticky;
        top: 88px;
    }
    .product-thumbnails-col {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        width: 84px;
        flex-shrink: 0;
    }
    .gallery-thumb-btn {
        width: 84px;
        height: 84px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #f2e2e6;
        background: #fff;
        cursor: pointer;
        padding: 0;
        transition: all 0.25s ease;
        position: relative;
    }
    .gallery-thumb-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }
    .gallery-thumb-btn:hover {
        border-color: var(--blush);
    }
    .gallery-thumb-btn:hover img {
        transform: scale(1.05);
    }
    .gallery-thumb-btn.active {
        border-color: var(--blush);
        box-shadow: 0 0 0 2px rgba(201,122,140, 0.2);
    }

    /* Main Product Image */
    .product-main-img-box {
        flex: 1;
        position: relative;
        border-radius: var(--radius-lg);
        overflow: hidden;
        background: #f7f6f5;
        border: 1px solid var(--border);
        aspect-ratio: 3/4;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .product-main-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center top;
        display: block;
        transition: opacity 0.3s ease, transform 0.4s ease;
    }
    .product-main-img-box:hover img {
        transform: scale(1.02);
    }

    /* Badges on main image */
    .badge-bestseller {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        background: #ffffff;
        color: var(--ink-muted);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 6px 14px;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        z-index: 2;
        border: 1px solid #f0e6e8;
    }
    .btn-wishlist-float {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--blush);
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        transition: all 0.25s ease;
        z-index: 2;
    }
    .btn-wishlist-float:hover {
        transform: scale(1.1);
        background: #fff5f7;
        box-shadow: 0 6px 18px rgba(201,122,140, 0.2);
    }
    .btn-wishlist-float.active {
        background: var(--blush);
        color: #fff;
        border-color: var(--blush);
    }

    /* Product Info (Right) */
    .product-info-wrapper {
        display: flex;
        flex-direction: column;
    }

    /* Rating & Collection */
    .product-meta-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .rating-stars {
        display: flex;
        align-items: center;
        gap: 2px;
        color: #eab308;
    }
    .rating-text {
        font-size: 0.85rem;
        color: var(--ink-muted);
        font-weight: 500;
    }
    .collection-tag {
        display: inline-block;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #b83b5e;
        background: #fce7ee;
        padding: 4px 10px;
        border-radius: 6px;
        margin-bottom: 0.6rem;
        width: fit-content;
    }

    /* Title & Price */
    .product-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.35rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1.2;
        margin: 0 0 0.75rem 0;
    }
    .product-price-box {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .product-current-price {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--blush);
    }
    .product-original-price {
        font-size: 1.05rem;
        color: var(--ink-faint);
        text-decoration: line-through;
    }

    /* Short Description */
    .product-short-desc {
        font-size: 0.92rem;
        line-height: 1.65;
        color: #6a4a52;
        margin: 0 0 1.75rem 0;
    }

    /* Divider */
    .product-section-divider {
        height: 1px;
        background: #f4dbe2;
        border: none;
        margin: 0 0 1.5rem 0;
    }

    /* Color Swatches */
    .option-group {
        margin-bottom: 1.5rem;
    }
    .option-label-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.65rem;
    }
    .option-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ink);
    }
    .option-value-name {
        font-weight: 400;
        color: var(--ink-muted);
    }
    .link-size-guide {
        font-size: 0.82rem;
        color: var(--blush);
        text-decoration: underline;
        cursor: pointer;
        transition: color 0.2s;
    }
    .link-size-guide:hover {
        color: var(--blush-dark);
    }

    .color-swatch-list {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .color-swatch-item {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        padding: 0;
    }
    .color-swatch-item::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }
    .color-swatch-item.active::after {
        border-color: var(--blush);
    }

    /* Size Buttons */
    .size-btn-list {
        display: flex;
        gap: 0.65rem;
    }
    .size-pill-btn {
        min-width: 48px;
        height: 44px;
        border-radius: 10px;
        border: 1.5px solid #e8d0d6;
        background: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ink);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 14px;
        transition: all 0.2s ease;
    }
    .size-pill-btn:hover {
        border-color: var(--blush);
        color: var(--blush);
    }
    .size-pill-btn.active {
        background: #e06b88;
        border-color: #e06b88;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(224, 107, 136, 0.35);
    }

    /* Action Row (Quantity + Add to Cart) */
    .product-action-row {
        display: flex;
        gap: 1rem;
        margin-top: 1.75rem;
        margin-bottom: 1.5rem;
    }
    .quantity-counter {
        display: flex;
        align-items: center;
        border: 1.5px solid #e8d0d6;
        border-radius: 8px;
        background: #fff;
        height: 52px;
        padding: 0 6px;
    }
    .qty-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: transparent;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ink-muted);
        font-size: 1.1rem;
        transition: all 0.2s ease;
    }
    .qty-btn:hover {
        background: #fce7ee;
        color: var(--blush);
    }
    .qty-display {
        min-width: 32px;
        text-align: center;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--ink);
        user-select: none;
    }
    .btn-add-to-cart {
        flex: 1;
        height: 52px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #e87b94 0%, var(--blush) 100%);
        color: #ffffff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.98rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.65rem;
        box-shadow: 0 6px 20px rgba(201,122,140, 0.35);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-add-to-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(201,122,140, 0.45);
        background: linear-gradient(135deg, var(--blush) 0%, #ba3253 100%);
    }
    .btn-add-to-cart:active {
        transform: translateY(0);
    }

    /* Trust & Guarantee Badges */
    .trust-badges-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f6e6ea;
    }
    .trust-badge-item {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.8rem;
        color: var(--ink-muted);
        font-weight: 500;
    }
    .trust-badge-item svg {
        width: 17px;
        height: 17px;
        color: #10b981;
        flex-shrink: 0;
    }

    /* ===== PANDUAN UKURAN (CM) ===== */
    .size-guide-section {
        max-width: 1320px;
        margin: 1.5rem auto 3.5rem;
        padding: 0 2rem;
    }
    .size-guide-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1.25rem 0;
    }
    .size-table-container {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(201,122,140, 0.04);
        transition: box-shadow 0.3s ease;
    }
    .size-table-container.highlight {
        box-shadow: 0 0 0 3px var(--blush);
    }
    .size-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .size-table thead {
        background: #fcdde5;
    }
    .size-table th {
        padding: 1.1rem 1.5rem;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--ink);
    }
    .size-table tbody tr {
        border-top: 1px solid #fae6ec;
        transition: background 0.2s;
    }
    .size-table tbody tr:hover {
        background: #fdf5f7;
    }
    .size-table td {
        padding: 1rem 1.5rem;
        font-size: 0.88rem;
        color: var(--ink-muted);
    }
    .size-table td:first-child {
        font-weight: 700;
        color: var(--ink);
    }

    /* ===== TABS & CARE SECTION ===== */
    .tabs-and-care-section {
        max-width: 1320px;
        margin: 0 auto 4rem;
        padding: 0 2rem;
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 3rem;
        align-items: start;
    }

    /* Tabs Header */
    .product-tabs-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        border-bottom: 2px solid #fae6ec;
        margin-bottom: 1.75rem;
    }
    .tab-nav-btn {
        background: none;
        border: none;
        font-family: 'DM Sans', sans-serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--ink-muted);
        padding: 0.75rem 0.25rem 1rem;
        cursor: pointer;
        position: relative;
        transition: all 0.2s ease;
    }
    .tab-nav-btn:hover {
        color: var(--blush);
    }
    .tab-nav-btn.active {
        color: var(--ink);
    }
    .tab-nav-btn.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2.5px;
        background: var(--blush);
        border-radius: 2px;
    }

    /* Tab Content - Deskripsi */
    .tab-panel {
        display: none;
        animation: fadeInTab 0.3s ease;
    }
    .tab-panel.active {
        display: block;
    }
    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .tab-content-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1rem 0;
    }
    .tab-content-text {
        font-size: 0.92rem;
        line-height: 1.75;
        color: #6a4a52;
        margin: 0 0 1.5rem 0;
    }
    .tab-features-title {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 0.85rem 0;
    }
    .tab-features-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .tab-features-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        font-size: 0.88rem;
        line-height: 1.6;
        color: var(--ink-muted);
    }
    .tab-features-list li::before {
        content: '•';
        color: var(--blush);
        font-size: 1.2rem;
        line-height: 1;
        margin-top: 0.1rem;
    }

    /* Tab Content - Ulasan */
    .reviews-summary-card {
        display: flex;
        align-items: center;
        gap: 2rem;
        padding: 1.5rem;
        background: #fdf5f7;
        border-radius: 14px;
        border: 1px solid var(--border);
        margin-bottom: 1.5rem;
    }
    .reviews-score {
        font-size: 2.75rem;
        font-family: 'Cormorant Garamond', serif;
        font-weight: 700;
        color: var(--ink);
        line-height: 1;
    }
    .review-item-card {
        border-bottom: 1px solid #f6e6ea;
        padding: 1.25rem 0;
    }
    .review-item-card:last-child {
        border-bottom: none;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.45rem;
    }
    .reviewer-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--ink);
    }
    .review-date {
        font-size: 0.78rem;
        color: #9a7a82;
    }

    /* Care Instructions Card (Right) */
    .care-instructions-card {
        background: var(--bg-warm);
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 18px rgba(201,122,140, 0.04);
    }
    .care-card-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 1.5rem 0;
    }
    .care-items-list {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .care-item {
        display: flex;
        align-items: center;
        gap: 1.1rem;
    }
    .care-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #ffffff;
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--blush);
        flex-shrink: 0;
    }
    .care-item-text {
        font-size: 0.88rem;
        color: var(--ink-muted);
        line-height: 1.5;
        margin: 0;
    }

    /* ===== PRODUK SERUPA ===== */
    .related-products-section {
        max-width: 1320px;
        margin: 0 auto 5rem;
        padding: 0 2rem;
    }
    .related-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .related-section-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0;
    }
    .related-view-all {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--blush);
        text-decoration: none;
        transition: color 0.2s;
    }
    .related-view-all:hover {
        color: var(--blush-dark);
    }

    .related-products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
    .related-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .related-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(201,122,140, 0.12);
        border-color: var(--blush);
    }
    .related-card-img-box {
        width: 100%;
        aspect-ratio: 3/4;
        background: #faf7f8;
        position: relative;
        overflow: hidden;
    }
    .related-card-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .related-card:hover .related-card-img-box img {
        transform: scale(1.05);
    }
    .related-badge-discount {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        background: #f43f5e;
        color: #fff;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        z-index: 2;
    }
    .related-btn-wishlist {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--blush);
        cursor: pointer;
        z-index: 2;
        transition: all 0.2s ease;
    }
    .related-btn-wishlist:hover {
        background: #fff;
        transform: scale(1.1);
    }
    .related-card-body {
        padding: 1.15rem 1.25rem 1.35rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .related-card-title {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.92rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }
    .related-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.65rem;
        margin-top: auto;
    }
    .related-price-current {
        font-weight: 700;
        color: var(--ink);
        font-size: 0.98rem;
    }
    .related-price-original {
        font-size: 0.82rem;
        color: var(--ink-faint);
        text-decoration: line-through;
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--ink);
        color: #fff;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        z-index: 999;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }
    .toast-notification svg {
        color: #10b981;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .product-main-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .tabs-and-care-section {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .related-products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 640px) {
        .product-gallery-wrapper {
            flex-direction: column-reverse;
        }
        .product-thumbnails-col {
            flex-direction: row;
            width: 100%;
            justify-content: flex-start;
        }
        .gallery-thumb-btn {
            width: 70px;
            height: 70px;
        }
        .product-title {
            font-size: 1.85rem;
        }
        .trust-badges-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .product-action-row {
            flex-direction: column;
        }
        .quantity-counter {
            justify-content: center;
        }
        .related-products-grid {
            grid-template-columns: 1fr;
        }
        .size-table th, .size-table td {
            padding: 0.75rem 0.85rem;
            font-size: 0.8rem;
        }
    }
</style>

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
                <button class="gallery-thumb-btn active" data-img-src="{{ asset($product['main_image']) }}" aria-label="Thumbnail 1">
                    <img src="{{ asset($product['gallery'][0] ?? $product['main_image']) }}" alt="Thumbnail 1">
                </button>
                <button class="gallery-thumb-btn" data-img-src="{{ asset($product['gallery'][1] ?? $product['main_image']) }}" aria-label="Thumbnail 2">
                    <img src="{{ asset($product['gallery'][1] ?? $product['main_image']) }}" alt="Thumbnail 2">
                </button>
                <button class="gallery-thumb-btn" data-img-src="{{ asset($product['gallery'][2] ?? $product['main_image']) }}" aria-label="Thumbnail 3">
                    <img src="{{ asset($product['gallery'][2] ?? $product['main_image']) }}" alt="Thumbnail 3">
                </button>
            </div>

            {{-- Main Image Box --}}
            <div class="product-main-img-box" id="product-main-img-box">
                <span class="badge-bestseller">{{ $product['badge'] ?? 'BEST SELLER' }}</span>
                                <button class="btn-wishlist-float {{ ($product['is_wishlisted'] ?? false) ? 'active' : '' }}" id="btn-wishlist-toggle" aria-label="Tambah ke Wishlist">
                    <i data-lucide="heart" style="width:20px;height:20px;"></i>
                </button>
                <img id="main-product-img" src="{{ asset($product['main_image']) }}" alt="{{ $product['title'] }}" loading="eager">
            </div>
        </div>

        {{-- Right: Product Details --}}
        <div class="product-info-wrapper" id="product-info">
            {{-- Rating & Collection --}}
            <div class="product-meta-row">
                <div class="rating-stars">
                    <i data-lucide="star" style="width:16px;height:16px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:16px;height:16px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:16px;height:16px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:16px;height:16px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:16px;height:16px;fill:#eab308;"></i>
                </div>
                <span class="rating-text">{{ $product['rating'] }} &middot; {{ $product['review_count'] }} ulasan</span>
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
                    <span class="option-title">Warna: <span class="option-value-name" id="selected-color-label">Red</span></span>
                </div>
                <div class="color-swatch-list">
                    @foreach($product['colors'] as $color)
                        <button 
                            class="color-swatch-item {{ $color['active'] ? 'active' : '' }}" 
                            style="background-color: {{ $color['hex'] }};" 
                            data-color-name="{{ $color['name'] }}"
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
                        <button 
                            class="size-pill-btn {{ $size === $product['default_size'] ? 'active' : '' }}" 
                            data-size="{{ $size }}">
                            {{ $size }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Quantity and Add to Cart --}}
            <div class="product-action-row">
                <div class="quantity-counter">
                    <button class="qty-btn" id="qty-minus" aria-label="Kurangi jumlah">&minus;</button>
                    <span class="qty-display" id="qty-val">1</span>
                    <button class="qty-btn" id="qty-plus" aria-label="Tambah jumlah">&plus;</button>
                </div>
                <button class="btn-add-to-cart" id="btn-add-to-cart">
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
                    <div class="reviews-score">4.9</div>
                    <div class="rating-stars" style="margin-top: 4px;">
                        <i data-lucide="star" style="width:18px;height:18px;fill:#eab308;"></i>
                        <i data-lucide="star" style="width:18px;height:18px;fill:#eab308;"></i>
                        <i data-lucide="star" style="width:18px;height:18px;fill:#eab308;"></i>
                        <i data-lucide="star" style="width:18px;height:18px;fill:#eab308;"></i>
                        <i data-lucide="star" style="width:18px;height:18px;fill:#eab308;"></i>
                    </div>
                </div>
                <div style="font-size: 0.88rem; color: var(--ink-muted); line-height: 1.5;">
                    <strong>98% Pembeli Puas</strong><br>
                    Berdasarkan {{ $product['review_count'] }} ulasan dari pelanggan yang telah berbelanja produk ini.
                </div>
            </div>

            <div class="review-item-card">
                <div class="review-header">
                    <span class="reviewer-name">Citra Kirana &bull; <small style="color:#10b981;font-weight:normal;">Verified Buyer</small></span>
                    <span class="review-date">2 hari yang lalu</span>
                </div>
                <div class="rating-stars" style="margin-bottom: 0.45rem;">
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                </div>
                <p style="font-size: 0.88rem; color: var(--ink-muted); margin: 0; line-height: 1.5;">
                    Bagus banget! Bahannya jatuh dan adem parah. Warnanya mewah seperti di foto, ukurannya pas banget sesuai tabel panduan ukuran.
                </p>
            </div>

            <div class="review-item-card">
                <div class="review-header">
                    <span class="reviewer-name">Nadia Safitri &bull; <small style="color:#10b981;font-weight:normal;">Verified Buyer</small></span>
                    <span class="review-date">1 minggu yang lalu</span>
                </div>
                <div class="rating-stars" style="margin-bottom: 0.45rem;">
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                    <i data-lucide="star" style="width:14px;height:14px;fill:#eab308;"></i>
                </div>
                <p style="font-size: 0.88rem; color: var(--ink-muted); margin: 0; line-height: 1.5;">
                    Packaging-nya niat dan wangi sekali saat dibuka. Bordir kelincinya manis dan rapi. Bakalan langganan di Sweet Dreams!
                </p>
            </div>
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
                    <button class="related-btn-wishlist" aria-label="Wishlist" onclick="event.preventDefault(); this.classList.toggle('active');">
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
    const thumbBtns = document.querySelectorAll('.gallery-thumb-btn');
    const mainImg = document.getElementById('main-product-img');

    thumbBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            thumbBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const newSrc = this.getAttribute('data-img-src') || this.querySelector('img').src;
            mainImg.style.opacity = '0.3';
            setTimeout(() => {
                mainImg.src = newSrc;
                mainImg.style.opacity = '1';
            }, 150);
        });
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
        });
    });

    // 3. Size Selection
    const sizeBtns = document.querySelectorAll('.size-pill-btn');
    sizeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            sizeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // 4. Quantity Counter
    const qtyVal = document.getElementById('qty-val');
    const qtyPlus = document.getElementById('qty-plus');
    const qtyMinus = document.getElementById('qty-minus');
    let currentQty = 1;

    qtyPlus.addEventListener('click', function() {
        currentQty++;
        qtyVal.textContent = currentQty;
    });

    qtyMinus.addEventListener('click', function() {
        if (currentQty > 1) {
            currentQty--;
            qtyVal.textContent = currentQty;
        }
    });

    // 5. Wishlist Float Button
        const wishlistBtn = document.getElementById('btn-wishlist-toggle');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function() {
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
            .then(res => res.json())
            .then(data => {
                wishlistBtn.classList.toggle('active', data.is_wishlisted);
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }
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

    // 8. Add to Cart Toast & Dynamic Cart Insertion
    const addToCartBtn = document.getElementById('btn-add-to-cart');
    const toast = document.getElementById('toast-notif');
    const toastMsg = document.getElementById('toast-msg');

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
