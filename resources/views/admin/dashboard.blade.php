@extends('layouts.admin')

@section('title', 'Dashboard Overview')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Selamat datang kembali, ' . (auth()->user()->name ?? 'Nadia') . ' — ' . now()->translatedFormat('l, j F Y'))

@section('content')

    {{-- Stat cards --}}
    <div class="grid grid-cols-4 gap-5">
        @php
            $stats = [
                ['icon' => 'wallet', 'change' => '+12,4%', 'change_color' => 'emerald', 'value' => 'Rp ' . number_format($penjualanHariIni ?? 8450000, 0, ',', '.'), 'label' => 'Penjualan hari ini'],
                ['icon' => 'trending-up', 'change' => '+8,2%', 'change_color' => 'emerald', 'value' => 'Rp ' . number_format($penjualanBulanIni ?? 184700000, 0, ',', '.'), 'label' => 'Penjualan bulan ini'],
                ['icon' => 'shopping-bag', 'change' => '+18 hari ini', 'change_color' => 'sky', 'value' => number_format($totalPesanan ?? 1248, 0, ',', '.'), 'label' => 'Total pesanan'],
                ['icon' => 'coins', 'change' => '+14,8%', 'change_color' => 'emerald', 'value' => 'Rp ' . ($totalPendapatan ?? '1,42 M'), 'label' => 'Total pendapatan'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-9 h-9 rounded-lg bg-brand-light flex items-center justify-center">
                        <i data-lucide="{{ $stat['icon'] }}" class="w-[18px] h-[18px] text-brand"></i>
                    </div>
                    <span class="text-xs font-medium text-{{ $stat['change_color'] }}-600 bg-{{ $stat['change_color'] }}-50 px-2 py-0.5 rounded-full">
                        {{ $stat['change'] }}
                    </span>
                </div>
                <div class="text-2xl font-bold text-ink">{{ $stat['value'] }}</div>
                <div class="text-sm text-ink-muted mt-1">{{ $stat['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Chart + side panel --}}
    <div class="grid grid-cols-[1fr_320px] gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-ink">Tren penjualan</h2>
                <button class="text-sm text-brand font-medium flex items-center gap-1">
                    30 hari terakhir <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </button>
            </div>

            <div class="flex items-end gap-3 h-48">
                @php
                    // Ganti $trenPenjualan dengan data asli dari controller (array angka)
                    $tren = $trenPenjualan ?? [38, 52, 42, 70, 60, 80, 55, 92, 68, 100];
                    $max = max($tren);
                @endphp
                @foreach ($tren as $i => $val)
                    <div class="flex-1 rounded-t-md {{ $loop->last ? 'bg-brand' : 'bg-brand-light' }}"
                         style="height: {{ $max ? round(($val / $max) * 100) : 0 }}%"></div>
                @endforeach
            </div>

            <div class="flex items-center gap-2 mt-4 text-xs text-ink-muted">
                <span class="w-2 h-2 rounded-full bg-brand"></span>
                Penjualan bersih · Rp {{ $penjualanMingguIni ?? '67,8 jt' }} minggu ini
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <h2 class="font-semibold text-ink mb-4">Status pesanan</h2>
                <ul class="space-y-3 text-sm">
                    @php
                        $statusPesanan = $statusPesanan ?? [
                            ['label' => 'Baru', 'value' => 38, 'color' => 'rose-400'],
                            ['label' => 'Diproses', 'value' => 64, 'color' => 'sky-400'],
                            ['label' => 'Dikirim', 'value' => 126, 'color' => 'amber-400'],
                            ['label' => 'Selesai', 'value' => 1020, 'color' => 'emerald-400'],
                        ];
                    @endphp
                    @foreach ($statusPesanan as $s)
                        <li class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-ink-soft">
                                <span class="w-2 h-2 rounded-full bg-{{ $s['color'] }}"></span>{{ $s['label'] }}
                            </span>
                            <span class="font-semibold">{{ number_format($s['value'], 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <h2 class="font-semibold text-ink mb-3">Target bulanan</h2>
                @php $targetPercent = $targetPercent ?? 82; @endphp
                <div class="text-3xl font-bold text-ink mb-3">{{ $targetPercent }}%</div>
                <div class="h-2 rounded-full bg-brand-light overflow-hidden">
                    <div class="h-full bg-brand rounded-full" style="width: {{ $targetPercent }}%"></div>
                </div>
                <div class="text-xs text-ink-muted mt-2">
                    Rp {{ $targetTercapai ?? '184,7 jt' }} dari Rp {{ $targetTotal ?? '225 jt' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Produk terlaris --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-semibold text-ink">Produk terlaris</h2>
            <a href="{{ Route::has('produk.index') ? route('produk.index') : '#' }}" class="text-sm text-brand font-medium">Lihat semua</a>
        </div>
        <div class="divide-y divide-black/5">
            @php
                // Ganti $produkTerlaris dengan koleksi/array dari controller
                $produkTerlaris = $produkTerlaris ?? [
                    ['nama' => 'Luna Satin Pajama Set', 'kategori' => 'Piyama', 'terjual' => 328, 'omzet' => 'Rp 41.000.000'],
                    ['nama' => 'Amora Lace Bralette', 'kategori' => 'Lingerie', 'terjual' => 286, 'omzet' => 'Rp 25.700.000'],
                    ['nama' => 'Sakura Silk Kimono', 'kategori' => 'Kimono', 'terjual' => 214, 'omzet' => 'Rp 32.100.000'],
                    ['nama' => 'Cloud Cotton Sleep Dress', 'kategori' => 'Baju tidur', 'terjual' => 198, 'omzet' => 'Rp 21.800.000'],
                ];
            @endphp
            @foreach ($produkTerlaris as $i => $p)
                <div class="flex items-center gap-4 py-3">
                    <span class="text-sm font-semibold text-ink-muted w-6">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="w-11 h-11 rounded-lg bg-gray-100 overflow-hidden">
                        @if (!empty($p['foto']))
                            <img src="{{ $p['foto'] }}" class="w-full h-full object-cover" alt="{{ $p['nama'] }}">
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-ink">{{ $p['nama'] }}</div>
                        <div class="text-xs text-ink-muted">{{ $p['kategori'] }}</div>
                    </div>
                    <div class="text-sm text-ink-muted w-24">{{ $p['terjual'] }} terjual</div>
                    <div class="text-sm font-semibold text-ink w-28 text-right">{{ $p['omzet'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

@endsection