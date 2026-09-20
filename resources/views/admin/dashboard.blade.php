@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', '')

@section('content')
<style>
/* ═══════════════════════════════════════════
   HERO HEADER BANNER
═══════════════════════════════════════════ */
.dash-hero {
    background: linear-gradient(135deg, #3a2a2e 0%, #8c2a45 45%, #d44d6e 100%);
    border-radius: 20px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    color: #fff;
}
.dash-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.dash-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; right: 120px;
    width: 280px; height: 280px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.dash-hero-date {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.18);
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}
.dash-hero-greeting {
    font-family: 'Inter', sans-serif;
    font-size: 2rem;
    font-weight: 800;
    margin: 0 0 0.35rem;
    letter-spacing: -0.02em;
}
.dash-hero-sub {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
    margin: 0;
}
.dash-hero-chips {
    display: flex;
    gap: 0.6rem;
    margin-top: 1.25rem;
    flex-wrap: wrap;
}
.dash-hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    color: #fff;
}
.dash-hero-chip:hover { background: rgba(255,255,255,0.22); }
.dash-hero-chip.primary { background: rgba(255,255,255,0.92); color: #8c2a45; }
.dash-hero-chip.primary:hover { background: #fff; }
.dash-hero-month {
    position: absolute;
    right: 2.5rem;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: rgba(255,255,255,0.13);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    font-weight: 600;
    z-index: 1;
}

/* ═══════════════════════════════════════════
   QUICK ACTIONS
═══════════════════════════════════════════ */
.quick-actions-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #f0e0e5;
}
.quick-actions-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}
.quick-actions-header h3 {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2a1f24;
    margin: 0;
}
.quick-actions-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0.75rem;
}
.quick-action-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.9rem 1.1rem;
    border-radius: 12px;
    border: 1.5px solid #f0e0e5;
    text-decoration: none;
    color: #2a1f24;
    transition: all 0.2s;
    background: #fafafa;
}
.quick-action-item:hover {
    border-color: #d44d6e;
    background: #fff9fb;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(212,77,110,0.1);
}
.quick-action-left { display: flex; align-items: center; gap: 0.8rem; }
.quick-action-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.quick-action-title { font-size: 0.88rem; font-weight: 700; margin: 0 0 2px; }
.quick-action-sub { font-size: 0.74rem; color: #9a7a85; margin: 0; }

/* ═══════════════════════════════════════════
   METRIC CARDS — TOP ROW
═══════════════════════════════════════════ */
.metric-grid-top {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}
.metric-grid-bot {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.metric-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.15rem 1.35rem;
    border: 1px solid #f0e0e5;
    position: relative;
}
.metric-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.6rem;
}
.metric-card-label {
    font-size: 0.76rem;
    font-weight: 600;
    color: #9a7a85;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.metric-badge {
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 3px;
}
.metric-badge.up   { background: #e6f9f0; color: #1d9e6a; }
.metric-badge.down { background: #fef2f2; color: #e53e3e; }
.metric-badge.flat { background: #fce7ee; color: #d44d6e; }
.metric-value {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2a1f24;
    letter-spacing: -0.02em;
    margin: 0;
}
.metric-value.sm { font-size: 1.25rem; }
.metric-icon-bg {
    position: absolute;
    bottom: 0.75rem; right: 0.85rem;
    opacity: 0.06;
}

/* ═══════════════════════════════════════════
   BOTTOM GRID: Chart + AI + Top Products
═══════════════════════════════════════════ */
.dash-bottom-grid {
    display: grid;
    grid-template-columns: 1.7fr 1fr;
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}
.dash-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.4rem 1.5rem;
    border: 1px solid #f0e0e5;
}
.dash-card-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #2a1f24;
    margin: 0 0 0.25rem;
}
.dash-card-sub {
    font-size: 0.78rem;
    color: #9a7a85;
    margin: 0 0 1.25rem;
}

/* Chart */
.chart-wrap {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 140px;
}
.cbar {
    flex: 1;
    border-radius: 5px 5px 0 0;
    background: #f0d8df;
    transition: background 0.2s;
    cursor: pointer;
    position: relative;
}
.cbar:hover { background: #e8b4c0; }
.cbar.today { background: linear-gradient(180deg, #e06b88, #d44d6e); }
.cbar:hover.today { opacity: 0.85; }
.chart-labels {
    display: flex;
    gap: 6px;
    margin-top: 6px;
}
.clabel {
    flex: 1;
    font-size: 0.62rem;
    color: #b08090;
    text-align: center;
    overflow: hidden;
    white-space: nowrap;
}

/* Legend dots */
.chart-legend { display: flex; gap: 1.25rem; margin-bottom: 1rem; }
.legend-item { display: flex; align-items: center; gap: 5px; font-size: 0.78rem; color: #7a5a65; }
.legend-dot { width: 8px; height: 8px; border-radius: 50%; }

/* Status orders */
.order-status-list { display: flex; flex-direction: column; gap: 0; }
.order-status-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.65rem 0;
    border-bottom: 1px solid #f5e8eb;
    font-size: 0.85rem;
}
.order-status-row:last-child { border-bottom: none; }
.order-status-left { display: flex; align-items: center; gap: 0.6rem; color: #5a3a42; }
.sdot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.order-status-val { font-weight: 700; color: #2a1f24; font-size: 1rem; }

/* ═══════════════════════════════════════════
   AI INSIGHTS SECTION
═══════════════════════════════════════════ */
.ai-section { margin-bottom: 1.5rem; }
.ai-section-head {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.ai-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #d44d6e, #b83a58);
    color: #fff;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    padding: 3px 11px;
    border-radius: 50px;
}
.ai-head-text h2 {
    font-size: 1rem;
    font-weight: 700;
    color: #2a1f24;
    margin: 0 0 1px;
}
.ai-head-text p {
    font-size: 0.76rem;
    color: #9a7a85;
    margin: 0;
}
.ai-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.85rem;
}
.ai-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.1rem 1.2rem;
    border: 1.5px solid #f0e0e5;
    display: flex;
    gap: 0.85rem;
    align-items: flex-start;
    position: relative;
    overflow: hidden;
    transition: transform 0.18s, box-shadow 0.18s;
}
.ai-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 22px rgba(0,0,0,0.07);
}
.ai-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 16px 16px 0 0;
}
.ai-card.positive::before { background: linear-gradient(90deg,#1d9e6a,#34d399); }
.ai-card.warning::before  { background: linear-gradient(90deg,#e8a94d,#f59e0b); }
.ai-card.neutral::before  { background: linear-gradient(90deg,#b87b58,#c99060); }
.ai-card.tip::before      { background: linear-gradient(90deg,#d44d6e,#e06b88); }
.ai-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ai-card.positive .ai-icon { background: #e6f9f0; color: #1d9e6a; }
.ai-card.warning  .ai-icon { background: #fff7ed; color: #e8a94d; }
.ai-card.neutral  .ai-icon { background: #fdf2eb; color: #b87b58; }
.ai-card.tip      .ai-icon { background: #fce7ee; color: #d44d6e; }
.ai-card-body { flex: 1; min-width: 0; }
.ai-card-title {
    font-size: 0.84rem;
    font-weight: 700;
    color: #2a1f24;
    margin: 0 0 0.3rem;
}
.ai-card-text {
    font-size: 0.78rem;
    color: #6a4a52;
    line-height: 1.55;
    margin: 0;
}
.ai-card-text strong { color: #2a1f24; }

/* ═══════════════════════════════════════════
   TOP PRODUCTS
═══════════════════════════════════════════ */
.top-prod-row {
    display: flex;
    align-items: center;
    gap: 0.85rem;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f5eaec;
}
.top-prod-row:last-child { border-bottom: none; }
.top-prod-rank {
    font-size: 0.78rem;
    font-weight: 800;
    color: #d44d6e;
    width: 20px;
    flex-shrink: 0;
}
.top-prod-img {
    width: 42px; height: 42px;
    border-radius: 9px;
    object-fit: cover;
    border: 1px solid #f0d5dc;
    flex-shrink: 0;
}
.top-prod-info { flex: 1; min-width: 0; }
.top-prod-name {
    font-size: 0.84rem;
    font-weight: 700;
    color: #2a1f24;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0 0 2px;
}
.top-prod-qty { font-size: 0.73rem; color: #9a7a85; margin: 0; }
.top-prod-rev {
    font-size: 0.82rem;
    font-weight: 700;
    color: #d44d6e;
    white-space: nowrap;
    text-align: right;
}

/* ═══════════════════════════════════════════
   PAYMENT MIX
═══════════════════════════════════════════ */
.pay-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.55rem 0;
    font-size: 0.84rem;
    border-bottom: 1px solid #f5eaec;
}
.pay-row:last-child { border-bottom: none; }
.pay-label { display: flex; align-items: center; gap: 0.5rem; text-transform: uppercase; font-size: 0.76rem; font-weight: 600; color: #5a3a42; }
.pay-count { font-weight: 700; color: #2a1f24; }

/* Best day pill */
.best-day-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: linear-gradient(135deg, #d44d6e, #e06b88);
    color: #fff;
    font-size: 1.05rem;
    font-weight: 800;
    padding: 6px 18px;
    border-radius: 50px;
    margin-top: 0.5rem;
}
</style>

{{-- ════════════════════════════════════════════
     1. HERO HEADER
════════════════════════════════════════════ --}}
<div class="dash-hero">
    <div class="dash-hero-date">
        <i data-lucide="clock" style="width:12px;height:12px;"></i>
        {{ now()->translatedFormat('l, j F Y') }}
    </div>
    <h1 class="dash-hero-greeting">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
    <p class="dash-hero-sub">Pantau performa bisnis Sweet Dreams Anda secara real-time</p>
    <div class="dash-hero-chips">
        <a href="{{ route('admin.products') }}" class="dash-hero-chip primary">
            <i data-lucide="plus" style="width:12px;height:12px;"></i> Tambah Produk
        </a>
        <a href="{{ route('admin.orders') }}" class="dash-hero-chip">
            <i data-lucide="file-text" style="width:12px;height:12px;"></i>
            Pesanan Baru
            @if($statusCounts['pending'] > 0)
                <span style="background:#f43f5e;color:#fff;border-radius:50px;padding:1px 7px;font-size:0.7rem;">{{ $statusCounts['pending'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.vouchers') }}" class="dash-hero-chip">
            <i data-lucide="tag" style="width:12px;height:12px;"></i> Kelola Promo
        </a>
        <a href="{{ route('admin.reports') }}" class="dash-hero-chip">
            <i data-lucide="bar-chart-2" style="width:12px;height:12px;"></i> Lihat Laporan
        </a>
    </div>
    <div class="dash-hero-month">
        <i data-lucide="calendar" style="width:14px;height:14px;"></i>
        {{ now()->translatedFormat('F Y') }}
    </div>
</div>

{{-- ════════════════════════════════════════════
     2. QUICK ACTIONS
════════════════════════════════════════════ --}}
<div class="quick-actions-card">
    <div class="quick-actions-header">
        <h3>Aksi Cepat</h3>
    </div>
    <div class="quick-actions-grid">
        <a href="{{ route('admin.orders') }}" class="quick-action-item">
            <div class="quick-action-left">
                <div class="quick-action-icon" style="background:#fce7ee;">
                    <i data-lucide="shopping-cart" style="width:18px;height:18px;color:#d44d6e;"></i>
                </div>
                <div>
                    <p class="quick-action-title">Pesanan</p>
                    <p class="quick-action-sub">Kelola transaksi</p>
                </div>
            </div>
            <i data-lucide="chevron-right" style="width:16px;height:16px;color:#c0a0a8;"></i>
        </a>
        <a href="{{ route('admin.products') }}" class="quick-action-item">
            <div class="quick-action-left">
                <div class="quick-action-icon" style="background:#fce7ee;">
                    <i data-lucide="package" style="width:18px;height:18px;color:#d44d6e;"></i>
                </div>
                <div>
                    <p class="quick-action-title">Produk</p>
                    <p class="quick-action-sub">Kelola stok</p>
                </div>
            </div>
            <i data-lucide="chevron-right" style="width:16px;height:16px;color:#c0a0a8;"></i>
        </a>
        <a href="{{ route('admin.vouchers') }}" class="quick-action-item">
            <div class="quick-action-left">
                <div class="quick-action-icon" style="background:#fffbe6;">
                    <i data-lucide="ticket" style="width:18px;height:18px;color:#c89a10;"></i>
                </div>
                <div>
                    <p class="quick-action-title">Promo & Voucher</p>
                    <p class="quick-action-sub">Buat diskon</p>
                </div>
            </div>
            <i data-lucide="chevron-right" style="width:16px;height:16px;color:#c0a0a8;"></i>
        </a>
        <a href="{{ route('admin.customers') }}" class="quick-action-item">
            <div class="quick-action-left">
                <div class="quick-action-icon" style="background:#fce7ee;">
                    <i data-lucide="users" style="width:18px;height:18px;color:#d44d6e;"></i>
                </div>
                <div>
                    <p class="quick-action-title">Pelanggan</p>
                    <p class="quick-action-sub">Data member</p>
                </div>
            </div>
            <i data-lucide="chevron-right" style="width:16px;height:16px;color:#c0a0a8;"></i>
        </a>
    </div>
</div>

{{-- ════════════════════════════════════════════
     3. METRIC CARDS — ROW 1
════════════════════════════════════════════ --}}
<div class="metric-grid-top">
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Pendapatan Bersih</span>
            <span class="metric-badge {{ $momGrowth >= 0 ? 'up' : 'down' }}">
                <i data-lucide="{{ $momGrowth >= 0 ? 'trending-up' : 'trending-down' }}" style="width:10px;height:10px;"></i>
                {{ $momGrowth >= 0 ? '+' : '' }}{{ $momGrowth }}%
            </span>
        </div>
        <p class="metric-value">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</p>
        <div class="metric-icon-bg"><i data-lucide="trending-up" style="width:52px;height:52px;color:#d44d6e;"></i></div>
    </div>
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Penjualan Hari Ini</span>
            <span class="metric-badge flat">Hari ini</span>
        </div>
        <p class="metric-value">Rp {{ number_format($salesToday, 0, ',', '.') }}</p>
        <div class="metric-icon-bg"><i data-lucide="sun" style="width:52px;height:52px;color:#e8a94d;"></i></div>
    </div>
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Total Transaksi</span>
            <span class="metric-badge {{ $orderGrowth >= 0 ? 'up' : 'down' }}">
                <i data-lucide="{{ $orderGrowth >= 0 ? 'trending-up' : 'trending-down' }}" style="width:10px;height:10px;"></i>
                {{ $orderGrowth >= 0 ? '+' : '' }}{{ $orderGrowth }}%
            </span>
        </div>
        <p class="metric-value">{{ number_format($totalOrders, 0, ',', '.') }}</p>
        <div class="metric-icon-bg"><i data-lucide="receipt" style="width:52px;height:52px;color:#d44d6e;"></i></div>
    </div>
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Total Pendapatan</span>
            <span class="metric-badge up">All time</span>
        </div>
        <p class="metric-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <div class="metric-icon-bg"><i data-lucide="wallet" style="width:52px;height:52px;color:#3ecf8e;"></i></div>
    </div>
</div>

{{-- ROW 2 --}}
<div class="metric-grid-bot">
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">AOV (Nilai Pesanan Rata²)</span>
        </div>
        <p class="metric-value sm">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
        <p style="font-size:0.73rem;color:#9a7a85;margin-top:4px;">Per transaksi</p>
    </div>
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Pelanggan Terdaftar</span>
        </div>
        <p class="metric-value sm">{{ number_format($totalCustomers, 0, ',', '.') }}</p>
        <p style="font-size:0.73rem;color:#9a7a85;margin-top:4px;">Akun aktif</p>
    </div>
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Pesanan Pending</span>
            @if($statusCounts['pending'] > 5)
                <span class="metric-badge down">Perhatian</span>
            @endif
        </div>
        <p class="metric-value sm" style="{{ $statusCounts['pending'] > 0 ? 'color:#e8a94d;' : '' }}">
            {{ $statusCounts['pending'] }}
        </p>
        <p style="font-size:0.73rem;color:#9a7a85;margin-top:4px;">Perlu diproses</p>
    </div>
    <div class="metric-card">
        <div class="metric-card-top">
            <span class="metric-card-label">Hari Terlaris</span>
        </div>
        @if($bestDay !== '-')
            <div class="best-day-pill">
                <i data-lucide="zap" style="width:14px;height:14px;"></i>
                {{ $bestDay }}
            </div>
        @else
            <p class="metric-value sm" style="color:#c0a0a8;">—</p>
            <p style="font-size:0.73rem;color:#9a7a85;margin-top:4px;">Belum ada data</p>
        @endif
    </div>
</div>

{{-- ════════════════════════════════════════════
     4. CHART + STATUS
════════════════════════════════════════════ --}}
<div class="dash-bottom-grid">
    {{-- Chart --}}
    <div class="dash-card">
        <h3 class="dash-card-title">Performa Keuangan</h3>
        <p class="dash-card-sub">Net Revenue — 12 hari terakhir</p>
        <div class="chart-legend">
            <div class="legend-item"><div class="legend-dot" style="background:#d44d6e;"></div> Net Revenue</div>
            <div class="legend-item"><div class="legend-dot" style="background:#f0d8df;"></div> Hari lalu</div>
        </div>
        <div class="chart-wrap">
            @foreach($salesTrend as $idx => $day)
                @php
                    $max    = $salesTrend->max('total') ?: 1;
                    $height = $day['total'] > 0 ? max(8, ($day['total'] / $max) * 130) : 4;
                    $isToday = $idx === $salesTrend->count() - 1;
                @endphp
                <div class="cbar {{ $isToday ? 'today' : '' }}"
                     style="height:{{ $height }}px;"
                     title="{{ $day['label'] }}: Rp {{ number_format($day['total'],0,',','.') }}">
                </div>
            @endforeach
        </div>
        <div class="chart-labels">
            @foreach($salesTrend as $day)
                <div class="clabel">{{ $day['label'] }}</div>
            @endforeach
        </div>
    </div>

    {{-- Status + Payment --}}
    <div class="dash-card">
        <h3 class="dash-card-title">Jam Sibuk & Status</h3>
        <p class="dash-card-sub">Distribusi pesanan bulan ini</p>
        <div class="order-status-list">
            <div class="order-status-row">
                <div class="order-status-left"><span class="sdot" style="background:#e879a0;"></span> Baru / Pending</div>
                <span class="order-status-val">{{ $statusCounts['pending'] }}</span>
            </div>
            <div class="order-status-row">
                <div class="order-status-left"><span class="sdot" style="background:#6aa9e8;"></span> Diproses</div>
                <span class="order-status-val">{{ $statusCounts['processing'] }}</span>
            </div>
            <div class="order-status-row">
                <div class="order-status-left"><span class="sdot" style="background:#e8a94d;"></span> Dikirim</div>
                <span class="order-status-val">{{ $statusCounts['shipped'] }}</span>
            </div>
            <div class="order-status-row">
                <div class="order-status-left"><span class="sdot" style="background:#3ecf8e;"></span> Selesai</div>
                <span class="order-status-val">{{ $statusCounts['completed'] }}</span>
            </div>
        </div>

        @if($paymentMix->isNotEmpty())
        <p style="font-size:0.8rem;font-weight:700;color:#2a1f24;margin:1rem 0 0.5rem;">Metode Pembayaran</p>
        @foreach($paymentMix as $method => $cnt)
        <div class="pay-row">
            <div class="pay-label">
                <span class="sdot" style="background:#d44d6e;"></span>
                {{ strtoupper($method) }}
            </div>
            <span class="pay-count">{{ $cnt }} pesanan</span>
        </div>
        @endforeach
        @endif
    </div>
</div>



{{-- ════════════════════════════════════════════
     6. TOP PRODUCTS
════════════════════════════════════════════ --}}
<div class="dash-card">
    <h3 class="dash-card-title">Produk Terlaris</h3>
    <p class="dash-card-sub">Berdasarkan total unit terjual</p>
    @if($topProducts->isEmpty())
        <p style="color:#9a7a85;font-size:0.85rem;padding:1rem 0;">Belum ada data penjualan produk.</p>
    @else
        @foreach($topProducts as $idx => $p)
        <div class="top-prod-row">
            <span class="top-prod-rank">{{ str_pad($idx+1, 2, '0', STR_PAD_LEFT) }}</span>
            <img class="top-prod-img" src="{{ asset($p->product_image) }}" alt="{{ $p->product_title }}">
            <div class="top-prod-info">
                <p class="top-prod-name">{{ $p->product_title }}</p>
                <p class="top-prod-qty">{{ $p->total_qty }} unit terjual</p>
            </div>
            <span class="top-prod-rev">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</span>
        </div>
        @endforeach
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (window.lucide) lucide.createIcons();
});
</script>
@endsection