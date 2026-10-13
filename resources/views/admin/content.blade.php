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

    {{-- ===== HALAMAN TENTANG (/tentang) ===== --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <div class="cms-card-header-left">
                <div class="cms-card-icon"><i data-lucide="info"></i></div>
                <div>
                    <p class="cms-card-title">Halaman Tentang</p>
                    <p class="cms-card-desc">Teks & gambar di /tentang — hero, cerita, nilai, statistik, visi-misi, CTA</p>
                </div>
            </div>
        </div>
        <div class="cms-card-body">
            <form method="POST" action="{{ route('admin.content.tentang') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:0 0 0.75rem;">Hero</p>
                <div class="field-group">
                    <label class="field-label">Judul Hero</label>
                    <input class="field-input" type="text" name="tentang_hero_title" value="{{ $tentang['tentang_hero_title'] }}">
                </div>
                <div class="field-group">
                    <label class="field-label">Sub-judul Hero</label>
                    <input class="field-input" type="text" name="tentang_hero_subtitle" value="{{ $tentang['tentang_hero_subtitle'] }}">
                </div>
                <div class="field-group">
                    <label class="field-label">Gambar Hero <small style="color:#8a7a7f;">(kosongkan bila tidak diganti — sekarang: {{ $tentang['tentang_hero_image'] }})</small></label>
                    <input class="field-input" type="file" name="tentang_hero_image" accept="image/*">
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">Cerita Kami</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="field-group">
                        <label class="field-label">Label Kecil</label>
                        <input class="field-input" type="text" name="tentang_story_label" value="{{ $tentang['tentang_story_label'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Judul Cerita</label>
                        <input class="field-input" type="text" name="tentang_story_title" value="{{ $tentang['tentang_story_title'] }}">
                    </div>
                </div>
                @foreach(['p1' => 'Paragraf 1', 'p2' => 'Paragraf 2', 'p3' => 'Paragraf 3'] as $pk => $pl)
                <div class="field-group">
                    <label class="field-label">{{ $pl }}</label>
                    <textarea class="field-input" name="tentang_story_{{ $pk }}" rows="3">{{ $tentang['tentang_story_' . $pk] }}</textarea>
                </div>
                @endforeach
                <div class="field-group">
                    <label class="field-label">Gambar Cerita <small style="color:#8a7a7f;">(kosongkan bila tidak diganti — sekarang: {{ $tentang['tentang_story_image'] }})</small></label>
                    <input class="field-input" type="file" name="tentang_story_image" accept="image/*">
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">Nilai-Nilai (3 kartu)</p>
                <div class="field-group">
                    <label class="field-label">Judul Bagian</label>
                    <input class="field-input" type="text" name="tentang_values_title" value="{{ $tentang['tentang_values_title'] }}">
                </div>
                <div class="field-group">
                    <label class="field-label">Sub-judul Bagian</label>
                    <input class="field-input" type="text" name="tentang_values_subtitle" value="{{ $tentang['tentang_values_subtitle'] }}">
                </div>
                @for($i = 1; $i <= 3; $i++)
                <div style="display:grid; grid-template-columns:200px 1fr; gap:1rem;">
                    <div class="field-group">
                        <label class="field-label">Kartu {{ $i }} — Judul</label>
                        <input class="field-input" type="text" name="tentang_value{{ $i }}_title" value="{{ $tentang['tentang_value' . $i . '_title'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Kartu {{ $i }} — Isi</label>
                        <textarea class="field-input" name="tentang_value{{ $i }}_body" rows="2">{{ $tentang['tentang_value' . $i . '_body'] }}</textarea>
                    </div>
                </div>
                @endfor

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">Statistik (4 kotak)</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    @for($i = 1; $i <= 4; $i++)
                    <div style="display:grid; grid-template-columns:100px 1fr; gap:0.5rem;">
                        <div class="field-group">
                            <label class="field-label">Angka {{ $i }}</label>
                            <input class="field-input" type="text" name="tentang_stat{{ $i }}_num" value="{{ $tentang['tentang_stat' . $i . '_num'] }}">
                        </div>
                        <div class="field-group">
                            <label class="field-label">Label {{ $i }}</label>
                            <input class="field-input" type="text" name="tentang_stat{{ $i }}_label" value="{{ $tentang['tentang_stat' . $i . '_label'] }}">
                        </div>
                    </div>
                    @endfor
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">Visi & Misi</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="field-group">
                        <label class="field-label">Judul Visi</label>
                        <input class="field-input" type="text" name="tentang_visi_title" value="{{ $tentang['tentang_visi_title'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Judul Misi</label>
                        <input class="field-input" type="text" name="tentang_misi_title" value="{{ $tentang['tentang_misi_title'] }}">
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label">Isi Visi</label>
                    <textarea class="field-input" name="tentang_visi_body" rows="3">{{ $tentang['tentang_visi_body'] }}</textarea>
                </div>
                <div class="field-group">
                    <label class="field-label">Isi Misi</label>
                    <textarea class="field-input" name="tentang_misi_body" rows="3">{{ $tentang['tentang_misi_body'] }}</textarea>
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">CTA Bawah</p>
                <div class="field-group">
                    <label class="field-label">Judul CTA</label>
                    <input class="field-input" type="text" name="tentang_cta_title" value="{{ $tentang['tentang_cta_title'] }}">
                </div>
                <div class="field-group">
                    <label class="field-label">Isi CTA</label>
                    <textarea class="field-input" name="tentang_cta_body" rows="2">{{ $tentang['tentang_cta_body'] }}</textarea>
                </div>
                <div class="field-group">
                    <label class="field-label">Teks Tombol CTA</label>
                    <input class="field-input" type="text" name="tentang_cta_button" value="{{ $tentang['tentang_cta_button'] }}">
                </div>

                <div style="margin-top:1.5rem;">
                    <button type="submit" class="btn-save">
                        <i data-lucide="save"></i> Simpan Halaman Tentang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== HALAMAN KONTAK (/kontak) ===== --}}
    <div class="cms-card">
        <div class="cms-card-header">
            <div class="cms-card-header-left">
                <div class="cms-card-icon"><i data-lucide="mail"></i></div>
                <div>
                    <p class="cms-card-title">Halaman Kontak</p>
                    <p class="cms-card-desc">Teks & info di /kontak — hero, kontak langsung, workshop, FAQ</p>
                </div>
            </div>
        </div>
        <div class="cms-card-body">
            <form method="POST" action="{{ route('admin.content.kontak') }}">
                @csrf @method('PUT')
                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:0 0 0.75rem;">Hero</p>
                <div class="field-group">
                    <label class="field-label">Judul</label>
                    <input class="field-input" type="text" name="kontak_hero_title" value="{{ $kontak['kontak_hero_title'] }}">
                </div>
                <div class="field-group">
                    <label class="field-label">Deskripsi</label>
                    <textarea class="field-input" name="kontak_hero_body" rows="3">{{ $kontak['kontak_hero_body'] }}</textarea>
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">Kontak Langsung</p>
                <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">
                    <div class="field-group">
                        <label class="field-label">No. WA (link, cth: 62812...)</label>
                        <input class="field-input" type="text" name="kontak_wa_number" value="{{ $kontak['kontak_wa_number'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">No. WA (tampil)</label>
                        <input class="field-input" type="text" name="kontak_wa_display" value="{{ $kontak['kontak_wa_display'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Sub-teks WA</label>
                        <input class="field-input" type="text" name="kontak_wa_sub" value="{{ $kontak['kontak_wa_sub'] }}">
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                    <div class="field-group">
                        <label class="field-label">Email</label>
                        <input class="field-input" type="text" name="kontak_email" value="{{ $kontak['kontak_email'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Sub-teks Email</label>
                        <input class="field-input" type="text" name="kontak_email_sub" value="{{ $kontak['kontak_email_sub'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Jam Operasional (judul)</label>
                        <input class="field-input" type="text" name="kontak_hours_title" value="{{ $kontak['kontak_hours_title'] }}">
                    </div>
                    <div class="field-group">
                        <label class="field-label">Jam Operasional (isi)</label>
                        <input class="field-input" type="text" name="kontak_hours_sub" value="{{ $kontak['kontak_hours_sub'] }}">
                    </div>
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">Workshop & Peta</p>
                <div class="field-group">
                    <label class="field-label">Alamat Workshop</label>
                    <textarea class="field-input" name="kontak_address" rows="2">{{ $kontak['kontak_address'] }}</textarea>
                </div>
                <div class="field-group">
                    <label class="field-label">Pencarian Peta (query Google Maps)</label>
                    <input class="field-input" type="text" name="kontak_maps_query" value="{{ $kontak['kontak_maps_query'] }}">
                </div>

                <p style="font-weight:700; font-size:0.85rem; color:#8c2a45; margin:1.25rem 0 0.75rem;">FAQ (tambah/hapus baris)</p>
                <div id="faq-rows">
                    @foreach($faqs as $faq)
                    <div class="faq-row" style="display:grid; grid-template-columns:1fr; gap:0.5rem; border:1px solid #f3dde3; border-radius:10px; padding:0.75rem; margin-bottom:0.75rem;">
                        <input class="field-input" type="text" name="faq_q[]" value="{{ $faq['q'] }}" placeholder="Pertanyaan..." maxlength="500">
                        <textarea class="field-input" name="faq_a[]" rows="2" placeholder="Jawaban..." maxlength="2000">{{ $faq['a'] }}</textarea>
                        <div><button type="button" class="btn-remove-faq" onclick="this.closest('.faq-row').remove()" style="background:none;border:none;color:#d44d6e;cursor:pointer;font-size:0.8rem;font-weight:600;">Hapus baris ini</button></div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn-save" onclick="addFaqRow()" style="background:#fff;color:#8c2a45;border:1.5px solid #f3dde3;margin-bottom:1rem;">+ Tambah FAQ</button>

                <div style="margin-top:0.5rem;">
                    <button type="submit" class="btn-save">
                        <i data-lucide="save"></i> Simpan Halaman Kontak
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function addFaqRow() {
        const wrap = document.getElementById('faq-rows');
        const div = document.createElement('div');
        div.className = 'faq-row';
        div.style.cssText = 'display:grid; grid-template-columns:1fr; gap:0.5rem; border:1px solid #f3dde3; border-radius:10px; padding:0.75rem; margin-bottom:0.75rem;';
        div.innerHTML = `
            <input class="field-input" type="text" name="faq_q[]" value="" placeholder="Pertanyaan..." maxlength="500">
            <textarea class="field-input" name="faq_a[]" rows="2" placeholder="Jawaban..." maxlength="2000"></textarea>
            <div><button type="button" onclick="this.closest('.faq-row').remove()" style="background:none;border:none;color:#d44d6e;cursor:pointer;font-size:0.8rem;font-weight:600;">Hapus baris ini</button></div>`;
        wrap.appendChild(div);
    }

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