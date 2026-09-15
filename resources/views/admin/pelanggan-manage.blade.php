@extends('layouts.admin')

@section('title', 'Manajemen Pelanggan')
@section('page-title', 'Manajemen Pelanggan')
@section('page-subtitle', 'Kenali pelanggan dan riwayat pembelian mereka')

@section('content')

    @php
        // Ganti $pelanggan dengan data asli dari controller (mis. Pelanggan::withCount('pesanan')->get())
        $pelanggan = $pelanggan ?? [
            ['id' => 1, 'nama' => 'Alya Rahma', 'email' => 'alya@email.com', 'pesanan' => 12, 'total_belanja' => 'Rp 4.820.000', 'segmen' => 'VIP'],
            ['id' => 2, 'nama' => 'Citra Lestari', 'email' => 'citra@email.com', 'pesanan' => 6, 'total_belanja' => 'Rp 2.160.000', 'segmen' => 'Lama'],
            ['id' => 3, 'nama' => 'Maya Sari', 'email' => 'maya@email.com', 'pesanan' => 1, 'total_belanja' => 'Rp 329.000', 'segmen' => 'Baru'],
            ['id' => 4, 'nama' => 'Dewi Anjani', 'email' => 'dewi@email.com', 'pesanan' => 9, 'total_belanja' => 'Rp 3.780.000', 'segmen' => 'VIP'],
        ];

        $segmenStyle = [
            'VIP' => 'bg-brand-light text-brand',
            'Lama' => 'bg-sky-50 text-sky-600',
            'Baru' => 'bg-emerald-50 text-emerald-600',
        ];

        $totalTerdaftar = $totalTerdaftar ?? 2256;
        $pelangganBaru = $pelangganBaru ?? 284;
        $pelangganLama = $pelangganLama ?? 1846;
        $pelangganVip = $pelangganVip ?? 126;
        $pertumbuhan = $pertumbuhan ?? '8,4%';

        // Riwayat pembelian pelanggan yang lagi dilihat
        $riwayat = $riwayat ?? [
            'nama' => 'Alya Rahma',
            'pesanan' => [
                ['no' => '#SD-1042', 'tanggal' => '08 Sep 2026', 'jumlah_item' => 3, 'total' => 'Rp 609.300'],
                ['no' => '#SD-0814', 'tanggal' => '14 Agu 2026', 'jumlah_item' => 2, 'total' => 'Rp 498.000'],
                ['no' => '#SD-0702', 'tanggal' => '02 Jul 2026', 'jumlah_item' => 1, 'total' => 'Rp 329.000'],
            ],
        ];
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Pelanggan baru</div>
            <div class="text-2xl font-bold text-ink">{{ number_format($pelangganBaru, 0, ',', '.') }}</div>
            <div class="text-xs text-emerald-600 font-medium mt-1">↑ {{ $pertumbuhan }} bulan ini</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Pelanggan lama</div>
            <div class="text-2xl font-bold text-ink">{{ number_format($pelangganLama, 0, ',', '.') }}</div>
            <div class="text-xs text-emerald-600 font-medium mt-1">↑ {{ $pertumbuhan }} bulan ini</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Pelanggan VIP</div>
            <div class="text-2xl font-bold text-ink">{{ number_format($pelangganVip, 0, ',', '.') }}</div>
            <div class="text-xs text-emerald-600 font-medium mt-1">↑ {{ $pertumbuhan }} bulan ini</div>
        </div>
    </div>

    {{-- Tabel pelanggan --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h2 class="font-semibold text-ink">Pelanggan terdaftar · {{ number_format($totalTerdaftar, 0, ',', '.') }}</h2>
            <form method="GET" class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari pelanggan"
                       class="text-sm text-right pr-6 py-1 border-b border-transparent focus:border-brand focus:outline-none placeholder:text-brand placeholder:font-medium text-ink w-40 focus:w-56 transition-all">
                <i data-lucide="search" class="w-3.5 h-3.5 text-brand absolute right-0 top-1/2 -translate-y-1/2"></i>
            </form>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-brand-light/40 text-ink-muted text-xs uppercase tracking-wide">
                    <th class="text-left font-medium px-6 py-3">Pelanggan</th>
                    <th class="text-left font-medium px-6 py-3">Email</th>
                    <th class="text-left font-medium px-6 py-3">Pesanan</th>
                    <th class="text-left font-medium px-6 py-3">Total belanja</th>
                    <th class="text-left font-medium px-6 py-3">Segmen</th>
                    <th class="text-left font-medium px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($pelanggan as $p)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-brand-light shrink-0"></div>
                                <span class="font-medium text-ink">{{ $p['nama'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['email'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['pesanan'] }}</td>
                        <td class="px-6 py-4 font-medium text-ink">{{ $p['total_belanja'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $segmenStyle[$p['segmen']] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $p['segmen'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#riwayat-{{ $p['id'] }}" class="text-brand font-medium text-sm hover:underline">Lihat riwayat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-ink-muted">Belum ada pelanggan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Riwayat pembelian --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <h2 class="font-semibold text-ink mb-5">Riwayat pembelian · {{ $riwayat['nama'] }}</h2>
        <div class="grid grid-cols-3 gap-5">
            @foreach ($riwayat['pesanan'] as $r)
                <div class="border border-black/5 rounded-xl p-4">
                    <div class="font-semibold text-ink text-sm">{{ $r['no'] }}</div>
                    <div class="text-xs text-ink-muted mt-1">{{ $r['tanggal'] }} · {{ $r['jumlah_item'] }} item</div>
                    <div class="text-brand font-semibold mt-2">{{ $r['total'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

@endsection