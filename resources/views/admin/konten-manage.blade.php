@extends('layouts.admin')

@section('title', 'Content Management')
@section('page-title', 'CMS / Content Management')
@section('page-subtitle', 'Atur tampilan homepage dan informasi toko')

@section('content')

    @php
        // Ganti $banner dengan data asli dari controller (mis. Banner::where('aktif', true)->first())
        $banner = $banner ?? [
            'gambar' => null,
            'judul' => 'Soft Nights, Beautiful Mornings',
            'subjudul' => 'Koleksi terbaru untuk malam yang lebih nyaman',
            'teks_tombol' => 'Belanja koleksi',
            'tautan' => '/collections/new-arrival',
            'status' => 'Banner Utama · Aktif',
        ];

        // Ganti $infoBlocks dengan data asli (mis. dari tabel content_blocks)
        $infoBlocks = $infoBlocks ?? [
            ['judul' => 'Informasi toko', 'isi' => 'Sweet Dream adalah brand sleepwear wanita yang menghadirkan kenyamanan premium sejak 2019.', 'status' => 'Dipublikasikan · diperbarui 2 hari lalu'],
            ['judul' => 'Kebijakan retur', 'isi' => 'Produk dapat diretur maksimal 7 hari setelah diterima, dengan tag dan kemasan utuh.', 'status' => 'Dipublikasikan · diperbarui 2 hari lalu'],
            ['judul' => 'Panduan ukuran', 'isi' => 'Ukur lingkar dada, pinggang, dan pinggul. Cocokkan hasil dengan tabel ukuran kami.', 'status' => 'Dipublikasikan · diperbarui 2 hari lalu'],
        ];
    @endphp

    {{-- Banner homepage --}}
    <div class="bg-white rounded-2xl border border-black/5 p-6">
        <form method="POST" action="{{ Route::has('konten.banner.update') ? route('konten.banner.update') : '#' }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex items-center justify-between mb-5">
                <h2 class="font-semibold text-ink">Banner homepage</h2>
                <button type="submit" class="bg-brand hover:bg-brand-dark text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
                    Simpan perubahan
                </button>
            </div>

            <div class="grid grid-cols-[1fr_1fr] gap-6">
                <label class="relative rounded-xl overflow-hidden bg-gray-100 h-full min-h-[220px] cursor-pointer block">
                    <input type="file" name="gambar" accept="image/png, image/jpeg" class="hidden">
                    @if (!empty($banner['gambar']))
                        <img src="{{ $banner['gambar'] }}" class="w-full h-full object-cover" alt="Banner homepage">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center gap-2 text-ink-muted">
                            <i data-lucide="image-plus" class="w-6 h-6"></i>
                            <span class="text-sm">Klik untuk unggah gambar banner</span>
                        </div>
                    @endif
                </label>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Judul banner</label>
                        <input type="text" name="judul" value="{{ old('judul', $banner['judul']) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Subjudul</label>
                        <input type="text" name="subjudul" value="{{ old('subjudul', $banner['subjudul']) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Teks tombol</label>
                        <input type="text" name="teks_tombol" value="{{ old('teks_tombol', $banner['teks_tombol']) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ink mb-1.5">Tautan</label>
                        <input type="text" name="tautan" value="{{ old('tautan', $banner['tautan']) }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-black/10 text-sm focus:outline-none focus:ring-2 focus:ring-brand/30 focus:border-brand">
                    </div>
                </div>
            </div>

            <div class="mt-5 border border-brand/20 bg-brand-light/40 text-brand text-sm font-medium px-4 py-2.5 rounded-xl">
                {{ $banner['status'] }}
            </div>
        </form>
    </div>

    {{-- Info blocks --}}
    <div class="grid grid-cols-3 gap-5">
        @foreach ($infoBlocks as $i => $block)
            <div class="bg-white rounded-2xl border border-black/5 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-semibold text-ink">{{ $block['judul'] }}</h2>
                    <a href="#edit-{{ $i }}" class="text-sm text-brand font-medium hover:underline">Edit</a>
                </div>
                <p class="text-sm text-ink-soft leading-relaxed">{{ $block['isi'] }}</p>
                <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium mt-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $block['status'] }}
                </div>
            </div>
        @endforeach
    </div>

@endsection