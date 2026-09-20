@extends('layouts.app')

@section('title', 'Tracking Pesanan ' . $order['order_id'] . ' - Sweet Dreams')

@section('content')
<style>
    /* ===== TRACKING PAGE ===== */
    .tracking-page {
        max-width: 820px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }

    /* Back Link */
    .tracking-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--ink-muted);
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 2rem;
        transition: color 0.2s;
    }
    .tracking-back-link:hover {
        color: var(--blush);
    }
    .tracking-back-link svg {
        width: 16px;
        height: 16px;
    }

    /* Header */
    .tracking-header {
        margin-bottom: 2.5rem;
    }
    .tracking-eyebrow {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--blush);
        display: block;
        margin-bottom: 0.5rem;
    }
    .tracking-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 0.65rem 0;
        line-height: 1.25;
    }
    .tracking-header p {
        font-size: 0.92rem;
        color: var(--ink-muted);
        margin: 0;
        line-height: 1.55;
    }

    /* ===== TRACKING CARD ===== */
    .tracking-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 2rem 2.25rem;
        box-shadow: 0 4px 24px rgba(201,122,140, 0.05);
        margin-bottom: 1.5rem;
    }

    /* Courier Header */
    .courier-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 2rem;
    }
    .courier-info h3 {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 0.3rem 0;
    }
    .courier-resi {
        font-size: 0.82rem;
        color: var(--ink-muted);
    }
    .tracking-status-badge {
        font-size: 0.78rem;
        font-weight: 700;
        padding: 6px 18px;
        border-radius: 8px;
        white-space: nowrap;
    }
    .tracking-status-badge.shipping {
        background: #fef2f5;
        color: var(--blush);
        border: 1px solid var(--border);
    }
    .tracking-status-badge.completed {
        background: #ecfdf5;
        color: #10b981;
        border: 1.5px solid #a7f3d0;
    }
    .tracking-status-badge.pending {
        background: #fef9c3;
        color: #b45309;
        border: 1.5px solid #fde68a;
    }

    /* ===== PROGRESS BAR ===== */
    .progress-tracker {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding: 0 0.5rem;
    }

    /* The connecting line (background track) */
    .progress-track-line {
        position: absolute;
        top: 12px;
        left: calc(0.5rem + 12px);
        right: calc(0.5rem + 12px);
        height: 4px;
        background: #f0d0d8;
        border-radius: 4px;
        z-index: 0;
    }

    /* The filled portion of the line */
    .progress-track-fill {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, var(--blush) 100%);
        border-radius: 4px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Step item */
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
        flex: 0 0 auto;
    }
    .progress-step:first-child {
        align-items: flex-start;
    }
    .progress-step:last-child {
        align-items: flex-end;
    }

    /* Step dot */
    .step-dot {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 3px solid #f0d0d8;
        background: #ffffff;
        margin-bottom: 0.75rem;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 0 0 0 transparent;
    }
    .step-dot.completed {
        background: var(--blush);
        border-color: var(--blush);
        box-shadow: 0 0 0 4px rgba(201,122,140, 0.15);
    }
    .step-dot.completed::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #ffffff;
    }
    .step-dot.current {
        border-color: var(--blush);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(201,122,140, 0.15);
        animation: pulseStep 2s infinite;
    }
    .step-dot.current::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--blush);
    }

    @keyframes pulseStep {
        0%, 100% { box-shadow: 0 0 0 4px rgba(201,122,140, 0.15); }
        50% { box-shadow: 0 0 0 8px rgba(201,122,140, 0.08); }
    }

    /* Step labels */
    .step-label {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--ink);
        margin-bottom: 0.15rem;
    }
    .step-date {
        font-size: 0.72rem;
        color: var(--ink-muted);
    }
    .progress-step:first-child .step-label,
    .progress-step:first-child .step-date {
        text-align: left;
    }
    .progress-step:last-child .step-label,
    .progress-step:last-child .step-date {
        text-align: right;
    }

    /* ===== LATEST UPDATE BAR ===== */
    .latest-update-bar {
        background: #fef8f9;
        border: 1px solid #fce7ee;
        border-radius: var(--radius);
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }
    .latest-update-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        flex: 1;
        min-width: 0;
    }
    .latest-update-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #fce7ee;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--blush);
    }
    .latest-update-icon svg {
        width: 16px;
        height: 16px;
    }
    .latest-update-text h4 {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 0.15rem 0;
        line-height: 1.35;
    }
    .latest-update-text span {
        font-size: 0.75rem;
        color: var(--ink-muted);
    }
    .btn-lihat-detail {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--blush);
        background: #ffffff;
        border: 1.5px solid var(--blush);
        border-radius: 8px;
        padding: 8px 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .btn-lihat-detail:hover {
        background: var(--blush);
        color: #ffffff;
    }

    /* ===== TIMELINE DETAIL (Expandable) ===== */
    .tracking-timeline-wrapper {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease;
        opacity: 0;
    }
    .tracking-timeline-wrapper.open {
        max-height: 800px;
        opacity: 1;
    }

    .tracking-timeline {
        padding: 1.75rem 0 0.5rem 0;
    }
    .timeline-date-group {
        margin-bottom: 1.25rem;
    }
    .timeline-date-label {
        font-family: 'DM Sans', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--ink-muted);
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 0.85rem;
        padding-left: 2rem;
    }
    .timeline-event {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.45rem 0;
        position: relative;
        padding-left: 2rem;
    }
    .timeline-event::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0.65rem;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #e8d0d6;
    }
    .timeline-date-group:first-child .timeline-event:first-child::before {
        background: var(--blush);
        box-shadow: 0 0 0 3px rgba(201,122,140, 0.15);
    }
    .timeline-event::after {
        content: '';
        position: absolute;
        left: calc(0.5rem + 3px);
        top: calc(0.65rem + 10px);
        width: 2px;
        height: calc(100% - 2px);
        background: #f0d0d8;
    }
    .timeline-date-group:last-child .timeline-event:last-child::after {
        display: none;
    }
    .timeline-event-time {
        font-size: 0.78rem;
        color: var(--ink-muted);
        font-weight: 600;
        white-space: nowrap;
        min-width: 40px;
    }
    .timeline-event-text {
        font-size: 0.85rem;
        color: var(--ink);
        line-height: 1.45;
    }

    /* ===== PRODUCT CARD ===== */
    .tracking-product-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem 1.75rem;
        box-shadow: 0 4px 16px rgba(201,122,140, 0.03);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.25rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .tracking-product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(201,122,140, 0.08);
    }
    .tracking-product-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex: 1;
        min-width: 0;
    }
    .tracking-product-img {
        width: 72px;
        height: 72px;
        border-radius: 14px;
        overflow: hidden;
        border: 1px solid var(--border);
        background: #faf6f7;
        flex-shrink: 0;
    }
    .tracking-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .tracking-product-info h4 {
        font-family: 'DM Sans', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        margin: 0 0 0.3rem 0;
    }
    .tracking-product-info p {
        font-size: 0.82rem;
        color: var(--ink-muted);
        margin: 0;
    }
    .tracking-product-price {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--blush);
        white-space: nowrap;
    }

    /* ===== ORDER SUMMARY FOOTER ===== */
    .tracking-summary-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: var(--radius-lg);
        padding: 1.5rem 1.75rem;
        box-shadow: 0 4px 16px rgba(201,122,140, 0.03);
        margin-top: 0.5rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.45rem 0;
    }
    .summary-row-label {
        font-size: 0.88rem;
        color: var(--ink-muted);
    }
    .summary-row-value {
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--ink);
    }
    .summary-row.total {
        padding-top: 0.85rem;
        margin-top: 0.5rem;
        border-top: 1.5px solid #fae6ec;
    }
    .summary-row.total .summary-row-label {
        font-weight: 700;
        color: var(--ink);
        font-size: 0.95rem;
    }
    .summary-row.total .summary-row-value {
        font-weight: 800;
        color: var(--blush);
        font-size: 1.1rem;
    }

    /* ===== BACK BUTTON ===== */
    .tracking-footer-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2.5rem;
    }
    .btn-back-orders {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #ffffff;
        border: 1.5px solid rgba(180,140,150,0.35);
        color: var(--ink-muted);
        font-family: 'DM Sans', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 10px 28px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .btn-back-orders:hover {
        border-color: var(--blush);
        color: var(--blush);
        background: #fffbfa;
    }
    .btn-back-orders svg {
        width: 16px;
        height: 16px;
    }

    /* Section title for products */
    .tracking-section-title {
        font-family: 'DM Sans', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--ink);
        margin: 2rem 0 1rem 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 640px) {
        .tracking-page {
            padding: 1.5rem 1rem 3rem;
        }
        .tracking-header h1 {
            font-size: 1.6rem;
        }
        .tracking-card {
            padding: 1.5rem 1.25rem;
        }
        .courier-header {
            flex-direction: column;
            gap: 0.75rem;
        }
        .latest-update-bar {
            flex-direction: column;
            align-items: flex-start;
        }
        .btn-lihat-detail {
            align-self: flex-end;
        }
        .tracking-product-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .tracking-product-price {
            align-self: flex-end;
        }
    }
</style>

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

    {{-- Tracking Card --}}
    <div class="tracking-card">
        {{-- Courier Info --}}
        <div class="courier-header">
            <div class="courier-info">
                <h3>{{ $order['courier']['name'] }}</h3>
                <span class="courier-resi">Resi {{ $order['courier']['resi'] }} &middot; {{ $order['courier']['service'] }}</span>
            </div>
            <span class="tracking-status-badge {{ $order['status_type'] }}">{{ $order['status'] }}</span>
        </div>

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

        {{-- Latest Update Bar --}}
        <div class="latest-update-bar">
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
        <div class="tracking-product-card">
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
