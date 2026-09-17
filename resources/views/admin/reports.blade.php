@extends('admin.layout')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Rekap Penjualan')
@section('page-subtitle', 'Analisis performa transaksi')

@section('content')
<style>
    .report-filter { display:flex; gap:0.75rem; align-items:center; margin-bottom:1.5rem; flex-wrap:wrap; }
    .report-filter input { padding:0.6rem 0.9rem; border:1px solid #e5dde0; border-radius:10px; font-size:0.85rem; }
    .chart-bar-wrap { display:flex; align-items:flex-end; gap:0.6rem; height:220px; padding-top:1rem; }
    .chart-bar { flex:1; background:#fbe2e8; border-radius:8px 8px 0 0; }
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 0.5rem; border-bottom:1px solid #f1e4e7; }
    .admin-table td { padding:0.9rem 0.5rem; border-bottom:1px solid #f1e4e7; font-size:0.88rem; }
</style>

<form method="GET" action="{{ route('admin.reports') }}" class="report-filter">
    <input type="date" name="start" value="{{ $start }}">
    <input type="date" name="end" value="{{ $end }}">
    <button type="submit" style="background:#d44d6e;color:#fff;border:none;border-radius:10px;padding:0.6rem 1.2rem;font-weight:600;cursor:pointer;">Terapkan filter</button>
</form>

<div class="admin-stat-grid">
    <div class="admin-stat-card"><div class="admin-stat-label">Pendapatan kotor</div><div class="admin-stat-value">Rp {{ number_format($grossRevenue, 0, ',', '.') }}</div></div>
    <div class="admin-stat-card"><div class="admin-stat-label">Diskon</div><div class="admin-stat-value">Rp {{ number_format($totalDiscount, 0, ',', '.') }}</div></div>
    <div class="admin-stat-card"><div class="admin-stat-label">Pendapatan bersih</div><div class="admin-stat-value">Rp {{ number_format($netRevenue, 0, ',', '.') }}</div></div>
    <div class="admin-stat-card"><div class="admin-stat-label">Rata-rata pesanan</div><div class="admin-stat-value">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div></div>
</div>

<div class="admin-card" style="margin-bottom:1.5rem;">
    <h3 style="font-size:1rem; margin-bottom:0.5rem;">Pendapatan harian</h3>
    <div class="chart-bar-wrap">
        @foreach($dailyChart as $day)
            @php
                $max = $dailyChart->max('total') ?: 1;
                $height = $day['total'] > 0 ? max(8, ($day['total'] / $max) * 200) : 4;
            @endphp
            <div class="chart-bar" style="height: {{ $height }}px;" title="Tgl {{ $day['label'] }}: Rp {{ number_format($day['total'],0,',','.') }}"></div>
        @endforeach
    </div>
</div>

<div class="admin-card">
    <h3 style="font-size:1rem; margin-bottom:1rem;">Rekap transaksi harian</h3>
    @if($dailyRecap->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">Belum ada transaksi di periode ini.</p>
    @else
        <table class="admin-table">
            <thead><tr><th>Tanggal</th><th>Pesanan</th><th>Pendapatan Kotor</th><th>Diskon</th><th>Pendapatan Bersih</th></tr></thead>
            <tbody>
                @foreach($dailyRecap as $row)
                    <tr>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['orders'] }}</td>
                        <td>Rp {{ number_format($row['gross'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row['discount'], 0, ',', '.') }}</td>
                        <td><strong>Rp {{ number_format($row['net'], 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection