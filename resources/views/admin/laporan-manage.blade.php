@extends('layouts.admin')

@section('title', 'Laporan & Rekap Penjualan')
@section('page-title', 'Laporan & Rekap Penjualan')
@section('page-subtitle', 'Analisis performa transaksi dan unduh laporan')

@section('content')

    @php
        $tanggalMulai = $tanggalMulai ?? request('dari', '01 Sep 2026');
        $tanggalAkhir = $tanggalAkhir ?? request('sampai', '08 Sep 2026');
        $periode = $periode ?? request('periode', 'Harian');

        $pendapatanKotor = $pendapatanKotor ?? 'Rp 184.700.000';
        $diskon = $diskon ?? 'Rp 12.840.000';
        $pendapatanBersih = $pendapatanBersih ?? 'Rp 171.860.000';
        $rataRataPesanan = $rataRataPesanan ?? 'Rp 286.400';

        // Ganti $pendapatanHarian dengan data asli dari controller (array angka per hari)
        $pendapatanHarian = $pendapatanHarian ?? [38, 52, 42, 70, 60, 80, 55, 100];
        $maxHarian = max($pendapatanHarian);

        $kanalPenjualan = $kanalPenjualan ?? [
            ['label' => 'Website', 'persen' => 68],
            ['label' => 'Marketplace', 'persen' => 21],
            ['label' => 'Social commerce', 'persen' => 11],
        ];

        // Ganti $rekapTransaksi dengan data asli dari controller
        $rekapTransaksi = $rekapTransaksi ?? [
            ['tanggal' => '08 Sep 2026', 'pesanan' => 38, 'kotor' => 'Rp 8.450.000', 'diskon' => 'Rp 420.000', 'bersih' => 'Rp 8.030.000'],
            ['tanggal' => '07 Sep 2026', 'pesanan' => 52, 'kotor' => 'Rp 11.280.000', 'diskon' => 'Rp 614.000', 'bersih' => 'Rp 10.666.000'],
            ['tanggal' => '06 Sep 2026', 'pesanan' => 41, 'kotor' => 'Rp 9.740.000', 'diskon' => 'Rp 486.000', 'bersih' => 'Rp 9.254.000'],
            ['tanggal' => '05 Sep 2026', 'pesanan' => 67, 'kotor' => 'Rp 15.320.000', 'diskon' => 'Rp 1.042.000', 'bersih' => 'Rp 14.278.000'],
            ['tanggal' => '04 Sep 2026', 'pesanan' => 58, 'kotor' => 'Rp 13.860.000', 'diskon' => 'Rp 894.000', 'bersih' => 'Rp 12.966.000'],
        ];
    @endphp

    {{-- Filter bar --}}
    <form method="GET" class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <input type="date" name="dari" value="{{ request('dari') }}"
                   class="px-4 py-2.5 rounded-xl border border-black/10 bg-white text-sm text-ink-soft focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            <input type="date" name="sampai" value="{{ request('sampai') }}"
                   class="px-4 py-2.5 rounded-xl border border-black/10 bg-white text-sm text-ink-soft focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            <select name="periode" class="px-4 py-2.5 rounded-xl border border-black/10 bg-white text-sm text-ink-soft focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                <option {{ $periode === 'Harian' ? 'selected' : '' }}>Harian</option>
                <option {{ $periode === 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option {{ $periode === 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
            <button type="submit" class="bg-brand hover:bg-brand-dark text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                Terapkan filter
            </button>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ Route::has('laporan.export.excel') ? route('laporan.export.excel', request()->query()) : '#' }}"
               class="flex items-center gap-2 border border-black/10 text-ink-soft text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-black/[0.02] transition">
                <i data-lucide="arrow-down" class="w-4 h-4"></i> Excel
            </a>
            <a href="{{ Route::has('laporan.export.pdf') ? route('laporan.export.pdf', request()->query()) : '#' }}"
               class="flex items-center gap-2 border border-black/10 text-ink-soft text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-black/[0.02] transition">
                <i data-lucide="arrow-down" class="w-4 h-4"></i> PDF
            </a>
        </div>
    </form>

    {{-- Stat cards --}}
    <div class="grid grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Pendapatan kotor</div>
            <div class="text-2xl font-bold text-ink">{{ $pendapatanKotor }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Diskon</div>
            <div class="text-2xl font-bold text-ink">{{ $diskon }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Pendapatan bersih</div>
            <div class="text-2xl font-bold text-ink">{{ $pendapatanBersih }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Rata-rata pesanan</div>
            <div class="text-2xl font-bold text-ink">{{ $rataRataPesanan }}</div>
        </div>
    </div>

    {{-- Chart + kanal penjualan --}}
    <div class="grid grid-cols-[1fr_320px] gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-6">
            <h2 class="font-semibold text-ink mb-6">Pendapatan harian</h2>
            <div class="flex items-end gap-3 h-48">
                @foreach ($pendapatanHarian as $val)
                    <div class="flex-1 rounded-t-md {{ $loop->last ? 'bg-brand' : 'bg-brand-light' }}"
                         style="height: {{ $maxHarian ? round(($val / $maxHarian) * 100) : 0 }}%"></div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <h2 class="font-semibold text-ink mb-4">Kanal penjualan</h2>
            <div class="space-y-4">
                @foreach ($kanalPenjualan as $k)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1.5">
                            <span class="text-ink-soft">{{ $k['label'] }}</span>
                            <span class="font-semibold text-ink">{{ $k['persen'] }}%</span>
                        </div>
                        <div class="h-1.5 rounded-full bg-brand-light overflow-hidden">
                            <div class="h-full bg-brand rounded-full" style="width: {{ $k['persen'] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Rekap transaksi harian --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h2 class="font-semibold text-ink">Rekap transaksi harian</h2>
            <span class="text-sm text-brand font-medium">{{ $bulanLaporan ?? 'September 2026' }}</span>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-brand-light/40 text-ink-muted text-xs uppercase tracking-wide">
                    <th class="text-left font-medium px-6 py-3">Tanggal</th>
                    <th class="text-left font-medium px-6 py-3">Pesanan</th>
                    <th class="text-left font-medium px-6 py-3">Pendapatan kotor</th>
                    <th class="text-left font-medium px-6 py-3">Diskon</th>
                    <th class="text-left font-medium px-6 py-3">Pendapatan bersih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($rekapTransaksi as $r)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-6 py-4 text-ink-soft">{{ $r['tanggal'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $r['pesanan'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $r['kotor'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $r['diskon'] }}</td>
                        <td class="px-6 py-4 font-semibold text-ink">{{ $r['bersih'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-ink-muted">Belum ada data transaksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection