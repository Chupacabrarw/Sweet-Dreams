@extends('admin.layout')

@section('title', 'Content Management')
@section('page-title', 'CMS / Content Management')
@section('page-subtitle', 'Atur tampilan homepage dan informasi toko')

@section('content')
<style>
    .content-grid { display:grid; grid-template-columns:1fr 1fr; gap:2rem; margin-bottom:1.5rem; }
    .banner-preview { border-radius:12px; overflow:hidden; height:220px; }
    .banner-preview img { width:100%; height:100%; object-fit:cover; }
        .banner-hover-overlay {
        position:absolute; inset:0;
        background:rgba(32,22,27,0.55);
        display:flex; flex-direction:column; align-items:center; justify-content:center; gap:0.5rem;
        color:#fff; font-size:0.85rem; font-weight:600;
        opacity:0; transition:opacity 0.2s ease;
        border-radius:12px;
    }
    .banner-upload-hover:hover .banner-hover-overlay { opacity:1; }
    .form-group label { display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; margin-top:1rem; }
    .form-group input, .form-group textarea { width:100%; padding:0.6rem 0.8rem; border:1px solid #e5dde0; border-radius:10px; font-size:0.88rem; font-family:inherit; }
    .badge-active { display:inline-block; margin-top:1.2rem; padding:0.5rem 1rem; background:#fdeef1; color:#d44d6e; border-radius:10px; font-size:0.85rem; font-weight:600; }
    .small-card-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:1.2rem; }
    .small-card { background:#fff; border-radius:16px; padding:1.3rem; }
    .small-card-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem; }
    .small-card p { font-size:0.85rem; color:#5a4348; margin-bottom:0.8rem; }
    .small-card .meta { font-size:0.78rem; color:#3ecf8e; }
    .edit-text-box { display:none; margin-top:0.8rem; }
    .edit-text-box textarea { width:100%; min-height:90px; padding:0.6rem; border:1px solid #e5dde0; border-radius:10px; font-family:inherit; }
</style>

@if(session('success'))
    <div style="background:#e3f9ee;color:#1e9e64;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

<div class="admin-card" style="margin-bottom:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.2rem;">
        <h3 style="font-size:1rem;">Banner homepage</h3>
    </div>

    <form method="POST" action="{{ route('admin.content.banner') }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="content-grid">
            <div>
                <label class="banner-preview banner-upload-hover" style="display:block; cursor:pointer; position:relative;">
                    <img src="{{ asset($banner->image) }}" id="banner-preview-img">
                    <div class="banner-hover-overlay">
                        <i data-lucide="camera" style="width:26px;height:26px;"></i>
                        <span>Klik untuk ubah gambar</span>
                    </div>
                    <input type="file" name="image" accept="image/*" style="display:none;" onchange="document.getElementById('banner-preview-img').src = URL.createObjectURL(this.files[0])">
                </label>
                <span class="badge-active">Banner Utama · {{ $banner->status === 'published' ? 'Aktif' : 'Draft' }}</span>
            </div>
            <div>
                <div class="form-group">
                    <label>Judul banner</label>
                    <input type="text" name="title" value="{{ $banner->title }}">
                </div>
                <div class="form-group">
                    <label>Subjudul</label>
                    <input type="text" name="subtitle" value="{{ $banner->subtitle }}">
                </div>
                <div class="form-group">
                    <label>Teks tombol</label>
                    <input type="text" name="button_text" value="{{ $banner->button_text }}">
                </div>
                <div class="form-group">
                    <label>Tautan</label>
                    <input type="text" name="link" value="{{ $banner->link }}">
                </div>
                <button type="submit" class="btn-pink" style="margin-top:1.2rem;background:#d44d6e;color:#fff;border:none;border-radius:10px;padding:0.7rem 1.2rem;font-weight:600;cursor:pointer;">Simpan perubahan</button>
            </div>
        </div>
    </form>
</div>

<div class="small-card-grid">
    @foreach([['key' => 'info_toko', 'title' => 'Informasi toko', 'content' => $infoToko], ['key' => 'kebijakan_retur', 'title' => 'Kebijakan retur', 'content' => $kebijakanRetur], ['key' => 'panduan_ukuran', 'title' => 'Panduan ukuran', 'content' => $panduanUkuran]] as $block)
        <div class="small-card">
            <div class="small-card-head">
                <strong>{{ $block['title'] }}</strong>
                <span class="action-link" style="color:#d44d6e;font-weight:600;font-size:0.85rem;cursor:pointer;" onclick="toggleEdit('{{ $block['key'] }}')">Edit</span>
            </div>
            <p id="text-{{ $block['key'] }}">{{ $block['content']->body }}</p>
            <span class="meta">● Dipublikasikan · diperbarui {{ $block['content']->updated_at->diffForHumans() }}</span>

            <form class="edit-text-box" id="edit-{{ $block['key'] }}" method="POST" action="{{ route('admin.content.text', $block['key']) }}">
                @csrf @method('PUT')
                <textarea name="body">{{ $block['content']->body }}</textarea>
                <button type="submit" style="margin-top:0.5rem;background:#d44d6e;color:#fff;border:none;border-radius:8px;padding:0.5rem 1rem;font-size:0.82rem;cursor:pointer;">Simpan</button>
            </form>
        </div>
    @endforeach
</div>

<script>
    function toggleEdit(key) {
        document.getElementById('edit-' + key).style.display = 'block';
    }
</script>
@endsection