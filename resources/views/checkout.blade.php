@extends('layouts.app')

@section('title', 'Checkout - Sweet Dreams')

@section('content')
<style>
    /* ===== STEPPER HEADER ===== */
    .checkout-stepper-wrapper {
        padding: 1.5rem 2rem;
        background: #ffffff;
        border-bottom: 1px solid #fceef2;
    }
    .checkout-stepper {
        max-width: 600px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.85rem;
        font-size: 0.82rem;
    }
    .stepper-step {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        color: #8a6a72;
        font-weight: 500;
        text-decoration: none;
    }
    .stepper-step.completed {
        color: #d44d6e;
    }
    .stepper-step.completed .step-circle {
        background: #fce7ee;
        color: #d44d6e;
        border-color: #fbd5df;
    }
    .stepper-step.active {
        color: #3a2a2e;
        font-weight: 700;
    }
    .stepper-step.active .step-pill {
        background: #e06b88;
        color: #ffffff;
        padding: 4px 14px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        box-shadow: 0 2px 8px rgba(224, 107, 136, 0.3);
    }
    .step-circle {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 1px solid #d4b8c0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
    }
    .stepper-arrow {
        color: #d4b8c0;
        width: 14px;
        height: 14px;
    }

    /* ===== CHECKOUT MAIN LAYOUT ===== */
    .checkout-page-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }
    .checkout-grid {
        display: grid;
        grid-template-columns: 1.45fr 1fr;
        gap: 3.5rem;
        align-items: start;
    }

    /* ===== SECTION HEADINGS ===== */
    .section-accent-heading {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin: 0 0 1.25rem 0;
    }
    .accent-bar {
        width: 4px;
        height: 20px;
        background: #b87b58;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .section-accent-heading h2 {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }

    /* ===== FORMS ===== */
    .checkout-form-section {
        margin-bottom: 2.5rem;
    }
    .form-group {
        margin-bottom: 1.1rem;
    }
    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #5a3a42;
        margin-bottom: 0.4rem;
    }
    .form-group label .required {
        color: #f43f5e;
    }
    .form-control {
        width: 100%;
        height: 46px;
        padding: 0 1rem;
        border: 1.5px solid #f2e2e6;
        border-radius: 12px;
        background: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        color: #3a2a2e;
        outline: none;
        transition: all 0.2s ease;
        box-sizing: border-box;
    }
    .form-control:focus {
        border-color: #d44d6e;
        box-shadow: 0 0 0 3px rgba(212, 77, 110, 0.1);
    }
    .form-control.is-invalid {
        border-color: #f43f5e !important;
        background: #fff8f9 !important;
        box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.12) !important;
        animation: shakeField 0.3s ease-in-out;
    }
    @keyframes shakeField {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        75% { transform: translateX(4px); }
    }
    .field-error-msg {
        display: none;
        color: #f43f5e;
        font-size: 0.76rem;
        font-weight: 500;
        margin-top: 0.35rem;
    }
    .field-error-msg.visible {
        display: block;
    }

    /* Checkout Floating Alert Toast */
    .checkout-alert-toast {
        position: fixed;
        top: 90px;
        right: 2rem;
        z-index: 1100;
        background: #ffffff;
        border-left: 4px solid #f43f5e;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(58, 42, 46, 0.16);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        max-width: 440px;
        transform: translateX(120%);
        opacity: 0;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .checkout-alert-toast.show {
        transform: translateX(0);
        opacity: 1;
        pointer-events: auto;
    }
    .checkout-alert-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fff1f2;
        color: #f43f5e;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .checkout-alert-content h4 {
        margin: 0 0 0.2rem 0;
        font-family: 'Inter', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .checkout-alert-content p {
        margin: 0;
        font-size: 0.82rem;
        color: #6a4a52;
        line-height: 1.45;
    }
    .checkout-alert-close {
        background: none;
        border: none;
        color: #b48a92;
        cursor: pointer;
        padding: 0;
        margin-left: auto;
        font-size: 1.25rem;
        line-height: 1;
        transition: color 0.2s;
    }
    .checkout-alert-close:hover {
        color: #3a2a2e;
    }
    textarea.form-control {
        height: auto;
        padding: 0.85rem 1rem;
        resize: vertical;
        line-height: 1.5;
    }
    .form-row-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* ===== RADIO SELECTION CARDS ===== */
    .radio-card-list {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .radio-card-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.35rem;
        background: #ffffff;
        border: 1.5px solid #f0dee2;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }
    .radio-card-item:hover {
        border-color: #d44d6e;
    }
    .radio-card-item.active {
        background: #fffbfa;
        border-color: #e88b9e;
        box-shadow: 0 2px 12px rgba(212, 77, 110, 0.08);
    }
    .radio-card-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .custom-radio-circle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #d4b8c0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .radio-card-item.active .custom-radio-circle {
        border-color: #d44d6e;
    }
    .custom-radio-circle::after {
        content: '';
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d44d6e;
        transform: scale(0);
        transition: transform 0.2s ease;
    }
    .radio-card-item.active .custom-radio-circle::after {
        transform: scale(1);
    }
    .radio-card-text {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    .radio-card-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .radio-card-desc {
        font-size: 0.8rem;
        color: #8a6a72;
    }
    .radio-card-price {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .radio-card-item.active .radio-card-price {
        color: #d44d6e;
    }
    .radio-card-badges {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #8a6a72;
        text-transform: uppercase;
    }

    /* ===== RIGHT: ORDER SUMMARY CARD ===== */
    .checkout-summary-card {
        background: #fef5f7;
        border: 1.5px solid #fbd5df;
        border-radius: 24px;
        padding: 2.25rem 2rem;
        position: sticky;
        top: 88px;
        box-shadow: 0 6px 24px rgba(212, 77, 110, 0.05);
    }
    .summary-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.45rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 1.5rem 0;
    }

    /* Mini Items List */
    .summary-items-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .summary-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .summary-item-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .summary-item-img {
        width: 58px;
        height: 58px;
        border-radius: 10px;
        overflow: hidden;
        border: 1.5px solid #fbd5df;
        background: #ffffff;
        flex-shrink: 0;
    }
    .summary-item-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .summary-item-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.2rem 0;
    }
    .summary-item-sub {
        font-size: 0.78rem;
        color: #8a6a72;
        margin: 0;
    }
    .summary-item-price {
        font-family: 'Inter', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        color: #3a2a2e;
        white-space: nowrap;
    }

    /* Voucher Input Box */
    .summary-voucher-group {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .summary-voucher-group input {
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
    .summary-voucher-group input:focus {
        border-color: #d44d6e;
    }
    .btn-summary-voucher {
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
    .btn-summary-voucher:hover {
        background: #d44d6e;
    }

    /* Cost Breakdown */
    .cost-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.88rem;
        color: #6a4a52;
    }
    .cost-row .cost-val {
        font-weight: 600;
        color: #3a2a2e;
    }
    .cost-row .cost-val.discount {
        color: #f43f5e;
    }

    .cost-divider {
        height: 1px;
        background: #f4dbe2;
        border: none;
        margin: 1.25rem 0;
    }

    .total-cost-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 1.5rem;
    }
    .total-cost-label {
        font-family: 'Playfair Display', serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .total-cost-val {
        font-family: 'Inter', sans-serif;
        font-size: 1.65rem;
        font-weight: 800;
        color: #e06b88;
    }

    /* Bayar Sekarang Button */
    .btn-pay-now {
        width: 100%;
        height: 52px;
        border: none;
        border-radius: 50px;
        background: #e06b88;
        color: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 18px rgba(224, 107, 136, 0.35);
        transition: all 0.25s ease;
        margin-bottom: 1.25rem;
    }
    .btn-pay-now:hover {
        background: #d44d6e;
        transform: translateY(-2px);
        box-shadow: 0 6px 22px rgba(212, 77, 110, 0.45);
    }

    .ssl-trust-note {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        font-size: 0.76rem;
        color: #8a6a72;
    }
    .ssl-trust-note svg {
        width: 13px;
        height: 13px;
        color: #8a6a72;
    }

    /* ===== CONFIRMATION MODAL ===== */
    .order-success-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(58, 42, 46, 0.5);
        backdrop-filter: blur(5px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .order-success-modal.open {
        display: flex;
    }
    .success-modal-card {
        background: #ffffff;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        padding: 2.5rem 2rem;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
        animation: popModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes popModal {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .success-icon-badge {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #fdf2f5;
        color: #d44d6e;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }
    .success-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
    }
    .success-invoice-id {
        display: inline-block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #d44d6e;
        background: #fce7ee;
        padding: 4px 12px;
        border-radius: 50px;
        margin-bottom: 1rem;
    }
    .success-desc {
        font-size: 0.9rem;
        color: #6a4a52;
        line-height: 1.6;
        margin: 0 0 1.75rem 0;
    }
    .btn-home-return {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        height: 48px;
        border-radius: 50px;
        background: #d44d6e;
        color: #ffffff;
        font-weight: 600;
        text-decoration: none;
        font-size: 0.95rem;
        transition: background 0.2s;
    }
    .btn-home-return:hover {
        background: #b83a58;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .checkout-grid {
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }
    }
    @media (max-width: 640px) {
        .form-row-2col {
            grid-template-columns: 1fr;
        }
        .checkout-stepper {
            gap: 0.45rem;
            font-size: 0.72rem;
        }
        .checkout-page-container {
            padding: 1.5rem 1rem 3rem;
        }
    }
</style>

{{-- STEPPER HEADER --}}
<div class="checkout-stepper-wrapper">
    <div class="checkout-stepper">
        <a href="/keranjang" class="stepper-step completed">
            <span class="step-circle"><i data-lucide="check" style="width:12px;height:12px;"></i></span>
            <span>Cart</span>
        </a>
        <i data-lucide="chevron-right" class="stepper-arrow"></i>
        <div class="stepper-step completed">
            <span class="step-circle"><i data-lucide="check" style="width:12px;height:12px;"></i></span>
            <span>Shipping</span>
        </div>
        <i data-lucide="chevron-right" class="stepper-arrow"></i>
        <div class="stepper-step active">
            <div class="step-pill">
                <span>3</span>
                <span>Payment</span>
            </div>
        </div>
        <i data-lucide="chevron-right" class="stepper-arrow"></i>
        <div class="stepper-step">
            <span class="step-circle">4</span>
            <span>Confirmation</span>
        </div>
    </div>
</div>

<div class="checkout-page-container">
    <div class="checkout-grid">
        
        {{-- Left: Forms & Options --}}
        <div class="checkout-main-forms">
            
            {{-- 1. Informasi Pengiriman --}}
            <section class="checkout-form-section" id="section-shipping-info">
                <div class="section-accent-heading">
                    <div class="accent-bar"></div>
                    <h2>Informasi Pengiriman</h2>
                </div>

                <div class="form-group">
                    <label for="input-nama">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="input-nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    <span class="field-error-msg" id="err-nama">Nama lengkap wajib diisi minimal 3 karakter.</span>
                </div>

                <div class="form-group">
                    <label for="input-phone">Nomor Telepon <span class="required">*</span></label>
                    <input type="tel" id="input-phone" class="form-control" placeholder="Contoh: 081234567890" required>
                    <span class="field-error-msg" id="err-phone">Nomor telepon harus valid (minimal 9 digit angka).</span>
                </div>

                <div class="form-group">
                    <label for="input-alamat">Alamat Lengkap <span class="required">*</span></label>
                    <textarea id="input-alamat" class="form-control" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan/kecamatan" required></textarea>
                    <span class="field-error-msg" id="err-alamat">Alamat pengiriman lengkap wajib diisi.</span>
                </div>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="input-kota">Kota <span class="required">*</span></label>
                        <input type="text" id="input-kota" class="form-control" placeholder="Kota / Kabupaten" required>
                        <span class="field-error-msg" id="err-kota">Kota / Kabupaten wajib diisi.</span>
                    </div>
                    <div class="form-group">
                        <label for="input-provinsi">Provinsi <span class="required">*</span></label>
                        <input type="text" id="input-provinsi" class="form-control" placeholder="Provinsi" required>
                        <span class="field-error-msg" id="err-provinsi">Provinsi wajib diisi.</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="input-kodepos">Kode Pos <span class="required">*</span></label>
                    <input type="text" id="input-kodepos" class="form-control" placeholder="Kode pos" required>
                    <span class="field-error-msg" id="err-kodepos">Kode pos wajib diisi (minimal 4-5 digit).</span>
                </div>
            </section>

            {{-- 2. Metode Pengiriman --}}
            <section class="checkout-form-section" id="section-couriers">
                <div class="section-accent-heading">
                    <div class="accent-bar"></div>
                    <h2>Metode Pengiriman</h2>
                </div>

                <div class="radio-card-list">
                    @foreach($shippingMethods as $courier)
                        <div class="radio-card-item {{ $courier['active'] ? 'active' : '' }}" 
                             data-type="shipping" 
                             data-id="{{ $courier['id'] }}" 
                             data-name="{{ $courier['name'] }}" 
                             data-cost="{{ $courier['cost'] }}">
                            <div class="radio-card-left">
                                <div class="custom-radio-circle"></div>
                                <div class="radio-card-text">
                                    <span class="radio-card-title">{{ $courier['name'] }}</span>
                                    <span class="radio-card-desc">{{ $courier['desc'] }}</span>
                                </div>
                            </div>
                            <span class="radio-card-price">Rp {{ number_format($courier['cost'], 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- 3. Metode Pembayaran --}}
            <section class="checkout-form-section" id="section-payment">
                <div class="section-accent-heading">
                    <div class="accent-bar"></div>
                    <h2>Metode Pembayaran</h2>
                </div>

                <div class="radio-card-list">
                    @foreach($paymentMethods as $payment)
                        <div class="radio-card-item {{ $payment['active'] ? 'active' : '' }}" 
                             data-type="payment" 
                             data-id="{{ $payment['id'] }}">
                            <div class="radio-card-left">
                                <div class="custom-radio-circle"></div>
                                <div class="radio-card-text">
                                    <span class="radio-card-title">{{ $payment['name'] }}</span>
                                    <span class="radio-card-desc">{{ $payment['desc'] }}</span>
                                </div>
                            </div>
                            @if(!empty($payment['badges']))
                                <span class="radio-card-badges">{{ $payment['badges'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

        </div>

        {{-- Right: Order Summary Card --}}
        <div class="checkout-summary-col">
            <div class="checkout-summary-card">
                <h3 class="summary-card-title">Ringkasan Pesanan</h3>

                {{-- Mini Items List (Rendered dynamically) --}}
                <div class="summary-items-list" id="checkout-items-list">
                    {{-- Populated dynamically by JavaScript --}}
                </div>

                {{-- Voucher Input --}}
                <div class="summary-voucher-group">
                    <input type="text" id="voucher-checkout-input" value="SWEETDREAM10" placeholder="KODE VOUCHER">
                    <button class="btn-summary-voucher" id="btn-checkout-voucher">Terapkan</button>
                </div>

                {{-- Cost Breakdown --}}
                <div class="cost-row">
                    <span>Subtotal</span>
                    <span class="cost-val" id="checkout-subtotal">Rp 0</span>
                </div>

                <div class="cost-row">
                    <span>Biaya Pengiriman (<span id="summary-courier-label">SiCepat</span>)</span>
                    <span class="cost-val" id="checkout-shipping">Rp 25.000</span>
                </div>

                <div class="cost-row" id="row-checkout-discount">
                    <span>Diskon (<span id="discount-label">Promo SWEETDREAM10</span>)</span>
                    <span class="cost-val discount" id="checkout-discount">-Rp 0</span>
                </div>

                <hr class="cost-divider">

                <div class="total-cost-row">
                    <span class="total-cost-label">Total Pembayaran</span>
                    <span class="total-cost-val" id="checkout-total">Rp 0</span>
                </div>

                <button class="btn-pay-now" id="btn-pay-now">
                    Bayar Sekarang
                </button>

                <div class="ssl-trust-note">
                    <i data-lucide="lock"></i>
                    <span>Transaksi aman & terenkripsi SSL</span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- FLOATING VALIDATION ALERT TOAST --}}
<div class="checkout-alert-toast" id="checkout-alert-toast">
    <div class="checkout-alert-icon">
        <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
    </div>
    <div class="checkout-alert-content">
        <h4 id="checkout-alert-title">Data Belum Lengkap</h4>
        <p id="checkout-alert-msg">Harap lengkapi seluruh data pengiriman sebelum melakukan pembayaran.</p>
    </div>
    <button class="checkout-alert-close" id="checkout-alert-close" aria-label="Tutup Notifikasi">&times;</button>
</div>

{{-- ORDER CONFIRMATION MODAL --}}
<div class="order-success-modal" id="order-modal">
    <div class="success-modal-card">
        <div class="success-icon-badge">
            <i data-lucide="check-circle" style="width:40px;height:40px;"></i>
        </div>
        <h3 class="success-title">Pesanan Berhasil Dibuat!</h3>
        <span class="success-invoice-id" id="invoice-display">INVOICE: #SD-20240908-01</span>
        <p class="success-desc">
            Terima kasih, <strong id="customer-name-display">Nabila Putri</strong>! Instruksi pembayaran telah dikirimkan ke nomor telepon dan pesanan Anda sedang disiapkan.
        </p>
        <a href="/" class="btn-home-return">
            Kembali ke Beranda
            <i data-lucide="arrow-right" style="width:18px;height:18px;"></i>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide Icons
    lucide.createIcons();

    let items = window.SweetDreamsCart ? window.SweetDreamsCart.getCart() : [];
    let currentShippingCost = 25000;
    let appliedVoucher = 'SWEETDREAM10';

    const itemsContainer = document.getElementById('checkout-items-list');
    const subtotalEl = document.getElementById('checkout-subtotal');
    const courierLabel = document.getElementById('summary-courier-label');
    const shippingEl = document.getElementById('checkout-shipping');
    const totalEl = document.getElementById('checkout-total');
    const discountEl = document.getElementById('checkout-discount');
    const discountLabel = document.getElementById('discount-label');
    const btnPayNow = document.getElementById('btn-pay-now');

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function showCheckoutAlert(title, message) {
        const toast = document.getElementById('checkout-alert-toast');
        const titleEl = document.getElementById('checkout-alert-title');
        const msgEl = document.getElementById('checkout-alert-msg');

        if (!toast) return;

        if (titleEl) titleEl.textContent = title;
        if (msgEl) msgEl.textContent = message;

        toast.classList.add('show');

        if (window.checkoutAlertTimeout) {
            clearTimeout(window.checkoutAlertTimeout);
        }
        window.checkoutAlertTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 5000);
    }

    document.getElementById('checkout-alert-close')?.addEventListener('click', () => {
        document.getElementById('checkout-alert-toast')?.classList.remove('show');
    });

    function validateCheckoutForm() {
        let isValid = true;
        let firstInvalidEl = null;

        const fields = [
            {
                el: document.getElementById('input-nama'),
                err: document.getElementById('err-nama'),
                check: val => val.trim().length >= 3,
                msg: 'Nama lengkap wajib diisi minimal 3 karakter.'
            },
            {
                el: document.getElementById('input-phone'),
                err: document.getElementById('err-phone'),
                check: val => /^[0-9+ -]{9,16}$/.test(val.trim()),
                msg: 'Nomor telepon harus valid (minimal 9 digit).'
            },
            {
                el: document.getElementById('input-alamat'),
                err: document.getElementById('err-alamat'),
                check: val => val.trim().length >= 8,
                msg: 'Alamat lengkap wajib diisi minimal 8 karakter.'
            },
            {
                el: document.getElementById('input-kota'),
                err: document.getElementById('err-kota'),
                check: val => val.trim().length >= 2,
                msg: 'Kota / Kabupaten wajib diisi.'
            },
            {
                el: document.getElementById('input-provinsi'),
                err: document.getElementById('err-provinsi'),
                check: val => val.trim().length >= 2,
                msg: 'Provinsi wajib diisi.'
            },
            {
                el: document.getElementById('input-kodepos'),
                err: document.getElementById('err-kodepos'),
                check: val => /^[0-9]{4,6}$/.test(val.trim()),
                msg: 'Kode pos wajib diisi berupa 4-5 digit angka.'
            }
        ];

        fields.forEach(f => {
            if (!f.el) return;
            const valid = f.check(f.el.value);
            if (!valid) {
                isValid = false;
                f.el.classList.add('is-invalid');
                if (f.err) {
                    f.err.textContent = f.msg;
                    f.err.classList.add('visible');
                }
                if (!firstInvalidEl) {
                    firstInvalidEl = f.el;
                }
            } else {
                f.el.classList.remove('is-invalid');
                if (f.err) f.err.classList.remove('visible');
            }

            // Real-time listener to remove error class when corrected
            f.el.addEventListener('input', function() {
                if (f.check(this.value)) {
                    this.classList.remove('is-invalid');
                    if (f.err) f.err.classList.remove('visible');
                }
            });
        });

        if (!isValid && firstInvalidEl) {
            firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidEl.focus();
        }

        return isValid;
    }

    function renderCheckoutSummary() {
        items = window.SweetDreamsCart ? window.SweetDreamsCart.getCart() : [];

        if (!items || items.length === 0) {
            if (itemsContainer) {
                itemsContainer.innerHTML = `
                    <div style="text-align:center;padding:1.5rem 0.5rem;color:#8a6a72;">
                        <p style="margin-bottom:0.75rem;font-size:0.9rem;">Keranjang belanja Anda masih kosong.</p>
                        <a href="/katalog" style="color:#d44d6e;font-weight:600;text-decoration:none;font-size:0.88rem;">+ Pilih Produk di Katalog</a>
                    </div>
                `;
            }
            if (subtotalEl) subtotalEl.textContent = 'Rp 0';
            if (totalEl) totalEl.textContent = 'Rp 0';
            if (discountEl) discountEl.textContent = '-Rp 0';
            return;
        }

        let html = '';
        let subtotal = 0;

        items.forEach(item => {
            const itemQty = parseInt(item.qty) || 1;
            const itemPrice = parseInt(item.price) || 0;
            const itemSubtotal = itemQty * itemPrice;
            subtotal += itemSubtotal;

            html += `
                <div class="summary-item-row">
                    <div class="summary-item-left">
                        <div class="summary-item-img">
                            <img src="${item.image}" alt="${item.title}">
                        </div>
                        <div>
                            <h4 class="summary-item-title">${item.title}</h4>
                            <p class="summary-item-sub">${item.variant || (item.color + ' · Size ' + item.size)} · Qty: ${itemQty}</p>
                        </div>
                    </div>
                    <span class="summary-item-price">${formatRupiah(itemSubtotal)}</span>
                </div>
            `;
        });

        if (itemsContainer) itemsContainer.innerHTML = html;

        // Subtotal
        if (subtotalEl) subtotalEl.textContent = formatRupiah(subtotal);

        // Shipping
        if (shippingEl) shippingEl.textContent = formatRupiah(currentShippingCost);

        // Voucher Calculation
        let discount = 0;
        if (appliedVoucher === 'SWEETDREAM10') {
            discount = Math.round(subtotal * 0.10);
            if (discountLabel) discountLabel.textContent = 'Promo SWEETDREAM10 (10%)';
        } else if (appliedVoucher === 'SWEETDREAM50') {
            discount = Math.min(50000, subtotal);
            if (discountLabel) discountLabel.textContent = 'Promo SWEETDREAM50';
        } else {
            discount = 0;
            if (discountLabel) discountLabel.textContent = 'Tanpa Voucher';
        }

        if (discountEl) {
            discountEl.textContent = discount > 0 ? `- ${formatRupiah(discount)}` : 'Rp 0';
        }

        // Final total
        let grandTotal = Math.max(0, subtotal + currentShippingCost - discount);
        if (totalEl) totalEl.textContent = formatRupiah(grandTotal);
    }

    // Courier selection
    const shippingCards = document.querySelectorAll('.radio-card-item[data-type="shipping"]');
    shippingCards.forEach(card => {
        card.addEventListener('click', function() {
            shippingCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');

            const courierName = this.getAttribute('data-name');
            const courierCost = parseInt(this.getAttribute('data-cost')) || 0;

            if (courierLabel) {
                if (courierName.includes('SiCepat')) {
                    courierLabel.textContent = 'SiCepat';
                } else if (courierName.includes('JNE')) {
                    courierLabel.textContent = 'JNE';
                } else if (courierName.includes('GoSend')) {
                    courierLabel.textContent = 'GoSend';
                } else {
                    courierLabel.textContent = courierName;
                }
            }

            currentShippingCost = courierCost;
            renderCheckoutSummary();
        });
    });

    // Payment selection
    const paymentCards = document.querySelectorAll('.radio-card-item[data-type="payment"]');
    paymentCards.forEach(card => {
        card.addEventListener('click', function() {
            paymentCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Voucher apply
    const btnVoucher = document.getElementById('btn-checkout-voucher');
    const inputVoucher = document.getElementById('voucher-checkout-input');
    if (btnVoucher && inputVoucher) {
        btnVoucher.addEventListener('click', function() {
            const val = inputVoucher.value.trim().toUpperCase();
            if (val === 'SWEETDREAM10' || val === 'SWEETDREAM50') {
                appliedVoucher = val;
                btnVoucher.textContent = 'Terpasang';
                btnVoucher.style.background = '#10b981';
            } else if (val === '') {
                appliedVoucher = '';
                btnVoucher.textContent = 'Terapkan';
                btnVoucher.style.background = '#d44d6e';
            } else {
                alert('Voucher ' + val + ' tidak valid.');
                appliedVoucher = '';
                btnVoucher.textContent = 'Terapkan';
                btnVoucher.style.background = '#d44d6e';
            }
            renderCheckoutSummary();
        });
    }

    // Pay Now Modal & Form Validation
    const modal = document.getElementById('order-modal');

    if (btnPayNow && modal) {
        btnPayNow.addEventListener('click', function(e) {
            e.preventDefault();

            // 1. Cek isi keranjang belanja
            const currentCart = window.SweetDreamsCart ? window.SweetDreamsCart.getCart() : [];
            if (!currentCart || currentCart.length === 0) {
                showCheckoutAlert(
                    'Keranjang Belanja Masih Kosong',
                    'Anda belum memiliki item di keranjang belanja. Silakan pilih produk dari katalog terlebih dahulu!'
                );
                return;
            }

            // 2. Cek validasi kelengkapan data pengiriman
            const isFormValid = validateCheckoutForm();
            if (!isFormValid) {
                showCheckoutAlert(
                    'Data Pengiriman Belum Lengkap',
                    'Harap lengkapi semua kolom bertanda bintang (*) dengan data yang sesuai sebelum melakukan pembayaran.'
                );
                return;
            }

            // 3. Jika valid, proses pembayaran
            const namaInput = document.getElementById('input-nama').value.trim();
            const nameDisplay = document.getElementById('customer-name-display');
            if (nameDisplay) {
                nameDisplay.textContent = namaInput;
            }

            // Generate realistic invoice number
            const randId = Math.floor(100 + Math.random() * 900);
            const invoiceDisplay = document.getElementById('invoice-display');
            if (invoiceDisplay) {
                invoiceDisplay.textContent = `INVOICE: #SD-20240908-${randId}`;
            }

            // Clear cart completely
            if (window.SweetDreamsCart) {
                window.SweetDreamsCart.clearCart();
            }

            modal.classList.add('open');
        });
    }

    // Initial render
    renderCheckoutSummary();
});
</script>
@endsection
