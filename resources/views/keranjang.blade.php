@extends('layouts.app')

@section('title', 'Keranjang Belanja - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/keranjang.css')
@endpush

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
        <div>
            <div id="cart-select-all-container" style="display:none; margin-bottom: 1rem; align-items: center; gap: 0.5rem;">
                <input type="checkbox" id="cart-select-all" checked style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--blush);">
                <label for="cart-select-all" style="font-weight: 600; cursor: pointer; color:var(--ink);">Pilih Semua</label>
            </div>
            <div class="cart-items-list" id="cart-items-container">
                {{-- Content will be rendered dynamically by JavaScript --}}
            </div>
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
                    <input type="text" id="input-voucher" value="" placeholder="MASUKKAN KODE">
                    <button class="btn-apply-voucher" id="btn-apply-voucher">Terapkan</button>
                </div>
                <div class="voucher-msg" id="voucher-msg"></div>
            </div>

            <hr class="summary-total-divider">

            <div class="summary-total-row">
                <span class="total-label">Total Akhir</span>
                <span class="total-price" id="summary-total">Rp0</span>
            </div>

            <a href="#" class="btn-checkout" id="btn-checkout" style="text-decoration: none;">
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
 window.selectedItemIds = new Set(window.initialCartItems.map(i => i.id));
document.addEventListener('DOMContentLoaded', function() {
    let discountAmount = 0;
    let isVoucherApplied = false;

    const subtotalEl = document.getElementById('summary-subtotal');
    const shippingEl = document.getElementById('summary-shipping');
    const discountEl = document.getElementById('summary-discount');
    const totalEl = document.getElementById('summary-total');
    const subtitleEl = document.getElementById('cart-count-subtitle');
    const itemsContainer = document.getElementById('cart-items-container');
    const selectAllContainer = document.getElementById('cart-select-all-container');
    const selectAllCheckbox = document.getElementById('cart-select-all');
    const emptyState = document.getElementById('cart-empty-state');
    const summaryCard = document.getElementById('order-summary-card');
    const announcementBar = document.getElementById('cart-announcement');
    const btnCheckout = document.getElementById('btn-checkout');

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
            if (selectAllContainer) selectAllContainer.style.display = 'none';
            if (summaryCard) summaryCard.style.display = 'none';
            if (emptyState) emptyState.style.display = 'block';
            if (subtitleEl) subtitleEl.textContent = '0 items in your cart';
            if (announcementBar) announcementBar.textContent = 'FREE SHIPPING ON ORDERS OVER RP500.000 ✨';
            lucide.createIcons();
            return;
        }

        if (emptyState) emptyState.style.display = 'none';
        if (itemsContainer) itemsContainer.style.display = 'flex';
        if (selectAllContainer) selectAllContainer.style.display = 'flex';
        if (summaryCard) summaryCard.style.display = 'block';

        let html = '';
        let subtotal = 0;
        let totalCount = 0;

        items.forEach(item => {
            const itemQty = parseInt(item.qty) || 1;
            const itemPrice = parseInt(item.price) || 0;
            const itemSubtotal = itemQty * itemPrice;
            const isSelected = window.selectedItemIds.has(item.id);
            
            if (isSelected) {
                subtotal += itemSubtotal;
                totalCount += itemQty;
            }

            html += `
                <div class="cart-item-row" id="cart-row-${item.id}" data-id="${item.id}">
                    <div class="cart-item-info">
                        <input type="checkbox" class="cart-item-checkbox" data-id="${item.id}" ${isSelected ? 'checked' : ''} style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--blush);">
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

        // Update Select All Checkbox state
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = items.length > 0 && window.selectedItemIds.size === items.length;
        }

        // Checkbox listeners
        itemsContainer.querySelectorAll('.cart-item-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                const id = parseInt(this.getAttribute('data-id'));
                if (this.checked) {
                    window.selectedItemIds.add(id);
                } else {
                    window.selectedItemIds.delete(id);
                }
                renderCart();
            });
        });

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
                }).then(() => {
                    window.selectedItemIds.delete(parseInt(id));
                    setTimeout(() => window.location.reload(), 250);
                });
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

    // Select All functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            if (this.checked) {
                window.initialCartItems.forEach(i => window.selectedItemIds.add(i.id));
            } else {
                window.selectedItemIds.clear();
            }
            renderCart();
        });
    }

    if (btnCheckout) {
        btnCheckout.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.selectedItemIds.size === 0) {
                alert('Pilih minimal satu produk untuk di-checkout.');
                return;
            }
            window.location.href = '/checkout?items=' + Array.from(window.selectedItemIds).join(',');
        });
    }

    // Listen to external cart update events
    window.addEventListener('sweetdreams_cart_updated', renderCart);

    // Initial render
    renderCart();
});
</script>
@endsection
