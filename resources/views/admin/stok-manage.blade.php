@extends('layouts.admin')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Pantau persediaan untuk setiap ukuran dan warna')

@section('content')

    @php
        // Ganti $stok dengan data asli dari controller (mis. StokVarian::with('produk')->get())
        $stok = $stok ?? [
            ['sku' => 'SD-PJM-023', 'produk' => 'Luna Satin Pajama', 'ukuran' => 'M', 'warna' => 'Blush', 'jumlah' => 18, 'indikator' => 'Aman'],
            ['sku' => 'SD-PJM-023', 'produk' => 'Luna Satin Pajama', 'ukuran' => 'L', 'warna' => 'Navy', 'jumlah' => 7, 'indikator' => 'Menipis'],
            ['sku' => 'SD-KMN-018', 'produk' => 'Sakura Silk Kimono', 'ukuran' => 'All size', 'warna' => 'Ivory', 'jumlah' => 3, 'indikator' => 'Kritis'],
            ['sku' => 'SD-BRA-041', 'produk' => 'Amora Lace Bralette', 'ukuran' => 'M', 'warna' => 'Black', 'jumlah' => 24, 'indikator' => 'Aman'],
            ['sku' => 'SD-SLP-008', 'produk' => 'Cloud Sleep Dress', 'ukuran' => 'XL', 'warna' => 'Sage', 'jumlah' => 0, 'indikator' => 'Habis'],
        ];

        $indikatorStyle = [
            'Aman' => 'bg-emerald-50 text-emerald-600',
            'Menipis' => 'bg-amber-50 text-amber-600',
            'Kritis' => 'bg-rose-50 text-rose-500',
            'Habis' => 'bg-rose-100 text-rose-600',
        ];
        $indikatorText = [
            'Aman' => 'text-ink',
            'Menipis' => 'text-amber-600',
            'Kritis' => 'text-rose-500',
            'Habis' => 'text-rose-500',
        ];

        $totalUnit = $totalUnit ?? collect($stok)->sum('jumlah');
        $stokMenipis = $stokMenipis ?? collect($stok)->where('indikator', 'Menipis')->count();
        $stokHabis = $stokHabis ?? collect($stok)->whereIn('indikator', ['Habis', 'Kritis'])->count();
    @endphp

    {{-- Stat cards --}}
    <div class="grid grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Total unit</div>
            <div class="text-2xl font-bold text-ink">{{ number_format($totalUnit, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Stok menipis</div>
            <div class="text-2xl font-bold text-ink">{{ $stokMenipis }} varian</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Stok habis</div>
            <div class="text-2xl font-bold text-rose-500">{{ $stokHabis }} varian</div>
        </div>
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            <div class="text-sm text-ink-muted mb-2">Diperbarui</div>
            <div class="text-2xl font-bold text-ink">{{ $terakhirUpdate ?? '5 menit lalu' }}</div>
        </div>
    </div>

    {{-- Tabel stok --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h2 class="font-semibold text-ink">Stok per varian</h2>
            <button type="button"
                    class="flex items-center gap-2 bg-brand hover:bg-brand-dark text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
                Update stok
            </button>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-brand-light/40 text-ink-muted text-xs uppercase tracking-wide">
                    <th class="text-left font-medium px-6 py-3">SKU</th>
                    <th class="text-left font-medium px-6 py-3">Produk</th>
                    <th class="text-left font-medium px-6 py-3">Ukuran</th>
                    <th class="text-left font-medium px-6 py-3">Warna</th>
                    <th class="text-left font-medium px-6 py-3">Stok</th>
                    <th class="text-left font-medium px-6 py-3">Indikator</th>
                    <th class="text-left font-medium px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($stok as $s)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-6 py-4 text-ink-soft">{{ $s['sku'] }}</td>
                        <td class="px-6 py-4 font-medium text-ink">{{ $s['produk'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $s['ukuran'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $s['warna'] }}</td>
                        <td class="px-6 py-4 font-medium {{ $indikatorText[$s['indikator']] ?? 'text-ink' }}">{{ $s['jumlah'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $indikatorStyle[$s['indikator']] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $s['indikator'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="#" class="text-brand font-medium text-sm hover:underline">Ubah jumlah</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-ink-muted">Belum ada data stok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection