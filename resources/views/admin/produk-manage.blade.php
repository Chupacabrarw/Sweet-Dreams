@extends('layouts.admin')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola katalog, varian, harga, dan status produk')

@section('content')

    @php
        // Ganti $produk dengan data asli dari controller (mis. Produk::latest()->get())
        $produk = $produk ?? [
            ['id' => 1, 'nama' => 'Luna Satin Pajama Set', 'kategori' => 'Piyama', 'varian' => 'S–XL · 4 warna', 'harga' => 'Rp 249.000', 'stok' => 84, 'status' => 'Aktif'],
            ['id' => 2, 'nama' => 'Amora Lace Bralette', 'kategori' => 'Lingerie', 'varian' => 'S–L · 3 warna', 'harga' => 'Rp 179.000', 'stok' => 52, 'status' => 'Aktif'],
            ['id' => 3, 'nama' => 'Sakura Silk Kimono', 'kategori' => 'Kimono', 'varian' => 'All size · 5 warna', 'harga' => 'Rp 329.000', 'stok' => 11, 'status' => 'Stok tipis'],
            ['id' => 4, 'nama' => 'Cloud Cotton Sleep Dress', 'kategori' => 'Baju tidur', 'varian' => 'S–XXL · 2 warna', 'harga' => 'Rp 219.000', 'stok' => 0, 'status' => 'Nonaktif'],
        ];
        $statusStyle = [
            'Aktif' => 'bg-emerald-50 text-emerald-600',
            'Stok tipis' => 'bg-amber-50 text-amber-600',
            'Nonaktif' => 'bg-rose-50 text-rose-500',
        ];
        $jumlahProduk = is_array($produk) ? count($produk) : $produk->count();
    @endphp

    {{-- Search + tambah produk --}}
    <div class="flex items-center justify-between gap-4">
        <form method="GET" class="flex-1 max-w-md">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 text-ink-muted absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Cari nama atau SKU produk..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-black/10 bg-white text-sm
                              placeholder:text-ink-muted focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
            </div>
        </form>

        <button type="button" onclick="document.getElementById('produk-form').scrollIntoView({behavior:'smooth'})"
                class="flex items-center gap-2 bg-brand hover:bg-brand-dark text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
            <i data-lucide="plus" class="w-4 h-4"></i> Tambah produk
        </button>
    </div>

    {{-- Tabel produk --}}
    <div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5">
            <h2 class="font-semibold text-ink">Semua produk · {{ $jumlahProduk }}</h2>
        </div>

        <table class="w-full text-sm">
            <thead>
                <tr class="bg-brand-light/40 text-ink-muted text-xs uppercase tracking-wide">
                    <th class="text-left font-medium px-6 py-3">Produk</th>
                    <th class="text-left font-medium px-6 py-3">Kategori</th>
                    <th class="text-left font-medium px-6 py-3">Varian</th>
                    <th class="text-left font-medium px-6 py-3">Harga</th>
                    <th class="text-left font-medium px-6 py-3">Stok</th>
                    <th class="text-left font-medium px-6 py-3">Status</th>
                    <th class="text-left font-medium px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/5">
                @forelse ($produk as $p)
                    <tr class="hover:bg-black/[0.02]">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden shrink-0">
                                    @if (!empty($p['foto']))
                                        <img src="{{ $p['foto'] }}" class="w-full h-full object-cover" alt="">
                                    @endif
                                </div>
                                <span class="font-medium text-ink">{{ $p['nama'] }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['kategori'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['varian'] }}</td>
                        <td class="px-6 py-4 text-ink font-medium">{{ $p['harga'] }}</td>
                        <td class="px-6 py-4 text-ink-soft">{{ $p['stok'] }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusStyle[$p['status']] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $p['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm">
                                <a href="#" class="text-brand font-medium hover:underline">Edit</a>
                                <span class="text-ink-muted">·</span>
                                <button type="button" class="text-ink-muted hover:text-ink">
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-ink-muted">Belum ada produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Form tambah / edit produk --}}
    <div id="produk-form" class="bg-white rounded-2xl border border-black/5 p-6">
        <h2 class="font-semibold text-ink mb-5">Tambah / edit produk</h2>

        <form method="POST" action="{{ Route::has('produk.store') ? route('produk.store') : '#' }}" enctype="multipart/form-data" class="grid grid-cols-[220px_1fr] gap-6">
            @csrf

            {{-- Upload foto --}}
            <label class="border-2 border-dashed border-brand/40 rounded-2xl h-full min-h-[220px] flex flex-col items-center justify-center gap-2 text-center cursor-pointer bg-brand-light/30 hover:bg-brand-light/50 transition">
                <input type="file" name="foto" accept="image/png, image/jpeg" class="hidden">
                <i data-lucide="upload" class="w-5 h-5 text-brand"></i>
                <span class="text-sm text-brand font-medium">Unggah foto produk</span>
                <span class="text-xs text-ink-muted">PNG/JPG maks. 5 MB</span>
            </label>

            {{-- Fields --}}
            <div class="space-y-5">
                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Nama produk</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Kategori</label>
                        <select name="kategori" class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                            <option value="">Pilih kategori</option>
                            <option value="Piyama">Piyama</option>
                            <option value="Lingerie">Lingerie</option>
                            <option value="Kimono">Kimono</option>
                            <option value="Baju tidur">Baju tidur</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Harga</label>
                        <input type="text" name="harga" value="{{ old('harga') }}" placeholder="Rp 0"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ink mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="2"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="grid grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Ukuran</label>
                        <input type="text" name="ukuran" value="{{ old('ukuran') }}" placeholder="S, M, L, XL"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Warna</label>
                        <input type="text" name="warna" value="{{ old('warna') }}" placeholder="Blush, Navy, Ivory"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-1">
                    <button type="submit" class="bg-brand hover:bg-brand-dark text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                        Simpan produk
                    </button>
                    <button type="reset" class="border border-black/10 text-ink-soft text-sm font-medium px-5 py-2.5 rounded-xl hover:bg-black/[0.02] transition">
                        Batalkan
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection