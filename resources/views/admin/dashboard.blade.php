@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Selamat datang kembali, ' . auth()->user()->name . ' — ' . now()->translatedFormat('l, j F Y'))

@section('content')
<style>
    .chart-bar-wrap { display:flex; align-items:flex-end; gap:0.6rem; height:220px; padding-top:1rem; }
    .chart-bar { flex:1; background:#fbe2e8; border-radius:8px 8px 0 0; }
    .chart-bar.today { background:#d44d6e; }
    .status-row { display:flex; justify-content:space-between; align-items:center; padding:0.6rem 0; font-size:0.88rem; }
    .status-dot { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:0.5rem; }
    .top-product-row { display:flex; align-items:center; gap:1rem; padding:0.9rem 0; border-bottom:1px solid #f1e4e7; }
    .top-product-row:last-child { border-bottom:none; }
    .top-product-row img { width:48px; height:48px; border-radius:10px; object-fit:cover; }
</style>

<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-label">Penjualan hari ini</div>
        <div class="admin-stat-value">Rp {{ number_format($salesToday, 0, ',', '.') }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Penjualan bulan ini</div>
        <div class="admin-stat-value">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Total pesanan</div>
        <div class="admin-stat-value">{{ number_format($totalOrders, 0, ',', '.') }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Total pendapatan</div>
        <div class="admin-stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">
    <div class="admin-card">
        <h3 style="font-size:1rem; margin-bottom:0.5rem;">Tren penjualan (12 hari terakhir)</h3>
        <div class="chart-bar-wrap">
            @foreach($salesTrend as $idx => $day)
                @php
                    $max = $salesTrend->max('total') ?: 1;
                    $height = $day['total'] > 0 ? max(8, ($day['total'] / $max) * 200) : 4;
                @endphp
                <div class="chart-bar {{ $idx === $salesTrend->count() - 1 ? 'today' : '' }}" style="height: {{ $height }}px;" title="{{ $day['label'] }}: Rp {{ number_format($day['total'],0,',','.') }}"></div>
            @endforeach
        </div>
    </div>

    <div class="admin-card">
        <h3 style="font-size:1rem; margin-bottom:0.75rem;">Status pesanan</h3>
        <div class="status-row"><span><span class="status-dot" style="background:#e879a0;"></span>Baru</span><strong>{{ $statusCounts['pending'] }}</strong></div>
        <div class="status-row"><span><span class="status-dot" style="background:#6aa9e8;"></span>Diproses</span><strong>{{ $statusCounts['processing'] }}</strong></div>
        <div class="status-row"><span><span class="status-dot" style="background:#e8a94d;"></span>Dikirim</span><strong>{{ $statusCounts['shipped'] }}</strong></div>
        <div class="status-row"><span><span class="status-dot" style="background:#3ecf8e;"></span>Selesai</span><strong>{{ $statusCounts['completed'] }}</strong></div>
    </div>
</div>

<div class="admin-card">
    <h3 style="font-size:1rem; margin-bottom:0.5rem;">Produk terlaris</h3>
    @if($topProducts->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">Belum ada data penjualan.</p>
    @else
        @foreach($topProducts as $idx => $p)
            <div class="top-product-row">
                <span style="color:#d44d6e; font-weight:700; width:24px;">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <img src="{{ asset($p->product_image) }}" alt="{{ $p->product_title }}">
                <div style="flex:1;">
                    <strong style="font-size:0.9rem;">{{ $p->product_title }}</strong>
                </div>
                <span style="font-size:0.85rem; color:#8a6a72;">{{ $p->total_qty }} terjual</span>
                <strong style="width:130px; text-align:right;">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</strong>
            </div>
        @endforeach
    @endif
</div>
@endsection