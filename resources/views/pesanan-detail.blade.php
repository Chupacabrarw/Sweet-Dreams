@extends('layouts.app')

@section('title', 'Tracking Pesanan ' . $order['order_id'] . ' - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/pesanan-detail.css')
@endpush

<div class="tracking-page">
    {{-- Back Link --}}
    <a href="/profil" class="tracking-back-link" id="btn-back-to-profil">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
        Kembali ke Akun Saya
    </a>

    {{-- Header --}}
    <div class="tracking-header">
        <span class="tracking-eyebrow">PESANAN {{ $order['order_id'] }}</span>
        <h1>{{ $order['headline'] }}</h1>
        <p>{{ $order['subtitle'] }}</p>
    </div>

    {{-- Payment Reminder Banner if Unpaid --}}
    @if(($order['payment_status'] ?? '') === 'unpaid')
        <div class="unpaid-payment-alert" style="background:#fff7f8;border:1.5px solid #f8c9d6;border-radius:18px;padding:1.5rem 1.75rem;margin-bottom:1.75rem;box-shadow:0 4px 20px rgba(224,107,136,0.08);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1.25rem;flex-wrap:wrap;">
                <div style="flex:1;min-width:280px;">
                    <span style="font-size:0.75rem;font-weight:700;letter-spacing:0.08em;color:#e06b88;text-transform:uppercase;">Menunggu Pembayaran</span>
                    <h3 style="font-family:var(--font-body);font-size:1.15rem;font-weight:700;color:#2a1f22;margin:0.25rem 0 0.5rem;">Selesaikan Pembayaran Pesananmu</h3>
                    <p style="font-size:0.88rem;color:#7a5961;margin:0;line-height:1.5;">Total tagihan: <strong style="color:#2a1f22;font-size:1rem;">{{ $order['summary']['total'] }}</strong></p>
                    
                    @if(!empty($order['va_number']))
                        <div style="margin-top:0.85rem;background:#ffffff;border:1px solid #ebd3da;border-radius:10px;padding:0.6rem 1rem;display:inline-flex;align-items:center;gap:1rem;">
                            <div>
                                <span style="font-size:0.72rem;color:#8c7379;display:block;">Nomor Virtual Account ({{ strtoupper($order['payment_method'] ?? 'VA') }}):</span>
                                <strong style="font-family:'DM Mono',monospace;font-size:1.15rem;color:#2a1f22;letter-spacing:0.05em;" id="detail-va-num">{{ $order['va_number'] }}</strong>
                            </div>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ $order['va_number'] }}'); this.textContent = 'Tersalin!'; setTimeout(() => this.textContent = 'Salin', 2000);" style="background:#fce7ee;border:none;color:#e06b88;font-weight:700;font-size:0.78rem;padding:6px 14px;border-radius:6px;cursor:pointer;">
                                Salin
                            </button>
                        </div>
                    @endif
                </div>

                <div style="display:flex;flex-direction:column;gap:0.5rem;align-self:center;">
                    @if(!empty($order['payment_url']))
                        <a href="{{ $order['payment_url'] }}" target="_blank" style="display:inline-flex;align-items:center;justify-content:center;gap:0.4rem;background:#e06b88;color:#ffffff;font-weight:700;font-size:0.88rem;padding:10px 24px;border-radius:8px;text-decoration:none;box-shadow:0 3px 10px rgba(224,107,136,0.3);">
                            Bayar Sekarang (Komerce)
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                        </a>
                    @endif

                    <button type="button" id="btn-sync-payment" onclick="checkLivePayment('{{ $order['raw_order_number'] ?? '' }}')" style="display:inline-flex;align-items:center;justify-content:center;gap:0.4rem;background:#ffffff;border:1px solid #e06b88;color:#e06b88;font-weight:700;font-size:0.84rem;padding:9px 20px;border-radius:8px;cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" id="sync-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                        <span id="sync-text">Cek Status Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>

        <script>
            function checkLivePayment(orderNum) {
                if (!orderNum) return;
                const btn = document.getElementById('btn-sync-payment');
                const txt = document.getElementById('sync-text');
                const ico = document.getElementById('sync-icon');
                if (btn) btn.disabled = true;
                if (txt) txt.textContent = 'Mengecek...';
                if (ico) ico.style.animation = 'spin 0.8s linear infinite';

                fetch('/api/payment/status/' + orderNum)
                    .then(r => r.json())
                    .then(res => {
                        if (res.payment_status === 'paid') {
                            if (txt) txt.textContent = 'Pembayaran Lunas!';
                            setTimeout(() => window.location.reload(), 800);
                        } else {
                            if (btn) btn.disabled = false;
                            if (txt) txt.textContent = 'Cek Status Pembayaran';
                            if (ico) ico.style.animation = 'none';
                            alert('Pembayaran belum terdeteksi. Silakan selesaikan pembayaran terlebih dahulu.');
                        }
                    })
                    .catch(() => {
                        if (btn) btn.disabled = false;
                        if (txt) txt.textContent = 'Cek Status Pembayaran';
                        if (ico) ico.style.animation = 'none';
                        alert('Gagal memeriksa status pembayaran.');
                    });
            }
        </script>
    @endif

    {{-- Tracking Card --}}
    <div class="tracking-card {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}">
        {{-- Courier Info --}}
        <div class="courier-header">
            <div class="courier-info">
                <h3>{{ $order['courier']['name'] }}</h3>
                <span class="courier-resi">Resi {{ $order['courier']['resi'] }} &middot; {{ $order['courier']['service'] }}</span>
            </div>
            <span class="tracking-status-badge {{ $order['status_type'] }}">{{ $order['status'] }}</span>
        </div>

        @if($order['status_type'] === 'cancelled')
            <div class="cancelled-order-progress" role="status">
                <span class="cancelled-order-icon" aria-hidden="true">×</span>
                <div>
                    <strong>Alur pesanan dihentikan</strong>
                    <p>Pesanan ini sudah dibatalkan dan tidak akan dilanjutkan ke proses pengiriman.</p>
                </div>
            </div>
        @else
            {{-- Progress Bar --}}
            @php
                $totalSteps = count($order['progress']);
                $currentStep = $order['current_step'];
                $fillPercent = ($totalSteps > 1) ? ($currentStep / ($totalSteps - 1)) * 100 : 0;
            @endphp
            <div class="progress-tracker">
                <div class="progress-track-line">
                    <div class="progress-track-fill" data-width="{{ $fillPercent }}" style="width: 0%"></div>
                </div>
                @foreach($order['progress'] as $idx => $step)
                    <div class="progress-step">
                        @if($step['completed'])
                            <div class="step-dot completed"></div>
                        @elseif($idx === $currentStep + 1)
                            <div class="step-dot current"></div>
                        @else
                            <div class="step-dot"></div>
                        @endif
                        <span class="step-label">{{ $step['label'] }}</span>
                        <span class="step-date">{{ $step['date'] }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Latest Update Bar --}}
        <div class="latest-update-bar {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}">
            <div class="latest-update-left">
                <div class="latest-update-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div class="latest-update-text">
                    <h4>{{ $order['latest_update']['text'] }}</h4>
                    <span>{{ $order['latest_update']['time'] }}</span>
                </div>
            </div>
            <button class="btn-lihat-detail" id="btn-toggle-timeline">Lihat detail</button>
        </div>

        {{-- Timeline Detail (Expandable) --}}
        <div class="tracking-timeline-wrapper" id="timeline-wrapper">
            <div class="tracking-timeline">
                @foreach($order['timeline'] as $group)
                    <div class="timeline-date-group">
                        <div class="timeline-date-label">{{ $group['date'] }}</div>
                        @foreach($group['events'] as $event)
                            <div class="timeline-event">
                                <span class="timeline-event-time">{{ $event['time'] }}</span>
                                <span class="timeline-event-text">{{ $event['text'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Product List --}}
    <h3 class="tracking-section-title">Produk dalam pesanan ini</h3>
    @foreach($order['products'] as $product)
        <div class="tracking-product-card {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}">
            <div class="tracking-product-left">
                <div class="tracking-product-img">
                    <img src="{{ asset($product['image']) }}" alt="{{ $product['title'] }}">
                </div>
                <div class="tracking-product-info">
                    <h4>{{ $product['title'] }}</h4>
                    <p>{{ $product['variant'] }}</p>
                </div>
            </div>
            <div class="tracking-product-price">{{ $product['price'] }}</div>
        </div>
    @endforeach

    {{-- Order Summary --}}
    <div class="tracking-summary-card">
        <div class="summary-row">
            <span class="summary-row-label">Subtotal produk</span>
            <span class="summary-row-value">{{ $order['summary']['subtotal'] }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-row-label">Ongkos kirim</span>
            <span class="summary-row-value">{{ $order['summary']['shipping'] }}</span>
        </div>
        <div class="summary-row total">
            <span class="summary-row-label">Total Belanja</span>
            <span class="summary-row-value">{{ $order['summary']['total'] }}</span>
        </div>
    </div>

    {{-- Footer Actions --}}
    <div class="tracking-footer-actions">
        <a href="/profil" class="btn-back-orders" id="btn-footer-back">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Kembali ke Pesanan Saya
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Timeline toggle
    const btnToggle = document.getElementById('btn-toggle-timeline');
    const timelineWrapper = document.getElementById('timeline-wrapper');
    let timelineOpen = false;

    if (btnToggle && timelineWrapper) {
        btnToggle.addEventListener('click', function() {
            timelineOpen = !timelineOpen;
            if (timelineOpen) {
                timelineWrapper.classList.add('open');
                btnToggle.textContent = 'Tutup detail';
            } else {
                timelineWrapper.classList.remove('open');
                btnToggle.textContent = 'Lihat detail';
            }
        });
    }

    // Clicking "Kembali" goes to profil with pesanan tab active
    const btnBack = document.getElementById('btn-back-to-profil');
    const btnFooterBack = document.getElementById('btn-footer-back');

    function goBackToPesanan(e) {
        e.preventDefault();
        sessionStorage.setItem('sweetdreams_active_tab', 'pesanan');
        window.location.href = '/profil';
    }

    if (btnBack) btnBack.addEventListener('click', goBackToPesanan);
    if (btnFooterBack) btnFooterBack.addEventListener('click', goBackToPesanan);

    // Animate progress bar on load
    const progressFill = document.querySelector('.progress-track-fill');
    if (progressFill) {
        const targetWidth = progressFill.getAttribute('data-width');
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                progressFill.style.width = targetWidth + '%';
            });
        });
    }
});
</script>
@endsection
