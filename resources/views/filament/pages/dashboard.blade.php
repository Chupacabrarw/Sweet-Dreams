<x-filament-panels::page>
<style>
/* ═══════════════════ RESET FILAMENT PAGE PADDING ═══════════════════ */
.fi-main { background: #f4f3f5 !important; }

/* ═══════════════════ HERO ═══════════════════ */
.sd-hero {
    background: linear-gradient(135deg, #3a2a2e 0%, #8c2a45 45%, #d44d6e 100%);
    border-radius: 20px;
    padding: 2rem 2.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    overflow: hidden;
    color: #fff;
}
.sd-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.sd-hero::after {
    content: '';
    position: absolute;
    bottom: -80px; right: 120px;
    width: 280px; height: 280px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
}
.sd-hero-date {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 4px 14px;
    font-size: 0.73rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 0.75rem;
}
.sd-hero h1 {
    font-size: 1.9rem;
    font-weight: 800;
    margin: 0 0 0.35rem;
    letter-spacing: -0.02em;
}
.sd-hero p { font-size: 0.88rem; color: rgba(255,255,255,0.72); margin: 0; }
.sd-hero-chips {
    display: flex;
    gap: 0.6rem;
    margin-top: 1.25rem;
    flex-wrap: wrap;
}
.sd-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    background: rgba(255,255,255,0.14);
    border: 1px solid rgba(255,255,255,0.22);
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    color: #fff;
    text-decoration: none;
    transition: background 0.2s;
}
.sd-chip:hover { background: rgba(255,255,255,0.24); }
.sd-chip.primary { background: rgba(255,255,255,0.92); color: #8c2a45; }
.sd-chip.primary:hover { background: #fff; }
.sd-hero-month {
    position: absolute;
    right: 2.5rem; top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.13);
    border: 1px solid rgba(255,255,255,0.25);
    border-radius: 10px;
    padding: 0.5rem 1rem;
    font-size: 0.84rem;
    font-weight: 600;
    z-index: 1;
}

/* ═══════════════════ QUICK ACTIONS ═══════════════════ */
.sd-qa-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #f0e0e5;
}
.sd-qa-card h3 { font-size: 0.95rem; font-weight: 700; color: #2a1f24; margin: 0 0 1rem; }
.sd-qa-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 0.75rem; }
.sd-qa-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 0.9rem 1.1rem; border-radius: 12px;
    border: 1.5px solid #f0e0e5; background: #fafafa;
    text-decoration: none; color: #2a1f24;
    transition: all 0.2s;
}
.sd-qa-item:hover {
    border-color: #d44d6e; background: #fff9fb;
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(212,77,110,0.1);
}
.sd-qa-left { display: flex; align-items: center; gap: 0.8rem; }
.sd-qa-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.sd-qa-title { font-size: 0.87rem; font-weight: 700; margin: 0 0 2px; }
.sd-qa-sub { font-size: 0.73rem; color: #9a7a85; margin: 0; }

/* ═══════════════════ METRICS ═══════════════════ */
.sd-metric-grid-top { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-bottom: 1rem; }
.sd-metric-grid-bot { display: grid; grid-template-columns: repeat(4,1fr); gap: 1rem; margin-bottom: 1.5rem; }
.sd-metric {
    background: #fff; border-radius: 14px; padding: 1.15rem 1.35rem;
    border: 1px solid #f0e0e5; position: relative;
}
.sd-metric-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.6rem; }
.sd-metric-label { font-size: 0.74rem; font-weight: 600; color: #9a7a85; text-transform: uppercase; letter-spacing: 0.04em; }
.sd-badge {
    font-size: 0.71rem; font-weight: 700;
    padding: 2px 8px; border-radius: 50px;
    display: inline-flex; align-items: center; gap: 3px;
}
.sd-badge.up   { background: #e6f9f0; color: #1d9e6a; }
.sd-badge.down { background: #fef2f2; color: #e53e3e; }
.sd-badge.flat { background: #fce7ee; color: #d44d6e; }
.sd-metric-value { font-size: 1.5rem; font-weight: 800; color: #2a1f24; letter-spacing: -0.02em; margin: 0; }
.sd-metric-value.sm { font-size: 1.25rem; }
.sd-metric-ghost { position: absolute; bottom: 0.75rem; right: 0.85rem; opacity: 0.06; }
.sd-best-day {
    display: inline-flex; align-items: center; gap: 0.4rem;
    background: linear-gradient(135deg, #d44d6e, #e06b88);
    color: #fff; font-size: 1rem; font-weight: 800;
    padding: 5px 16px; border-radius: 50px; margin-top: 4px;
}

/* ═══════════════════ BOTTOM GRID ═══════════════════ */
.sd-bottom-grid { display: grid; grid-template-columns: 1.7fr 1fr; gap: 1.25rem; margin-bottom: 1.5rem; }
.sd-card { background: #fff; border-radius: 16px; padding: 1.4rem 1.5rem; border: 1px solid #f0e0e5; }
.sd-card-title { font-size: 0.95rem; font-weight: 700; color: #2a1f24; margin: 0 0 0.2rem; }
.sd-card-sub { font-size: 0.77rem; color: #9a7a85; margin: 0 0 1.2rem; }

/* Chart */
.sd-chart-legend { display: flex; gap: 1.2rem; margin-bottom: 1rem; }
.sd-legend-item { display: flex; align-items: center; gap: 5px; font-size: 0.77rem; color: #7a5a65; }
.sd-legend-dot { width: 8px; height: 8px; border-radius: 50%; }
.sd-chart-wrap { display: flex; align-items: flex-end; gap: 5px; height: 130px; }
.sd-bar {
    flex: 1; border-radius: 5px 5px 0 0;
    background: #f0d8df; cursor: pointer;
    transition: background 0.2s;
}
.sd-bar:hover { background: #e8b4c0; }
.sd-bar.today { background: linear-gradient(180deg, #e06b88, #d44d6e); }
.sd-chart-labels { display: flex; gap: 5px; margin-top: 6px; }
.sd-clabel { flex: 1; font-size: 0.61rem; color: #b08090; text-align: center; overflow: hidden; white-space: nowrap; }

/* Status */
.sd-status-list { display: flex; flex-direction: column; }
.sd-status-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.6rem 0; border-bottom: 1px solid #f5e8eb;
    font-size: 0.84rem;
}
.sd-status-row:last-child { border-bottom: none; }
.sd-status-left { display: flex; align-items: center; gap: 0.6rem; color: #5a3a42; }
.sd-sdot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.sd-status-val { font-weight: 700; color: #2a1f24; font-size: 1rem; }
.sd-pay-row { display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid #f5eaec; font-size: 0.83rem; }
.sd-pay-row:last-child { border-bottom: none; }
.sd-pay-label { display: flex; align-items: center; gap: 0.5rem; text-transform: uppercase; font-size: 0.75rem; font-weight: 600; color: #5a3a42; }

/* ═══════════════════ AI INSIGHTS ═══════════════════ */
.sd-ai-head { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.25rem; }
.sd-ai-chip {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: linear-gradient(135deg, #d44d6e, #b83a58);
    color: #fff; font-size: 0.67rem; font-weight: 700;
    letter-spacing: 0.07em; text-transform: uppercase;
    padding: 3px 10px; border-radius: 50px;
}
.sd-ai-section { margin-bottom: 1.5rem; }
.sd-ai-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 0.85rem; margin-top: 1rem; }
.sd-ai-card {
    background: #fff; border-radius: 16px; padding: 1.1rem 1.2rem;
    border: 1.5px solid #f0e0e5;
    display: flex; gap: 0.85rem; align-items: flex-start;
    position: relative; overflow: hidden;
    transition: transform 0.18s, box-shadow 0.18s;
}
.sd-ai-card:hover { transform: translateY(-2px); box-shadow: 0 6px 22px rgba(0,0,0,0.07); }
.sd-ai-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0;
    height: 3px; border-radius: 16px 16px 0 0;
}
.sd-ai-card.positive::before { background: linear-gradient(90deg,#1d9e6a,#34d399); }
.sd-ai-card.warning::before  { background: linear-gradient(90deg,#e8a94d,#f59e0b); }
.sd-ai-card.neutral::before  { background: linear-gradient(90deg,#b87b58,#c99060); }
.sd-ai-card.tip::before      { background: linear-gradient(90deg,#d44d6e,#e06b88); }
.sd-ai-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sd-ai-card.positive .sd-ai-icon { background: #e6f9f0; color: #1d9e6a; }
.sd-ai-card.warning  .sd-ai-icon { background: #fff7ed; color: #e8a94d; }
.sd-ai-card.neutral  .sd-ai-icon { background: #fdf2eb; color: #b87b58; }
.sd-ai-card.tip      .sd-ai-icon { background: #fce7ee; color: #d44d6e; }
.sd-ai-card-title { font-size: 0.83rem; font-weight: 700; color: #2a1f24; margin: 0 0 0.3rem; }
.sd-ai-card-text { font-size: 0.77rem; color: #6a4a52; line-height: 1.55; margin: 0; }
.sd-ai-card-text strong { color: #2a1f24; }

/* ═══════════════════ TOP PRODUCTS ═══════════════════ */
.sd-prod-row { display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 0; border-bottom: 1px solid #f5eaec; }
.sd-prod-row:last-child { border-bottom: none; }
.sd-prod-rank { font-size: 0.77rem; font-weight: 800; color: #d44d6e; width: 20px; flex-shrink: 0; }
.sd-prod-img { width: 42px; height: 42px; border-radius: 9px; object-fit: cover; border: 1px solid #f0d5dc; flex-shrink: 0; }
.sd-prod-info { flex: 1; min-width: 0; }
.sd-prod-name { font-size: 0.84rem; font-weight: 700; color: #2a1f24; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0 0 2px; }
.sd-prod-qty  { font-size: 0.73rem; color: #9a7a85; margin: 0; }
.sd-prod-rev  { font-size: 0.82rem; font-weight: 700; color: #d44d6e; white-space: nowrap; }
</style>

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
