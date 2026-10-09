<x-filament-panels::page>
@vite('resources/css/pages/filament/pages/dashboard.css')

{{-- 1. HERO --}}
<div class="sd-hero">
    <div class="sd-hero-date">{{ now()->translatedFormat('l, j F Y') }}</div>
    <h1>Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
    <p>Pantau performa bisnis Sweet Dreams secara real-time</p>
    <div class="sd-hero-chips">
        <a href="{{ url('/filament/products/create') }}" class="sd-chip primary">+ Tambah Produk</a>
        <a href="{{ url('/filament/orders') }}" class="sd-chip">📋 Kelola Pesanan
            @if($statusCounts['pending'] > 0)
                <span style="background:#f43f5e;color:#fff;border-radius:50px;padding:1px 7px;font-size:0.7rem;">{{ $statusCounts['pending'] }}</span>
            @endif
        </a>
        <a href="{{ url('/filament/vouchers') }}" class="sd-chip">🏷️ Promo & Voucher</a>
        <a href="{{ url('/admin/dashboard') }}" class="sd-chip">📊 Laporan Bisnis</a>
    </div>
    <div class="sd-hero-month">📅 {{ now()->translatedFormat('F Y') }}</div>
</div>

{{-- 2. QUICK ACTIONS --}}
<div class="sd-qa-card">
    <h3>Aksi Cepat</h3>
    <div class="sd-qa-grid">
        <a href="{{ url('/filament/orders') }}" class="sd-qa-item">
            <div class="sd-qa-left">
                <div class="sd-qa-icon" style="background:#fce7ee;">🛒</div>
                <div><p class="sd-qa-title">Pesanan</p><p class="sd-qa-sub">Kelola transaksi</p></div>
            </div>
            <span style="color:#c0a0a8;font-size:1rem;">›</span>
        </a>
        <a href="{{ url('/filament/products') }}" class="sd-qa-item">
            <div class="sd-qa-left">
                <div class="sd-qa-icon" style="background:#fce7ee;">📦</div>
                <div><p class="sd-qa-title">Produk</p><p class="sd-qa-sub">Kelola stok</p></div>
            </div>
            <span style="color:#c0a0a8;font-size:1rem;">›</span>
        </a>
        <a href="{{ url('/filament/vouchers') }}" class="sd-qa-item">
            <div class="sd-qa-left">
                <div class="sd-qa-icon" style="background:#fffbe6;">🎟️</div>
                <div><p class="sd-qa-title">Promo & Voucher</p><p class="sd-qa-sub">Buat diskon</p></div>
            </div>
            <span style="color:#c0a0a8;font-size:1rem;">›</span>
        </a>
        <a href="{{ url('/filament/users') }}" class="sd-qa-item">
            <div class="sd-qa-left">
                <div class="sd-qa-icon" style="background:#fce7ee;">👥</div>
                <div><p class="sd-qa-title">Pelanggan</p><p class="sd-qa-sub">Data member</p></div>
            </div>
            <span style="color:#c0a0a8;font-size:1rem;">›</span>
        </a>
    </div>
</div>

{{-- 3. METRIC CARDS ROW 1 --}}
<div class="sd-metric-grid-top">
    <div class="sd-metric">
        <div class="sd-metric-top">
            <span class="sd-metric-label">Pendapatan Bersih</span>
            <span class="sd-badge {{ $momGrowth >= 0 ? 'up' : 'down' }}">
                {{ $momGrowth >= 0 ? '▲' : '▼' }} {{ abs($momGrowth) }}%
            </span>
        </div>
        <p class="sd-metric-value">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</p>
        <div class="sd-metric-ghost" style="font-size:3rem;">📈</div>
    </div>
    <div class="sd-metric">
        <div class="sd-metric-top">
            <span class="sd-metric-label">Penjualan Hari Ini</span>
            <span class="sd-badge flat">Hari ini</span>
        </div>
        <p class="sd-metric-value">Rp {{ number_format($salesToday, 0, ',', '.') }}</p>
    </div>
    <div class="sd-metric">
        <div class="sd-metric-top">
            <span class="sd-metric-label">Total Transaksi</span>
            <span class="sd-badge {{ $orderGrowth >= 0 ? 'up' : 'down' }}">
                {{ $orderGrowth >= 0 ? '▲' : '▼' }} {{ abs($orderGrowth) }}%
            </span>
        </div>
        <p class="sd-metric-value">{{ number_format($totalOrders, 0, ',', '.') }}</p>
    </div>
    <div class="sd-metric">
        <div class="sd-metric-top">
            <span class="sd-metric-label">Total Pendapatan</span>
            <span class="sd-badge up">All time</span>
        </div>
        <p class="sd-metric-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
    </div>
</div>

{{-- ROW 2 --}}
<div class="sd-metric-grid-bot">
    <div class="sd-metric">
        <div class="sd-metric-top"><span class="sd-metric-label">AOV (Rata² Pesanan)</span></div>
        <p class="sd-metric-value sm">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
        <p style="font-size:0.72rem;color:#9a7a85;margin-top:4px;">Per transaksi</p>
    </div>
    <div class="sd-metric">
        <div class="sd-metric-top"><span class="sd-metric-label">Pelanggan Terdaftar</span></div>
        <p class="sd-metric-value sm">{{ number_format($totalCustomers, 0, ',', '.') }}</p>
        <p style="font-size:0.72rem;color:#9a7a85;margin-top:4px;">Akun aktif</p>
    </div>
    <div class="sd-metric">
        <div class="sd-metric-top">
            <span class="sd-metric-label">Pesanan Pending</span>
            @if($statusCounts['pending'] > 5)<span class="sd-badge down">Perhatian</span>@endif
        </div>
        <p class="sd-metric-value sm" style="{{ $statusCounts['pending'] > 0 ? 'color:#e8a94d;' : '' }}">
            {{ $statusCounts['pending'] }}
        </p>
        <p style="font-size:0.72rem;color:#9a7a85;margin-top:4px;">Perlu diproses</p>
    </div>
    <div class="sd-metric">
        <div class="sd-metric-top"><span class="sd-metric-label">Hari Terlaris</span></div>
        @if($bestDay !== '-')
            <div class="sd-best-day">⚡ {{ $bestDay }}</div>
        @else
            <p class="sd-metric-value sm" style="color:#c0a0a8;">—</p>
            <p style="font-size:0.72rem;color:#9a7a85;margin-top:4px;">Belum ada data</p>
        @endif
    </div>
</div>

{{-- 4. CHART + STATUS --}}
<div class="sd-bottom-grid">
    <div class="sd-card">
        <h3 class="sd-card-title">Performa Keuangan</h3>
        <p class="sd-card-sub">Net Revenue — 12 hari terakhir</p>
        <div class="sd-chart-legend">
            <div class="sd-legend-item"><div class="sd-legend-dot" style="background:#d44d6e;"></div> Net Revenue</div>
            <div class="sd-legend-item"><div class="sd-legend-dot" style="background:#f0d8df;"></div> Hari lalu</div>
        </div>
        <div class="sd-chart-wrap">
            @foreach($salesTrend as $idx => $day)
                @php
                    $max    = $salesTrend->max('total') ?: 1;
                    $height = $day['total'] > 0 ? max(8, ($day['total'] / $max) * 120) : 4;
                    $isToday = $idx === $salesTrend->count() - 1;
                @endphp
                <div class="sd-bar {{ $isToday ? 'today' : '' }}"
                     style="height:{{ $height }}px;"
                     title="{{ $day['label'] }}: Rp {{ number_format($day['total'],0,',','.') }}">
                </div>
            @endforeach
        </div>
        <div class="sd-chart-labels">
            @foreach($salesTrend as $day)
                <div class="sd-clabel">{{ $day['label'] }}</div>
            @endforeach
        </div>
    </div>
    <div class="sd-card">
        <h3 class="sd-card-title">Status Pesanan</h3>
        <p class="sd-card-sub">Distribusi pesanan bulan ini</p>
        <div class="sd-status-list">
            <div class="sd-status-row">
                <div class="sd-status-left"><span class="sd-sdot" style="background:#e879a0;"></span> Baru / Pending</div>
                <span class="sd-status-val">{{ $statusCounts['pending'] }}</span>
            </div>
            <div class="sd-status-row">
                <div class="sd-status-left"><span class="sd-sdot" style="background:#6aa9e8;"></span> Diproses</div>
                <span class="sd-status-val">{{ $statusCounts['processing'] }}</span>
            </div>
            <div class="sd-status-row">
                <div class="sd-status-left"><span class="sd-sdot" style="background:#e8a94d;"></span> Dikirim</div>
                <span class="sd-status-val">{{ $statusCounts['shipped'] }}</span>
            </div>
            <div class="sd-status-row">
                <div class="sd-status-left"><span class="sd-sdot" style="background:#3ecf8e;"></span> Selesai</div>
                <span class="sd-status-val">{{ $statusCounts['completed'] }}</span>
            </div>
        </div>
        @if($paymentMix->isNotEmpty())
            <p style="font-size:0.8rem;font-weight:700;color:#2a1f24;margin:1rem 0 0.5rem;">Metode Pembayaran</p>
            @foreach($paymentMix as $method => $cnt)
            <div class="sd-pay-row">
                <div class="sd-pay-label"><span class="sd-sdot" style="background:#d44d6e;"></span>{{ strtoupper($method) }}</div>
                <span style="font-weight:700;color:#2a1f24;">{{ $cnt }} pesanan</span>
            </div>
            @endforeach
        @endif
    </div>
</div>

{{-- 5. AI INSIGHTS --}}
<div class="sd-ai-section">
    <div class="sd-ai-head">
        <span class="sd-ai-chip">✨ AI Insight</span>
        <h2 style="font-size:1rem;font-weight:700;color:#2a1f24;margin:0;">Ringkasan Bisnis Cerdas</h2>
    </div>
    <p style="font-size:0.76rem;color:#9a7a85;margin:0;">Analisis otomatis berbasis data real-time untuk keputusan strategis</p>
    @if(count($insights) > 0)
    <div class="sd-ai-grid">
        @foreach($insights as $insight)
        <div class="sd-ai-card {{ $insight['type'] }}">
            <div class="sd-ai-icon">
                @switch($insight['icon'])
                    @case('trending-up')   📈 @break
                    @case('trending-down') 📉 @break
                    @case('bar-chart-2')   📊 @break
                    @case('clock')         ⏰ @break
                    @case('package')       📦 @break
                    @case('star')          ⭐ @break
                    @case('shopping-bag')  🛍️ @break
                    @case('gift')          🎁 @break
                    @case('calendar')      📅 @break
                    @case('award')         🏆 @break
                    @case('camera')        📸 @break
                    @case('users')         👥 @break
                    @case('tag')           🏷️ @break
                    @default               💡
                @endswitch
            </div>
            <div>
                <p class="sd-ai-card-title">{{ $insight['title'] }}</p>
                <p class="sd-ai-card-text">{!! $insight['body'] !!}</p>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

{{-- 6. TOP PRODUCTS --}}
<div class="sd-card">
    <h3 class="sd-card-title">Produk Terlaris</h3>
    <p class="sd-card-sub">Berdasarkan total unit terjual</p>
    @if($topProducts->isEmpty())
        <p style="color:#9a7a85;font-size:0.84rem;padding:0.75rem 0;">Belum ada data penjualan produk.</p>
    @else
        @foreach($topProducts as $idx => $p)
        <div class="sd-prod-row">
            <span class="sd-prod-rank">{{ str_pad($idx+1,2,'0',STR_PAD_LEFT) }}</span>
            <img class="sd-prod-img" src="{{ asset($p->product_image) }}" alt="{{ $p->product_title }}">
            <div class="sd-prod-info">
                <p class="sd-prod-name">{{ $p->product_title }}</p>
                <p class="sd-prod-qty">{{ $p->total_qty }} unit terjual</p>
            </div>
            <span class="sd-prod-rev">Rp {{ number_format($p->total_revenue, 0, ',', '.') }}</span>
        </div>
        @endforeach
    @endif
</div>
</x-filament-panels::page>
