@extends('admin.layout')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Rekap Penjualan')
@section('page-subtitle', 'Analisis performa transaksi')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/reports.css')
@endpush

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