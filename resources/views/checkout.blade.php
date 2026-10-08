@extends('layouts.app')

@section('title', 'Checkout - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/checkout.css')
@endpush

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
                        <label for="select-provinsi">Provinsi <span class="required">*</span></label>
                        <select id="select-provinsi" class="form-control" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @if(!empty($provinces))
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov['id'] }}">{{ $prov['name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        <input type="hidden" id="input-provinsi" value="">
                        <input type="hidden" id="input-provinsi-id" value="">
                        <span class="field-error-msg" id="err-provinsi">Provinsi wajib dipilih.</span>
                    </div>
                    <div class="form-group">
                        <label for="select-kota">Kota / Kabupaten <span class="required">*</span></label>
                        <select id="select-kota" class="form-control" required disabled>
                            <option value="">-- Pilih Provinsi Dahulu --</option>
                        </select>
                        <input type="hidden" id="input-kota" value="">
                        <input type="hidden" id="input-kota-id" value="">
                        <span class="field-error-msg" id="err-kota">Kota / Kabupaten wajib dipilih.</span>
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
                    <span class="shipping-rate-status" id="rajaongkir-badge-status">
                        <i data-lucide="truck" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 3px; color: var(--blush);"></i>
                        Pilih kota/kabupaten
                    </span>
                </div>

                <div class="radio-card-list" id="courier-list-container">
                    <div class="shipping-rate-placeholder">Pilih provinsi dan kota/kabupaten untuk melihat tarif pengiriman.</div>
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
                    <input type="text" id="voucher-checkout-input" value="" placeholder="KODE VOUCHER">
                    <button class="btn-summary-voucher" id="btn-checkout-voucher">Terapkan</button>
                </div>

                {{-- Cost Breakdown --}}
                <div class="cost-row">
                    <span>Subtotal</span>
                    <span class="cost-val" id="checkout-subtotal">Rp 0</span>
                </div>

                <div class="cost-row">
                    <span>Biaya Pengiriman (<span id="summary-courier-label">Belum dipilih</span>)</span>
                    <span class="cost-val" id="checkout-shipping">Pilih kota/kabupaten</span>
                </div>

                <div class="cost-row" id="row-checkout-discount">
                    <span>Diskon (<span id="discount-label">Promo SWEETDREAM10</span>)</span>
                    <span class="cost-val discount" id="checkout-discount">-Rp 0</span>
                </div>

                <hr class="cost-divider">

                <div class="total-cost-row">
                    <span class="total-cost-label">Total Pembayaran</span>
                    <span class="total-cost-val" id="checkout-total">Menunggu ongkir</span>
                </div>

                <button class="btn-pay-now" id="btn-pay-now" disabled>
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

{{-- ORDER CONFIRMATION & PAYMENT MODAL --}}
<div class="order-success-modal" id="order-modal">
    <div class="success-modal-card">
        <div class="success-icon-badge" id="modal-icon-badge">
            <i data-lucide="check-circle" style="width:40px;height:40px;"></i>
        </div>
        <h3 class="success-title" id="modal-title">Pesanan Berhasil Dibuat!</h3>
        <span class="success-invoice-id" id="invoice-display">INVOICE: #SD-20240908-01</span>
        
        <p class="success-desc" id="modal-desc-text">
            Terima kasih, <strong id="customer-name-display">Pelanggan</strong>! Pesananmu telah tercatat dan siap diproses.
        </p>

        {{-- Total Tagihan Box --}}
        <div class="modal-amount-display">
            <span class="amount-label">Total Tagihan:</span>
            <span class="amount-val" id="modal-total-amount">Rp 0</span>
        </div>

        {{-- Virtual Account Box (Aktif jika bayar via Bank Transfer / VA) --}}
        <div class="va-payment-box" id="va-box" style="display:none;">
            <div class="va-header">
                <span class="va-bank-badge" id="va-bank-badge">BCA Virtual Account</span>
                <span class="va-status-tag">Menunggu Pembayaran</span>
            </div>
            <div class="va-number-wrapper">
                <span class="va-number-text" id="va-number-display">0000000000</span>
                <button type="button" class="btn-copy-va" id="btn-copy-va" title="Salin Nomor VA">
                    <i data-lucide="copy" style="width:16px;height:16px;"></i>
                    <span id="copy-va-text">Salin</span>
                </button>
            </div>
            <p class="va-note">Transfer tepat sejumlah total tagihan di atas agar otomatis terverifikasi sistem.</p>
        </div>

        {{-- Payment Gateway Actions --}}
        <div class="payment-actions-group">
            <a href="#" target="_blank" id="btn-open-payment-url" class="btn-home-return btn-pay-gateway" style="display:none;">
                <i data-lucide="external-link" style="width:18px;height:18px;"></i>
                Buka Halaman Pembayaran (Komerce Pay)
            </a>

            <button type="button" id="btn-check-payment-status" class="btn-home-return" style="background:#fce7ee;color:var(--blush);border:1px solid #fbd5df;cursor:pointer;">
                <i data-lucide="refresh-cw" style="width:16px;height:16px;" id="check-status-icon"></i>
                <span id="check-status-text">Cek Status Pembayaran</span>
            </button>

            <a href="#" id="btn-view-order" class="btn-home-return" style="background:transparent;color:var(--ink);border:1px solid #e5d7dc;margin-top:0.35rem;">
                Lihat Detail Pesanan
                <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
            </a>
            
            <a href="/" class="btn-home-return" style="background:transparent;color:var(--ink-muted);border:none;margin-top:-0.25rem;font-size:0.85rem;">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide Icons
    lucide.createIcons();

        let items = @json($checkoutItems);
    let currentShippingCost = null;
    let shippingRatesReady = false;
    let shippingRateRequest = 0;
    let appliedVoucher = '';

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

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
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

    
    // Auto prefill shipping details dari alamat tersimpan di database
    const savedAddresses = @json($addresses);
    const primaryAddr = savedAddresses.find(a => a.is_primary) || savedAddresses[0];

    if (primaryAddr) {
        const inputNama = document.getElementById('input-nama');
        const inputPhone = document.getElementById('input-phone');
        const inputAlamat = document.getElementById('input-alamat');
        const inputKota = document.getElementById('input-kota');
        const inputKotaId = document.getElementById('input-kota-id');
        const inputProvinsi = document.getElementById('input-provinsi');
        const inputProvinsiId = document.getElementById('input-provinsi-id');
        const inputKodepos = document.getElementById('input-kodepos');

        if (inputNama && !inputNama.value) inputNama.value = primaryAddr.name || '';
        if (inputPhone && !inputPhone.value) inputPhone.value = primaryAddr.phone || '';
        if (inputAlamat && !inputAlamat.value) inputAlamat.value = primaryAddr.address || '';
        if (inputKota && !inputKota.value) inputKota.value = primaryAddr.city || '';
        if (inputKotaId && !inputKotaId.value) inputKotaId.value = primaryAddr.city_id || '';
        if (inputProvinsi && !inputProvinsi.value) inputProvinsi.value = primaryAddr.province || '';
        if (inputProvinsiId && !inputProvinsiId.value) inputProvinsiId.value = primaryAddr.province_id || '';
        if (inputKodepos && !inputKodepos.value) inputKodepos.value = primaryAddr.postal_code || '';
    }

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
                el: document.getElementById('input-provinsi'),
                focusEl: document.getElementById('select-provinsi'),
                err: document.getElementById('err-provinsi'),
                check: val => val.trim().length >= 2,
                msg: 'Provinsi wajib dipilih.'
            },
            {
                el: document.getElementById('input-kota'),
                focusEl: document.getElementById('select-kota'),
                err: document.getElementById('err-kota'),
                check: val => val.trim().length >= 2,
                msg: 'Kota / Kabupaten wajib dipilih.'
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
            const targetEl = f.focusEl || f.el;
            const valid = f.check(f.el.value);
            if (!valid) {
                isValid = false;
                targetEl.classList.add('is-invalid');
                if (f.err) {
                    f.err.textContent = f.msg;
                    f.err.classList.add('visible');
                }
                if (!firstInvalidEl) {
                    firstInvalidEl = targetEl;
                }
            } else {
                targetEl.classList.remove('is-invalid');
                if (f.err) f.err.classList.remove('visible');
            }

            // Real-time listener to remove error class when corrected
            const listenerEl = f.focusEl || f.el;
            listenerEl.addEventListener('input', function() {
                if (f.check(f.el.value)) {
                    listenerEl.classList.remove('is-invalid');
                    if (f.err) f.err.classList.remove('visible');
                }
            });
            if (f.focusEl) {
                f.focusEl.addEventListener('change', function() {
                    if (f.check(f.el.value)) {
                        f.focusEl.classList.remove('is-invalid');
                        if (f.err) f.err.classList.remove('visible');
                    }
                });
            }
        });

        if (!isValid && firstInvalidEl) {
            firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidEl.focus();
        }

        return isValid;
    }

    function renderCheckoutSummary() {
       

        if (!items || items.length === 0) {
            if (itemsContainer) {
                itemsContainer.innerHTML = `
                    <div style="text-align:center;padding:1.5rem 0.5rem;color:#8a6a72;">
                        <p style="margin-bottom:0.75rem;font-size:0.9rem;">Keranjang belanja Anda masih kosong.</p>
                        <a href="/katalog" style="color:var(--blush);font-weight:600;text-decoration:none;font-size:0.88rem;">+ Pilih Produk di Katalog</a>
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
        if (shippingEl) {
            shippingEl.textContent = currentShippingCost === null
                ? 'Pilih kota/kabupaten'
                : formatRupiah(currentShippingCost);
        }

        // Voucher Calculation
        let discount = 0;
        if (appliedVoucher && window._appliedVoucherLabel) {
            if (discountLabel) discountLabel.textContent = `Promo ${window._appliedVoucherLabel}`;
        } else {
            discount = 0;
            if (discountLabel) discountLabel.textContent = 'Tanpa Voucher';
        }

        if (discountEl) {
            discountEl.textContent = discount > 0 ? `- ${formatRupiah(discount)}` : 'Rp 0';
        }

        // Final total
        let grandTotal = Math.max(0, subtotal + (currentShippingCost || 0) - discount);
        if (totalEl) {
            totalEl.textContent = shippingRatesReady ? formatRupiah(grandTotal) : 'Menunggu ongkir';
        }
        if (btnPayNow) btnPayNow.disabled = !shippingRatesReady;
    }

    // ===== RAJAONGKIR PROVINCES, CITIES & SHIPPING CALCULATION =====
    const selectProvinsi = document.getElementById('select-provinsi');
    const selectKota = document.getElementById('select-kota');
    const inputProvinsi = document.getElementById('input-provinsi');
    const inputProvinsiId = document.getElementById('input-provinsi-id');
    const inputKota = document.getElementById('input-kota');
    const inputKotaId = document.getElementById('input-kota-id');
    const courierContainer = document.getElementById('courier-list-container');
    const shippingStatus = document.getElementById('rajaongkir-badge-status');

    let allProvinces = [];

    function setShippingStatus(text, type = '') {
        if (!shippingStatus) return;
        shippingStatus.className = `shipping-rate-status${type ? ` ${type}` : ''}`;
        shippingStatus.textContent = text;
    }

    function resetShippingRates(message, statusText = 'Pilih kota/kabupaten') {
        shippingRateRequest++;
        currentShippingCost = null;
        shippingRatesReady = false;
        if (courierContainer) {
            courierContainer.innerHTML = `<div class="shipping-rate-placeholder">${escapeHtml(message)}</div>`;
        }
        if (courierLabel) courierLabel.textContent = 'Belum dipilih';
        setShippingStatus(statusText);
        renderCheckoutSummary();
    }

    function bindCourierSelection() {
        const shippingCards = document.querySelectorAll('.radio-card-item[data-type="shipping"]');
        shippingCards.forEach(card => {
            card.addEventListener('click', function() {
                shippingCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const courierName = this.getAttribute('data-name');
                const courierCost = parseInt(this.getAttribute('data-cost')) || 0;

                if (courierLabel) {
                    courierLabel.textContent = courierName;
                }

                currentShippingCost = courierCost;
                shippingRatesReady = true;
                if (btnPayNow) btnPayNow.disabled = false;
                renderCheckoutSummary();
            });
        });
    }

    function renderCourierCards(services, source) {
        if (!courierContainer || !services || services.length === 0) return;

        const isEstimate = source !== 'live_api' && source !== 'live_cache';
        setShippingStatus(
            isEstimate ? 'Estimasi zona (bukan tarif live)' : 'Tarif berdasarkan kota dari RajaOngkir',
            isEstimate ? 'is-estimate' : 'is-live'
        );

        let html = '';
        services.forEach((s, idx) => {
            const isFirst = idx === 0;
            const courierCode = (s.courier || '').toLowerCase();
            const badgeClass = courierCode.includes('jne') ? 'jne' 
                : (courierCode.includes('pos') ? 'pos' 
                : (courierCode.includes('j&t') || courierCode.includes('jnt') ? 'jnt' 
                : (courierCode.includes('lion') ? 'lion' 
                : (courierCode.includes('sap') ? 'sap' 
                : (courierCode.includes('spx') || courierCode.includes('shopee') ? 'spx' 
                : (courierCode.includes('sicepat') ? 'sicepat' : ''))))));
            
            html += `
                <div class="radio-card-item ${isFirst ? 'active' : ''}"
                     data-type="shipping"
                     data-id="${s.id}"
                     data-name="${escapeHtml(s.name)}"
                     data-cost="${s.cost}">
                    <div class="radio-card-left">
                        <div class="custom-radio-circle"></div>
                        <div class="radio-card-text">
                            <span class="radio-card-title">
                                ${escapeHtml(s.name)}
                                <span class="shipping-courier-tag ${badgeClass}">${escapeHtml(s.courier)}</span>
                            </span>
                            <span class="radio-card-desc">${escapeHtml(s.description || '')}</span>
                        </div>
                    </div>
                    <span class="radio-card-price">${formatRupiah(s.cost)}</span>
                </div>
            `;
        });

        courierContainer.innerHTML = html;
        bindCourierSelection();

        // Auto select first courier
        const first = services[0];
        if (first) {
            currentShippingCost = first.cost;
            shippingRatesReady = true;
            if (btnPayNow) btnPayNow.disabled = false;
            if (courierLabel) courierLabel.textContent = first.name;
            renderCheckoutSummary();
        }
    }

    function fetchShippingRates(cityId, cityName, provId = null) {
        if (!courierContainer) return;
        const requestId = ++shippingRateRequest;
        currentShippingCost = null;
        shippingRatesReady = false;
        if (btnPayNow) btnPayNow.disabled = true;
        if (courierLabel) courierLabel.textContent = 'Menghitung...';
        setShippingStatus('Menghitung tarif kota/kabupaten...');
        renderCheckoutSummary();

        courierContainer.innerHTML = `
            <div class="shipping-loading-indicator">
                <div class="spinner-sm" style="width:20px;height:20px;border:2.5px solid #fbd5df;border-top-color:#d44d6e;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                <span>Mencari tarif untuk kota/kabupaten yang dipilih...</span>
            </div>
        `;

        const totalWeight = Math.max(500, items.reduce((sum, item) => sum + (item.qty * 250), 0));
        const effectiveProvId = provId || (selectProvinsi ? selectProvinsi.value : null);

        fetch('/api/shipping/cost', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                destination_city_id: cityId || null,
                city: cityName || null,
                province_id: effectiveProvId || null,
                weight: totalWeight
            })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Tarif pengiriman tidak dapat dimuat.');
            return data;
        })
        .then(data => {
            if (requestId !== shippingRateRequest) return;
            if (data && data.services && data.services.length > 0) {
                renderCourierCards(data.services, data.source);
            } else {
                resetShippingRates(
                    data.message || 'Tarif tidak tersedia untuk tujuan ini. Coba pilih kota/kabupaten lain atau hubungi admin.',
                    'Tarif tidak tersedia'
                );
            }
        })
        .catch(err => {
            if (requestId !== shippingRateRequest) return;
            console.error('Error fetching shipping rates:', err);
            resetShippingRates('Tarif gagal dimuat. Periksa koneksi lalu pilih ulang kota/kabupaten.', 'Gagal memuat tarif');
        });
    }

    function loadCitiesForProvince(provId, selectedCityIdOrName = null) {
        if (!selectKota) return;
        resetShippingRates(
            provId
                ? 'Pilih kota/kabupaten untuk melihat tarif pengiriman.'
                : 'Pilih provinsi dan kota/kabupaten untuk melihat tarif pengiriman.'
        );
        selectKota.innerHTML = '<option value="">-- Memuat Kota / Kabupaten... --</option>';
        selectKota.disabled = true;

        if (!provId) {
            selectKota.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
            return;
        }

        fetch(`/api/shipping/cities?province_id=${provId}`)
            .then(res => {
                if (!res.ok) throw new Error('HTTP error ' + res.status);
                return res.json();
            })
            .then(cities => {
                if (!Array.isArray(cities)) {
                    console.error('Invalid cities response, expected array:', cities);
                    throw new Error('Data kota tidak valid');
                }

                if (cities.length === 0) {
                    selectKota.innerHTML = '<option value="">-- Tidak ada data kota --</option>';
                    selectKota.disabled = false;
                    return;
                }

                let options = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                let matchedOption = null;

                cities.forEach(c => {
                    const isSelected = selectedCityIdOrName && (
                        String(c.id) === String(selectedCityIdOrName) ||
                        c.name.toLowerCase() === String(selectedCityIdOrName).toLowerCase() ||
                        c.name.toLowerCase().includes(String(selectedCityIdOrName).toLowerCase())
                    );
                    if (isSelected && !matchedOption) matchedOption = c;
                    options += `<option value="${c.id}" ${isSelected ? 'selected' : ''}>${escapeHtml(c.name)}</option>`;
                });

                selectKota.innerHTML = options;
                selectKota.disabled = false;

                if (matchedOption) {
                    selectKota.value = matchedOption.id;
                    inputKota.value = matchedOption.name;
                    inputKotaId.value = matchedOption.id;
                    fetchShippingRates(matchedOption.id, matchedOption.name, provId);
                }
            })
            .catch(err => {
                console.error('Error loading cities:', err);
                selectKota.innerHTML = '<option value="">-- Gagal Memuat Kota (Klik untuk coba lagi) --</option>';
                selectKota.disabled = false;
            });
    }

    if (selectProvinsi) {
        selectProvinsi.addEventListener('change', function() {
            const provId = this.value;
            const provName = this.options[this.selectedIndex]?.text || '';
            inputProvinsi.value = provId ? provName : '';
            inputProvinsiId.value = provId || '';

            inputKota.value = '';
            inputKotaId.value = '';
            loadCitiesForProvince(provId);

        });
    }

    if (selectKota) {
        selectKota.addEventListener('change', function() {
            const cityId = this.value;
            const cityName = this.options[this.selectedIndex]?.text || '';
            inputKota.value = cityId ? cityName : '';
            inputKotaId.value = cityId || '';

            if (cityId) {
                fetchShippingRates(cityId, cityName, selectProvinsi ? selectProvinsi.value : null);
            } else {
                resetShippingRates('Pilih kota/kabupaten untuk melihat tarif pengiriman.');
            }
        });

        selectKota.addEventListener('click', function() {
            if (!this.value && this.options[0]?.text?.includes('Gagal') && selectProvinsi && selectProvinsi.value) {
                loadCitiesForProvince(selectProvinsi.value);
            }
        });
    }

    // Load initial provinces and prefill address
    const STATIC_PROVINCES = @json($provinces ?? \App\Http\Controllers\ShippingController::staticProvinces());

    function initProvinces(provincesList) {
        allProvinces = (provincesList && provincesList.length > 0) ? provincesList : STATIC_PROVINCES;

        if (selectProvinsi && selectProvinsi.options.length <= 1) {
            let options = '<option value="">-- Pilih Provinsi --</option>';
            allProvinces.forEach(p => {
                options += `<option value="${p.id}">${escapeHtml(p.name)}</option>`;
            });
            selectProvinsi.innerHTML = options;
        }

        if (primaryAddr && selectProvinsi) {
            let matchedProv = null;
            allProvinces.forEach(p => {
                const isSelected = (primaryAddr.province_id && String(p.id) === String(primaryAddr.province_id)) ||
                    (primaryAddr.province && p.name.toLowerCase().includes(primaryAddr.province.toLowerCase()));
                if (isSelected && !matchedProv) matchedProv = p;
            });

            if (matchedProv) {
                selectProvinsi.value = matchedProv.id;
                inputProvinsi.value = matchedProv.name;
                inputProvinsiId.value = matchedProv.id;
                loadCitiesForProvince(matchedProv.id, primaryAddr.city_id || primaryAddr.city);
            } else if (primaryAddr.city_id || primaryAddr.city) {
                fetchShippingRates(primaryAddr.city_id || null, primaryAddr.city || null);
            }
        }
    }

    initProvinces(STATIC_PROVINCES);

    fetch('/api/shipping/provinces')
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                allProvinces = data;
            }
        })
        .catch(() => {});

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
            const code = inputVoucher.value.trim();
            if (!code) return;

            fetch('/api/checkout/validate-voucher', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ code: code, subtotal: items.reduce((sum, i) => sum + (i.price * i.qty), 0) })
            })
            .then(res => res.json())
            .then(data => {
                if (data.valid) {
                    appliedVoucher = code;
                    window._appliedVoucherLabel = data.label;
                } else {
                    appliedVoucher = '';
                    alert(data.message);
                }
                renderCheckoutSummary();
            });
        });
    }

        // Pay Now Modal & Form Validation
    const modal = document.getElementById('order-modal');

    if (btnPayNow && modal) {
        btnPayNow.addEventListener('click', function(e) {
            e.preventDefault();

            // 1. Cek validasi kelengkapan data pengiriman
            const isFormValid = validateCheckoutForm();
            if (!isFormValid) {
                showCheckoutAlert(
                    'Data Pengiriman Belum Lengkap',
                    'Harap lengkapi semua kolom bertanda bintang (*) dengan data yang sesuai sebelum melakukan pembayaran.'
                );
                return;
            }

                        // 2. Ambil metode pengiriman & pembayaran yang lagi dipilih
            const selectedShipping = document.querySelector('.radio-card-item[data-type="shipping"].active');
            const selectedPayment = document.querySelector('.radio-card-item[data-type="payment"].active');

            btnPayNow.disabled = true;
            btnPayNow.textContent = 'Memproses...';

            // 3. Kirim pesanan ke server
            fetch('/api/checkout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: document.getElementById('input-nama').value.trim(),
                    phone: document.getElementById('input-phone').value.trim(),
                    address: document.getElementById('input-alamat').value.trim(),
                    city: document.getElementById('input-kota').value.trim(),
                    city_id: document.getElementById('input-kota-id')?.value || null,
                    province: document.getElementById('input-provinsi').value.trim(),
                    province_id: document.getElementById('input-provinsi-id')?.value || null,
                    postal_code: document.getElementById('input-kodepos').value.trim(),
                    shipping_id: selectedShipping ? selectedShipping.getAttribute('data-id') : null,
                    shipping_courier: selectedShipping ? selectedShipping.getAttribute('data-name') : null,
                    shipping_cost: selectedShipping ? (parseInt(selectedShipping.getAttribute('data-cost')) || 0) : 0,
                    payment_id: selectedPayment ? selectedPayment.getAttribute('data-id') : null,
                    voucher: appliedVoucher || null,
                    item_ids: items.map(i => i.id)
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                btnPayNow.disabled = false;
                btnPayNow.textContent = 'Bayar Sekarang';

                if (status === 201) {
                    const nameDisplay = document.getElementById('customer-name-display');
                    if (nameDisplay) nameDisplay.textContent = document.getElementById('input-nama').value.trim();

                    const invoiceDisplay = document.getElementById('invoice-display');
                    if (invoiceDisplay) invoiceDisplay.textContent = `INVOICE: #${body.order_number}`;

                    const modalTotalAmount = document.getElementById('modal-total-amount');
                    if (modalTotalAmount) modalTotalAmount.textContent = formatRupiah(body.total || 0);

                    const viewOrderBtn = document.getElementById('btn-view-order');
                    if (viewOrderBtn) viewOrderBtn.href = `/pesanan/${body.order_number}`;

                    // Tampilkan Virtual Account jika ada
                    const vaBox = document.getElementById('va-box');
                    const vaNumberDisplay = document.getElementById('va-number-display');
                    const vaBankBadge = document.getElementById('va-bank-badge');
                    const btnCopyVa = document.getElementById('btn-copy-va');
                    const copyVaText = document.getElementById('copy-va-text');

                    if (body.va_number && vaBox && vaNumberDisplay) {
                        vaBox.style.display = 'block';
                        vaNumberDisplay.textContent = body.va_number;
                        if (vaBankBadge) vaBankBadge.textContent = (body.bank_code || 'Bank') + ' Virtual Account';

                        if (btnCopyVa) {
                            btnCopyVa.onclick = function() {
                                navigator.clipboard.writeText(body.va_number).then(() => {
                                    if (copyVaText) copyVaText.textContent = 'Tersalin!';
                                    setTimeout(() => { if (copyVaText) copyVaText.textContent = 'Salin'; }, 2000);
                                });
                            };
                        }
                    } else if (vaBox) {
                        vaBox.style.display = 'none';
                    }

                    // Tampilkan tombol buka Payment Page jika ada payment_url
                    const btnOpenPay = document.getElementById('btn-open-payment-url');
                    if (btnOpenPay) {
                        if (body.payment_url) {
                            btnOpenPay.style.display = 'inline-flex';
                            btnOpenPay.href = body.payment_url;
                        } else {
                            btnOpenPay.style.display = 'none';
                        }
                    }

                    // Setup tombol Cek Status Pembayaran
                    const btnCheckStatus = document.getElementById('btn-check-payment-status');
                    const checkStatusText = document.getElementById('check-status-text');
                    const checkStatusIcon = document.getElementById('check-status-icon');

                    if (btnCheckStatus) {
                        btnCheckStatus.onclick = function() {
                            btnCheckStatus.disabled = true;
                            if (checkStatusText) checkStatusText.textContent = 'Mengecek...';
                            if (checkStatusIcon) checkStatusIcon.style.animation = 'spin 0.8s linear infinite';

                            fetch(`/api/payment/status/${body.order_number}`)
                                .then(r => r.json())
                                .then(res => {
                                    btnCheckStatus.disabled = false;
                                    if (checkStatusIcon) checkStatusIcon.style.animation = 'none';

                                    if (res.payment_status === 'paid') {
                                        if (checkStatusText) checkStatusText.textContent = 'Pembayaran Lunas!';
                                        btnCheckStatus.style.background = '#d4edda';
                                        btnCheckStatus.style.color = '#155724';
                                        btnCheckStatus.style.borderColor = '#c3e6cb';
                                        setTimeout(() => {
                                            window.location.href = `/pesanan/${body.order_number}`;
                                        }, 1000);
                                    } else {
                                        if (checkStatusText) checkStatusText.textContent = 'Cek Status Pembayaran';
                                        showCheckoutAlert('Status Pembayaran', 'Pembayaran belum terdeteksi. Silakan lakukan pembayaran terlebih dahulu.');
                                    }
                                })
                                .catch(() => {
                                    btnCheckStatus.disabled = false;
                                    if (checkStatusText) checkStatusText.textContent = 'Cek Status Pembayaran';
                                    if (checkStatusIcon) checkStatusIcon.style.animation = 'none';
                                    showCheckoutAlert('Koneksi', 'Gagal memeriksa status pembayaran.');
                                });
                        };
                    }

                    lucide.createIcons();
                    modal.classList.add('open');
                } else {
                    if (body.redirect_url) {
                        window.location.assign(body.redirect_url);
                        return;
                    }

                    showCheckoutAlert(
                        'Gagal Membuat Pesanan',
                        body.message || 'Terjadi kesalahan, silakan coba lagi.'
                    );
                }
            })
            .catch(() => {
                btnPayNow.disabled = false;
                btnPayNow.textContent = 'Bayar Sekarang';
                showCheckoutAlert('Gagal Membuat Pesanan', 'Tidak bisa menghubungi server, coba lagi.');
            });
        });
    }

    // Initial render
    renderCheckoutSummary();
});
</script>
@endsection
