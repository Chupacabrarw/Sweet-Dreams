@extends('admin.layout')

@section('title', 'Content Management')
@section('page-title', 'CMS / Content Management')
@section('page-subtitle', 'Atur tampilan homepage dan konten landing page')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/content.css')
@endpush

<div class="cms-grid">

    {{-- ===== BANNER SECTION ===== --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <div class="cms-card-header-left">
                <div class="cms-card-icon"><i data-lucide="image"></i></div>
                <div>
                    <p class="cms-card-title">Banner Homepage</p>
                    <p class="cms-card-desc">Gambar & teks utama di halaman depan</p>
                </div>
            </div>
        </div>
        <div class="cms-card-body">
            <form method="POST" action="{{ route('admin.content.banner') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="banner-layout">
                    {{-- Preview --}}
                    <div>
                        <div class="banner-preview-wrap" id="banner-preview-area" onclick="document.getElementById('banner-file-input').click()" style="cursor:pointer;">
                            <img src="{{ asset($banner->image) }}" id="banner-preview-img" alt="Banner Preview">
                            <div class="banner-overlay">
                                <i data-lucide="camera"></i>
                                <span>Klik untuk ganti gambar</span>
                            </div>
                        </div>
                                                <input type="file" id="banner-file-input" name="image" accept="image/*" style="display:none;"
                               onchange="previewBanner(this)">
                        <div class="banner-badge">
                            <span class="banner-badge-dot"></span>
                            Banner Utama · {{ $banner->status === 'published' ? 'Aktif' : 'Draft' }}
                        </div>

                        {{-- Petunjuk ukuran gambar --}}
                        <small style="display:block; margin-top:8px; color:#8a7a7f; line-height:1.4;">
                            Ukuran disarankan 2400×1000 px (landscape), minimal 1920×800 px. Format JPG/WebP.
                        </small>

                        @error('image')
                            <small style="display:block; margin-top:6px; color:#d44d6e;">{{ $message }}</small>
                        @enderror
                    </div>


                    {{-- Fields --}}
                    <div>
                        <div class="field-group">
                            <label class="field-label">Judul Banner</label>
                            <input class="field-input" type="text" name="title" value="{{ $banner->title }}" placeholder="Judul utama...">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Sub-judul</label>
                            <input class="field-input" type="text" name="subtitle" value="{{ $banner->subtitle }}" placeholder="Kalimat pendukung...">
                        </div>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                            <div class="field-group">
                                <label class="field-label">Teks Tombol</label>
                                <input class="field-input" type="text" name="button_text" value="{{ $banner->button_text }}" placeholder="Shop Now">
                            </div>
                            <div class="field-group">
                                <label class="field-label">Tautan Tombol</label>
                                <input class="field-input" type="text" name="link" value="{{ $banner->link }}" placeholder="/katalog">
                            </div>
                        </div>
                        <button type="submit" class="btn-save">
                            <i data-lucide="save"></i> Simpan Banner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== LANDING PAGE SECTIONS ===== --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <div class="cms-card-header-left">
                <div class="cms-card-icon"><i data-lucide="layout-panel-top"></i></div>
                <div>
                    <p class="cms-card-title">Landing Page Sections</p>
                    <p class="cms-card-desc">Pilih kategori & produk yang tampil di halaman utama. Produk terlaris ditandai sebagai saran.</p>
                </div>
            </div>
        </div>
        <div class="cms-card-body">
            <form method="POST" action="{{ route('admin.content.featured') }}" id="featured-form">
                @csrf @method('PUT')
                <div class="sections-grid">

                    {{-- KATEGORI --}}
                    <div class="section-panel">
                        <div class="section-panel-header">
                            <i data-lucide="grid-2x2"></i>
                            <span class="section-panel-title">Kategori Ditampilkan</span>
                            <span class="section-panel-count" id="cat-count">{{ count($featuredCategories) }}</span>
                        </div>
                        <div class="section-panel-body" id="cat-list">
                            @forelse($categories as $category)
                            <label class="check-item">
                                <input type="checkbox"
                                       name="categories[]"
                                       value="{{ $category->id }}"
                                       {{ in_array($category->id, $featuredCategories) ? 'checked' : '' }}
                                       onchange="updateCount('cat-list', 'cat-count')">
                                <span class="check-box"><i data-lucide="check"></i></span>
                                <span class="check-label">{{ $category->name }}</span>
                            </label>
                            @empty
                            <div class="section-panel-empty">Belum ada kategori.</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- PRODUK --}}
                    <div class="section-panel">
                        <div class="section-panel-header">
                            <i data-lucide="shopping-bag"></i>
                            <span class="section-panel-title">Produk Unggulan</span>
                            <span class="section-panel-count" id="prod-count">{{ count($featuredProducts) }}</span>
                        </div>
                        <div class="section-panel-body" id="prod-list">
                            @forelse($products as $product)
                            <label class="check-item">
                                <input type="checkbox"
                                       name="products[]"
                                       value="{{ $product->id }}"
                                       {{ in_array($product->id, $featuredProducts) ? 'checked' : '' }}
                                       onchange="updateCount('prod-list', 'prod-count')">
                                <span class="check-box"><i data-lucide="check"></i></span>
                                <img class="check-thumb"
                                     src="{{ asset($product->image) }}"
                                     alt="{{ $product->title }}"
                                     onerror="this.style.display='none'">
                                <span class="check-label">{{ $product->title }}</span>
                                @if($product->paid_sales_count > 0)
                                <span class="check-sales">Terjual {{ $product->paid_sales_count }}</span>
                                @endif
                                <span class="check-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </label>
                            @empty
                            <div class="section-panel-empty">Belum ada produk.</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div style="margin-top:1.5rem; display:flex; align-items:center; gap:1rem;">
                    <button type="submit" class="btn-save">
                        <i data-lucide="save"></i> Simpan Sections
                    </button>
                    <span style="font-size:0.78rem; color:#9a7a82;">
                        Perubahan langsung tampil di landing page setelah disimpan.
                    </span>
                </div>
            </form>
        </div>
    </div>

    <div class="cms-card">
        <div class="cms-card-header">
            <div class="cms-card-header-left">
                <div class="cms-card-icon"><i data-lucide="truck"></i></div>
                <div>
                    <p class="cms-card-title">Ekspedisi Aktif</p>
                    <p class="cms-card-desc">Pilih kurir yang muncul sebagai opsi di checkout pembeli</p>
                </div>
            </div>
        </div>
        <div class="cms-card-body">
            <form method="POST" action="{{ route('admin.content.couriers') }}">
                @csrf @method('PUT')
                <div class="sections-grid">
                    <div class="section-panel">
                        <div class="section-panel-header">
                            <i data-lucide="package"></i>
                            <span class="section-panel-title">Kurir Ditampilkan</span>
                            <span class="section-panel-count" id="courier-count">{{ count($enabledCouriers) }}</span>
                        </div>
                        <div class="section-panel-body" id="courier-list">
                            @foreach($couriers as $code => $name)
                            <label class="check-item">
                                <input type="checkbox"
                                       name="couriers[]"
                                       value="{{ $code }}"
                                       {{ in_array($code, $enabledCouriers) ? 'checked' : '' }}
                                       onchange="updateCount('courier-list', 'courier-count')">
                                <span class="check-box"><i data-lucide="check"></i></span>
                                <span class="check-label">{{ $name }} ({{ strtoupper($code) }})</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div style="margin-top:1.5rem; display:flex; align-items:center; gap:1rem;">
                    <button type="submit" class="btn-save">
                        <i data-lucide="save"></i> Simpan Ekspedisi
                    </button>
                    <span style="font-size:0.78rem; color:#9a7a82;">
                        Minimal 1 kurir. Tarif tersimpan di-cache, jadi pilihan baru langsung berlaku.
                    </span>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function updateCount(listId, countId) {
        const checked = document.querySelectorAll('#' + listId + ' input[type="checkbox"]:checked').length;
        document.getElementById(countId).textContent = checked;
    }

    function previewBanner(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('banner-preview-img').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection