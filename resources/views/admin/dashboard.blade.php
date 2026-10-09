@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', '')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/dashboard.css')
@endpush

{{-- ════════════════════════════════════════════
     1. HERO HEADER
════════════════════════════════════════════ --}}
<div class="dash-hero">
    <div class="dash-hero-date">
        <i data-lucide="clock" style="width:12px;height:12px;"></i>
        {{ now()->translatedFormat('l, j F Y') }}
    </div>
    <h1 class="dash-hero-greeting">Selamat Datang, {{ auth()->user()->name }}!</h1>
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