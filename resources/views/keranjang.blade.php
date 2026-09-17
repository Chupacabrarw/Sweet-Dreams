@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sweet Dreams')

@section('content')
<style>
    /* ===== TOP ANNOUNCEMENT BAR ===== */
    .cart-announcement-bar {
        background: #fdf2f5;
        border-bottom: 1px solid #f9d8e2;
        text-align: center;
        padding: 0.65rem 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #5a3a42;
    }

    /* ===== CART CONTAINER ===== */
    .cart-page-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }

    /* Header */
    .cart-header {
        margin-bottom: 2.25rem;
    }
    .cart-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.4rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.4rem 0;
    }
    .cart-header p {
        font-size: 0.95rem;
        color: #8a6a72;
        margin: 0;
    }

    /* Main 2-Col Layout */
    .cart-layout-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 3.5rem;
        align-items: start;
        margin-bottom: 5rem;
    }

    /* ===== CART ITEMS (LEFT) ===== */
    .cart-items-list {
        display: flex;
        flex-direction: column;
    }
    .cart-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.75rem 0;
        border-bottom: 1px solid #f6e2e8;
        transition: all 0.3s ease;
    }
    .cart-item-row:first-child {
        padding-top: 0.5rem;
    }
    .cart-item-row.removing {
        opacity: 0;
        transform: translateX(-30px);
    }

    .cart-item-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }
    .cart-item-img-box {
        width: 86px;
        height: 86px;
        border-radius: 14px;
        overflow: hidden;
        border: 1.5px solid #fbd5df;
        background: #faf6f7;
        flex-shrink: 0;
    }
    .cart-item-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .cart-item-details {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .cart-item-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
        text-decoration: none;
        transition: color 0.2s;
    }
    .cart-item-title:hover {
        color: #d44d6e;
    }
    .cart-item-variant {
        font-size: 0.85rem;
        color: #8a6a72;
        margin: 0;
    }
    .btn-cart-remove {
        background: none;
        border: none;
        color: #d44d6e;
        font-size: 0.82rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        cursor: pointer;
        padding: 0;
        margin-top: 0.2rem;
        transition: all 0.2s ease;
        width: fit-content;
    }
    .btn-cart-remove:hover {
        color: #a82e4e;
        transform: translateY(-1px);
    }

    .cart-item-actions {
        display: flex;
        align-items: center;
        gap: 2.5rem;
    }
    .cart-qty-counter {
        display: flex;
        align-items: center;
        border: 1.5px solid #e8d0d6;
        border-radius: 50px;
        background: #fff;
        height: 40px;
        padding: 0 4px;
    }
    .cart-qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        background: transparent;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #5a3a42;
        font-size: 1rem;
        transition: all 0.2s ease;
    }
    .cart-qty-btn:hover {
        background: #fce7ee;
        color: #d44d6e;
    }
    .cart-qty-val {
        min-width: 28px;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        color: #3a2a2e;
        user-select: none;
    }
    .cart-item-price {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        min-width: 110px;
        text-align: right;
    }

    /* Empty Cart State */
    .cart-empty-state {
        display: none;
        text-align: center;
        padding: 4rem 2rem;
        background: #fff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
    }
    .cart-empty-state svg {
        color: #d4b8c0;
        margin-bottom: 1rem;
    }
    .cart-empty-state h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
    }
    .cart-empty-state p {
        color: #8a6a72;
        font-size: 0.92rem;
        margin: 0 0 1.5rem 0;
    }
    .btn-empty-shop {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #d44d6e;
        color: #fff;
        padding: 12px 28px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.3s ease;
    }
    .btn-empty-shop:hover {
        background: #b83a58;
        transform: translateY(-2px);
    }

    /* ===== ORDER SUMMARY CARD (RIGHT) ===== */
    .order-summary-card {
        background: #fef5f7;
        border: 1.5px solid #fbd5df;
        border-radius: 24px;
        padding: 2.25rem 2rem;
        position: sticky;
        top: 88px;
        box-shadow: 0 6px 24px rgba(212, 77, 110, 0.05);
    }
    .summary-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 1.5rem 0;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        font-size: 0.92rem;
        color: #6a4a52;
    }
    .summary-row .value {
        font-weight: 600;
        color: #3a2a2e;
    }
    .summary-row .value.free {
        color: #10b981;
        font-weight: 700;
    }
    .summary-row .value.discount {
        color: #f43f5e;
        font-weight: 600;
    }

    /* Voucher Box */
    .voucher-section {
        margin: 1.5rem 0;
    }
    .voucher-label {
        display: block;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #8a6a72;
        margin-bottom: 0.5rem;
    }
    .voucher-input-group {
        display: flex;
        gap: 0.5rem;
    }
    .voucher-input-group input {
        flex: 1;
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e8d0d6;
        border-radius: 10px;
        background: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        color: #3a2a2e;
        outline: none;
        transition: border-color 0.2s;
        text-transform: uppercase;
    }
    .voucher-input-group input:focus {
        border-color: #d44d6e;
    }
    .btn-apply-voucher {
        height: 44px;
        padding: 0 1.25rem;
        background: #e06b88;
        border: none;
        border-radius: 10px;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-apply-voucher:hover {
        background: #d44d6e;
    }
    .voucher-msg {
        font-size: 0.78rem;
        margin-top: 0.4rem;
        color: #10b981;
        display: none;
    }

    /* Total Row */
    .summary-total-divider {
        height: 1px;
        background: #f4dbe2;
        border: none;
        margin: 1.5rem 0 1.25rem;
    }
    .summary-total-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 1.5rem;
    }
    .total-label {
        font-family: 'Playfair Display', serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .total-price {
        font-family: 'Inter', sans-serif;
        font-size: 1.65rem;
        font-weight: 800;
        color: #d44d6e;
    }

    /* Checkout Button */
    .btn-checkout {
        width: 100%;
        height: 52px;
        border: none;
        border-radius: 50px;
        background: linear-gradient(135deg, #e87b94 0%, #d44d6e 100%);
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 20px rgba(212, 77, 110, 0.35);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        margin-bottom: 1.5rem;
    }
    .btn-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 77, 110, 0.45);
        background: linear-gradient(135deg, #d44d6e 0%, #ba3253 100%);
    }

    /* Trust row */
    .summary-trust-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1.25rem;
        margin-bottom: 1.25rem;
    }
    .summary-trust-item {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.76rem;
        color: #8a6a72;
        font-weight: 500;
    }
    .summary-trust-item svg {
        width: 14px;
        height: 14px;
        color: #b48a92;
    }

    /* Payment Badges */
    .summary-payments-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .summary-pay-badge {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        color: #7a5a62;
        background: #ffffff;
        border: 1px solid #f0d5dc;
        padding: 4px 10px;
        border-radius: 6px;
    }

    /* ===== REKOMENDASI (MUNGKIN KAMU JUGA SUKA) ===== */
    .cart-recommendations-section {
        margin-top: 1rem;
    }
    .recommendations-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.85rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 2rem 0;
    }
    .recommendations-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
    .rec-product-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 18px;
        overflow: hidden;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .rec-product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(212, 77, 110, 0.12);
        border-color: #d44d6e;
    }
    .rec-card-img-box {
        width: 100%;
        aspect-ratio: 3/4;
        background: #faf7f8;
        position: relative;
        overflow: hidden;
    }
    .rec-card-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }
    .rec-product-card:hover .rec-card-img-box img {
        transform: scale(1.05);
    }
    .rec-badge-discount {
        position: absolute;
        top: 0.85rem;
        left: 0.85rem;
        background: #f43f5e;
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        z-index: 2;
    }
    .rec-btn-wishlist {
        position: absolute;
        top: 0.85rem;
        right: 0.85rem;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #fbd5df;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d44d6e;
        cursor: pointer;
        z-index: 2;
        transition: all 0.2s ease;
    }
    .rec-btn-wishlist:hover {
        background: #fff;
        transform: scale(1.1);
    }
    .rec-card-body {
        padding: 1.15rem 1.25rem 1.35rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .rec-card-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.92rem;
        font-weight: 600;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
        line-height: 1.4;
    }
    .rec-price-row {
        display: flex;
        align-items: baseline;
        gap: 0.65rem;
        margin-top: auto;
    }
    .rec-price-current {
        font-weight: 700;
        color: #3a2a2e;
        font-size: 0.98rem;
    }
    .rec-price-original {
        font-size: 0.82rem;
        color: #b48a92;
        text-decoration: line-through;
    }

    /* Checkout Modal Overlay */
    .checkout-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(58, 42, 46, 0.45);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .checkout-modal-overlay.open {
        display: flex;
    }
    .checkout-modal-box {
        background: #ffffff;
        border-radius: 24px;
        max-width: 460px;
        width: 100%;
        padding: 2.5rem 2rem;
        text-align: center;
        box-shadow: 0 16px 40px rgba(0,0,0,0.18);
        animation: scaleUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes scaleUpModal {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .checkout-modal-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #fdf2f5;
        color: #d44d6e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .checkout-modal-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.6rem 0;
    }
    .checkout-modal-text {
        font-size: 0.92rem;
        color: #6a4a52;
        line-height: 1.6;
        margin: 0 0 1.75rem 0;
    }
    .btn-modal-close {
        width: 100%;
        height: 48px;
        background: #d44d6e;
        color: #fff;
        border: none;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-modal-close:hover {
        background: #b83a58;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .cart-layout-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
        .recommendations-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 640px) {
        .cart-item-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        .cart-item-actions {
            width: 100%;
            justify-content: space-between;
        }
        .recommendations-grid {
            grid-template-columns: 1fr;
        }
        .cart-header h1 {
            font-size: 1.85rem;
        }
    }
</style>

{{-- TOP ANNOUNCEMENT BAR --}}
<div class="cart-announcement-bar" id="cart-announcement">
    FREE SHIPPING ON ORDERS OVER RP500.000 ✨
</div>

<div class="cart-page-wrapper">
    {{-- Header --}}
    <div class="cart-header">
        <h1>Keranjang Belanja</h1>
        <p id="cart-count-subtitle">2 items in your cart</p>
    </div>

    {{-- Main 2-Col Layout --}}
    <div class="cart-layout-grid" id="cart-content-area">
        
        {{-- Left: Cart Items List --}}
        <div class="cart-items-list" id="cart-items-container">
            {{-- Content will be rendered dynamically by JavaScript --}}
        </div>

        {{-- Empty Cart Box (Initially hidden) --}}
        <div class="cart-empty-state" id="cart-empty-state">
            <i data-lucide="shopping-bag" style="width:54px;height:54px;"></i>
            <h3>Keranjang Anda Masih Kosong</h3>
            <p>Jelajahi koleksi busana tidur premium kami dan temukan kenyamanan impian Anda.</p>
            <a href="/katalog" class="btn-empty-shop">
                Mulai Belanja
                <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
            </a>
        </div>

        {{-- Right: Order Summary Card --}}
        <div class="order-summary-card" id="order-summary-card">
            <h3 class="summary-title">Ringkasan Pesanan</h3>

            <div class="summary-row">
                <span>Subtotal</span>
                <span class="value" id="summary-subtotal">Rp0</span>
            </div>

            <div class="summary-row">
                <span>Estimasi Ongkir</span>
                <span class="value free" id="summary-shipping">FREE</span>
            </div>

            <div class="summary-row" id="row-discount">
                <span>Diskon Promo</span>
                <span class="value discount" id="summary-discount">- Rp50.000</span>
            </div>

            {{-- Voucher Input --}}
            <div class="voucher-section">
                <label class="voucher-label" for="input-voucher">KODE VOUCHER</label>
                <div class="voucher-input-group">
                    <input type="text" id="input-voucher" value="SWEETDREAM50" placeholder="MASUKKAN KODE">
                    <button class="btn-apply-voucher" id="btn-apply-voucher">Terapkan</button>
                </div>
                <div class="voucher-msg" id="voucher-msg">Voucher diskon Rp50.000 berhasil dipasang!</div>
            </div>

            <hr class="summary-total-divider">

            <div class="summary-total-row">
                <span class="total-label">Total Akhir</span>
                <span class="total-price" id="summary-total">Rp0</span>
            </div>

            <a href="/checkout" class="btn-checkout" id="btn-checkout" style="text-decoration: none;">
                Lanjut ke Checkout
            </a>

            {{-- Trust badges --}}
            <div class="summary-trust-row">
                <div class="summary-trust-item">
                    <i data-lucide="shield-check"></i>
                    <span>100% Secure Payment</span>
                </div>
                <div class="summary-trust-item">
                    <i data-lucide="rotate-ccw"></i>
                    <span>Easy 15-Day Returns</span>
                </div>
            </div>

            {{-- Payment badges --}}
            <div class="summary-payments-row">
                <span class="summary-pay-badge">QRIS</span>
                <span class="summary-pay-badge">TRANSFER BANK</span>
                <span class="summary-pay-badge">E-WALLET</span>
            </div>
        </div>

    </div>

    {{-- Rekomendasi: Mungkin Kamu Juga Suka --}}
    <section class="cart-recommendations-section">
        <h2 class="recommendations-title">Mungkin Kamu Juga Suka</h2>

        <div class="recommendations-grid">
            @foreach($recommendedProducts as $idx => $rec)
                <a href="/produk/{{ $rec['slug'] }}" class="rec-product-card" id="rec-prod-{{ $idx + 1 }}">
                    <div class="rec-card-img-box">
                        <span class="rec-badge-discount">{{ $rec['discount'] }}</span>
                        <button class="rec-btn-wishlist" aria-label="Wishlist" onclick="event.preventDefault(); this.classList.toggle('active');">
                            <i data-lucide="heart" style="width:16px;height:16px;"></i>
                        </button>
                        <img src="{{ asset($rec['image']) }}" alt="{{ $rec['name'] }}" loading="lazy">
                    </div>
                    <div class="rec-card-body">
                        <h4 class="rec-card-title">{{ $rec['name'] }}</h4>
                        <div class="rec-price-row">
                            <span class="rec-price-current">{{ $rec['price'] }}</span>
                            <span class="rec-price-original">{{ $rec['original_price'] }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</div>

{{-- CHECKOUT CONFIRMATION MODAL --}}
<div class="checkout-modal-overlay" id="checkout-modal">
    <div class="checkout-modal-box">
        <div class="checkout-modal-icon">
            <i data-lucide="check-circle" style="width:36px;height:36px;"></i>
        </div>
        <h3 class="checkout-modal-title">Pesanan Diproses!</h3>
        <p class="checkout-modal-text">
            Terima kasih telah berbelanja di <strong>Sweet Dreams</strong>. Anda akan diarahkan ke saluran pembayaran aman kami.
        </p>
        <button class="btn-modal-close" id="btn-close-modal">Tutup</button>
    </div>
</div>

<script>
 window.initialCartItems = @json($cartItems);
document.addEventListener('DOMContentLoaded', function() {
    let discountAmount = 50000;
    let isVoucherApplied = true;

    const subtotalEl = document.getElementById('summary-subtotal');
    const shippingEl = document.getElementById('summary-shipping');
    const discountEl = document.getElementById('summary-discount');
    const totalEl = document.getElementById('summary-total');
    const subtitleEl = document.getElementById('cart-count-subtitle');
    const itemsContainer = document.getElementById('cart-items-container');
    const emptyState = document.getElementById('cart-empty-state');
    const summaryCard = document.getElementById('order-summary-card');
    const announcementBar = document.getElementById('cart-announcement');

    function formatRupiah(num) {
        return 'Rp' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function renderCart() {
                const items = window.initialCartItems || [];

        // Check if empty
        if (!items || items.length === 0) {
            if (itemsContainer) {
                itemsContainer.innerHTML = '';
                itemsContainer.style.display = 'none';
            }
            if (summaryCard) summaryCard.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            if (subtitleEl) subtitleEl.textContent = '0 items in your cart';
            if (announcementBar) announcementBar.textContent = 'FREE SHIPPING ON ORDERS OVER RP500.000 ✨';
            lucide.createIcons();
            return;
        }

        if (emptyState) emptyState.style.display = 'none';
        if (itemsContainer) itemsContainer.style.display = 'flex';
        if (summaryCard) summaryCard.style.display = 'block';

        let html = '';
        let subtotal = 0;
        let totalCount = 0;

        items.forEach(item => {
            const itemQty = parseInt(item.qty) || 1;
            const itemPrice = parseInt(item.price) || 0;
            const itemSubtotal = itemQty * itemPrice;
            subtotal += itemSubtotal;
            totalCount += itemQty;

            html += `
                <div class="cart-item-row" id="cart-row-${item.id}" data-id="${item.id}">
                    <div class="cart-item-info">
                        <div class="cart-item-img-box">
                            <img src="${item.image}" alt="${item.title}">
                        </div>
                        <div class="cart-item-details">
                            <a href="/produk/${item.slug || 'sailor-rabbit-set'}" class="cart-item-title">${item.title}</a>
                            <p class="cart-item-variant">${item.variant || (item.color + ' · Size ' + item.size)}</p>
                            <button class="btn-cart-remove" data-id="${item.id}" aria-label="Hapus item">
                                <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                Hapus
                            </button>
                        </div>
                    </div>

                    <div class="cart-item-actions">
                        <div class="cart-qty-counter">
                            <button class="cart-qty-btn btn-qty-dec" data-id="${item.id}" aria-label="Kurangi kuantitas">&minus;</button>
                            <span class="cart-qty-val" id="qty-${item.id}">${itemQty}</span>
                            <button class="cart-qty-btn btn-qty-inc" data-id="${item.id}" aria-label="Tambah kuantitas">&plus;</button>
                        </div>
                        <div class="cart-item-price" id="price-${item.id}">
                            ${formatRupiah(itemSubtotal)}
                        </div>
                    </div>
                </div>
            `;
        });

        itemsContainer.innerHTML = html;
        lucide.createIcons();

                function updateCartQty(id, newQty) {
            fetch(`/api/cart/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quantity: newQty })
            }).then(() => window.location.reload());
        }

        // Plus quantity
        itemsContainer.querySelectorAll('.btn-qty-inc').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const it = items.find(i => String(i.id) === String(id));
                if (it) updateCartQty(id, it.qty + 1);
            });
        });

        // Minus quantity
        itemsContainer.querySelectorAll('.btn-qty-dec').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const it = items.find(i => String(i.id) === String(id));
                if (it && it.qty > 1) updateCartQty(id, it.qty - 1);
            });
        });

        // Remove item
        itemsContainer.querySelectorAll('.btn-cart-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = document.getElementById('cart-row-' + id);
                if (row) row.classList.add('removing');

                fetch(`/api/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(() => setTimeout(() => window.location.reload(), 250));
            });
        });

        // Update Subtitle
        if (subtitleEl) {
            subtitleEl.textContent = `${totalCount} item${totalCount > 1 ? 's' : ''} in your cart`;
        }

        // Subtotal
        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);

        // Shipping calculation
        let shippingFee = 0;
        if (subtotal >= 500000) {
            shippingFee = 0;
            if (shippingEl) {
                shippingEl.textContent = 'FREE';
                shippingEl.className = 'value free';
            }
            if (announcementBar) {
                announcementBar.innerHTML = 'SELAMAT! ANDA MENDAPATKAN GRATIS ONGKIR (BELANJA > RP500.000) ✨';
            }
        } else {
            shippingFee = 25000;
            const diff = 500000 - subtotal;
            if (shippingEl) {
                shippingEl.textContent = formatRupiah(shippingFee);
                shippingEl.className = 'value';
            }
            if (announcementBar) {
                announcementBar.innerHTML = `TAMBAHKAN ${formatRupiah(diff)} LAGI UNTUK MENDAPATKAN GRATIS ONGKIR! ✨`;
            }
        }

        // Discount
        let currentDiscount = isVoucherApplied ? Math.min(discountAmount, subtotal) : 0;
        if (discountEl) {
            discountEl.textContent = currentDiscount > 0 ? `- ${formatRupiah(currentDiscount)}` : 'Rp0';
        }

        // Grand Total
        let grandTotal = Math.max(0, subtotal + shippingFee - currentDiscount);
        if (totalEl) totalEl.textContent = formatRupiah(grandTotal);
    }

    // Voucher application
    const btnVoucher = document.getElementById('btn-apply-voucher');
    const inputVoucher = document.getElementById('input-voucher');
    const msgVoucher = document.getElementById('voucher-msg');

    if (btnVoucher && inputVoucher) {
        btnVoucher.addEventListener('click', function() {
            const code = inputVoucher.value.trim().toUpperCase();
            
            if (code === '') {
                isVoucherApplied = false;
                discountAmount = 0;
                msgVoucher.style.display = 'none';
                renderCart();
                return;
            }

            // Memanggil API backend untuk mengecek validitas voucher
            fetch('/api/validate-voucher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    code: code,
                    // Karena subtotal sudah dideklarasikan di renderCart, kita perlu menghitung ulang sementara
                    // atau ambil dari elemen teks subtotal yang ada.
                    subtotal: window.initialCartItems ? window.initialCartItems.reduce((sum, item) => sum + (item.qty * item.price), 0) : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    isVoucherApplied = true;
                    discountAmount = data.discount;
                    msgVoucher.textContent = `Voucher ${data.label || 'diskon'} berhasil dipasang!`;
                    msgVoucher.style.color = '#10b981';
                    msgVoucher.style.display = 'block';
                } else {
                    isVoucherApplied = false;
                    discountAmount = 0;
                    msgVoucher.textContent = data.message || 'Kode voucher tidak valid';
                    msgVoucher.style.color = '#f43f5e';
                    msgVoucher.style.display = 'block';
                }
                renderCart();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengecek voucher.');
            });
        });
    }

    // Listen to external cart update events
    window.addEventListener('sweetdreams_cart_updated', renderCart);

    // Initial render
    renderCart();
});
</script>
@endsection
