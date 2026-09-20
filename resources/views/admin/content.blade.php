@extends('admin.layout')

@section('title', 'Content Management')
@section('page-title', 'CMS / Content Management')
@section('page-subtitle', 'Atur tampilan homepage dan konten landing page')

@section('content')
<style>
    /* ===== PAGE LAYOUT ===== */
    .cms-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    /* ===== SECTION CARD ===== */
    .cms-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(42,15,24,0.06);
        border: 1px solid #f0e8eb;
        transition: box-shadow 0.2s ease;
    }
    .cms-card:hover {
        box-shadow: 0 4px 16px rgba(212,77,110,0.08);
    }
    .cms-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.75rem;
        border-bottom: 1px solid #f8f0f2;
        background: linear-gradient(135deg, #fff 0%, #fef8fa 100%);
    }
    .cms-card-header-left {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }
    .cms-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #d44d6e, #e87090);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(212,77,110,0.25);
    }
    .cms-card-icon svg { width: 18px; height: 18px; }
    .cms-card-title {
        font-size: 0.97rem;
        font-weight: 700;
        color: #20161b;
        margin: 0;
    }
    .cms-card-desc {
        font-size: 0.78rem;
        color: #9a7a82;
        margin: 0.1rem 0 0 0;
    }
    .cms-card-body { padding: 1.75rem; }

    /* ===== BANNER SECTION ===== */
    .banner-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
        align-items: start;
    }
    .banner-preview-wrap {
        position: relative;
        border-radius: 14px;
        overflow: hidden;
        aspect-ratio: 16/9;
        cursor: pointer;
        background: #fef0f3;
        border: 2px dashed #f4c0cc;
        transition: border-color 0.2s;
    }
    .banner-preview-wrap:hover { border-color: #d44d6e; }
    .banner-preview-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .banner-overlay {
        position: absolute;
        inset: 0;
        background: rgba(32,22,27,0.6);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        opacity: 0;
        transition: opacity 0.25s ease;
        border-radius: 12px;
    }
    .banner-overlay svg { width: 24px; height: 24px; }
    .banner-preview-wrap:hover .banner-overlay { opacity: 1; }
    .banner-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: 0.85rem;
        padding: 0.35rem 0.85rem;
        background: #eef9f3;
        color: #1e9e64;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .banner-badge-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #3ecf8e;
    }

    /* ===== FORM FIELDS ===== */
    .field-group { margin-bottom: 1.1rem; }
    .field-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        color: #5a3a42;
        margin-bottom: 0.45rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
    .field-input {
        width: 100%;
        padding: 0.65rem 0.9rem;
        border: 1.5px solid #ecdde0;
        border-radius: 10px;
        font-size: 0.88rem;
        font-family: 'Inter', sans-serif;
        color: #20161b;
        background: #fff;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }
    .field-input:focus {
        border-color: #d44d6e;
        box-shadow: 0 0 0 3px rgba(212,77,110,0.1);
    }

    /* ===== SAVE BUTTON ===== */
    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1.25rem;
        padding: 0.7rem 1.5rem;
        background: linear-gradient(135deg, #d44d6e, #e06080);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        box-shadow: 0 4px 12px rgba(212,77,110,0.3);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-save:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(212,77,110,0.4);
    }
    .btn-save:active { transform: translateY(0); }
    .btn-save svg { width: 15px; height: 15px; }

    /* ===== LANDING PAGE SECTIONS ===== */
    .sections-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    .section-panel {
        border: 1.5px solid #f0e8eb;
        border-radius: 14px;
        overflow: hidden;
    }
    .section-panel-header {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.9rem 1.15rem;
        background: #fef8fa;
        border-bottom: 1.5px solid #f0e8eb;
    }
    .section-panel-header svg { width: 16px; height: 16px; color: #d44d6e; }
    .section-panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #20161b;
    }
    .section-panel-count {
        margin-left: auto;
        background: #d44d6e;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 0.15rem 0.55rem;
        border-radius: 20px;
        min-width: 22px;
        text-align: center;
    }
    .section-panel-body {
        max-height: 300px;
        overflow-y: auto;
        padding: 0.5rem 0;
    }
    .section-panel-body::-webkit-scrollbar { width: 4px; }
    .section-panel-body::-webkit-scrollbar-thumb { background: #f4c0cc; border-radius: 2px; }

    .check-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 1.15rem;
        cursor: pointer;
        transition: background 0.15s ease;
        user-select: none;
    }
    .check-item:hover { background: #fef5f7; }
    .check-item input[type="checkbox"] { display: none; }
    .check-box {
        width: 18px;
        height: 18px;
        border: 2px solid #ecdde0;
        border-radius: 5px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
        background: #fff;
    }
    .check-box svg { width: 10px; height: 10px; color: #fff; opacity: 0; transition: opacity 0.15s; }
    .check-item input:checked ~ .check-box {
        background: #d44d6e;
        border-color: #d44d6e;
    }
    .check-item input:checked ~ .check-box svg { opacity: 1; }
    .check-item input:checked ~ .check-label { color: #d44d6e; font-weight: 600; }

    .check-label {
        font-size: 0.87rem;
        color: #3a2a2e;
        font-weight: 500;
        transition: color 0.15s;
        flex: 1;
    }
    .check-thumb {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        object-fit: cover;
        background: #fef0f3;
        flex-shrink: 0;
    }
    .check-price {
        font-size: 0.75rem;
        color: #9a7a82;
        margin-left: auto;
    }
    .section-panel-empty {
        padding: 2rem;
        text-align: center;
        color: #9a7a82;
        font-size: 0.85rem;
    }

    /* ===== SUCCESS TOAST ===== */
    .toast-success {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.9rem 1.25rem;
        background: linear-gradient(135deg, #eef9f3, #d8f4e7);
        border: 1.5px solid #a8e5c6;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.88rem;
        color: #1a7a4f;
        font-weight: 500;
        animation: slideDown 0.3s ease;
    }
    .toast-success svg { width: 18px; height: 18px; color: #3ecf8e; flex-shrink: 0; }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>

{{-- Toast --}}
@if(session('success'))
    <div class="toast-success">
        <i data-lucide="check-circle-2"></i>
        {{ session('success') }}
    </div>
@endif

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
                    <p class="cms-card-desc">Pilih kategori & produk yang tampil di halaman utama</p>
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