@extends('admin.layout')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola katalog, varian, harga, dan status produk')

@section('content')
<style>
    .admin-toolbar { display:flex; justify-content:space-between; gap:1rem; margin-bottom:1.5rem; }
    .admin-search-input {
        flex:1; max-width:400px; padding:0.7rem 1rem; border:1px solid #e5dde0;
        border-radius:10px; font-size:0.88rem; background:#fff;
    }
    .btn-pink { background:#d44d6e; color:#fff; border:none; border-radius:10px; padding:0.7rem 1.2rem; font-weight:600; font-size:0.88rem; cursor:pointer; }
    .btn-pink:hover { background:#b83d5c; }
    .btn-outline { background:#fff; border:1px solid #e5dde0; border-radius:10px; padding:0.7rem 1.2rem; font-weight:600; font-size:0.88rem; cursor:pointer; }

    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 0.5rem; border-bottom:1px solid #f1e4e7; }
    .admin-table td { padding:0.9rem 0.5rem; border-bottom:1px solid #f1e4e7; font-size:0.88rem; vertical-align:middle; }
    .prod-cell { display:flex; align-items:center; gap:0.75rem; }
    .prod-cell img { width:44px; height:44px; border-radius:8px; object-fit:cover; }
    .status-pill { padding:0.25rem 0.7rem; border-radius:20px; font-size:0.76rem; font-weight:600; }
    .status-pill.aktif { background:#e3f9ee; color:#1e9e64; }
    .status-pill.tipis { background:#fff3d9; color:#b7791f; }
    .status-pill.nonaktif { background:#fde2e6; color:#c53660; }
    .action-link { color:#d44d6e; font-weight:600; font-size:0.85rem; cursor:pointer; margin-right:0.75rem; }

    .product-form-grid { display:grid; grid-template-columns:220px 1fr; gap:2rem; }
    .dropzone {
        border:2px dashed #f2c9d3; border-radius:14px; height:220px;
        display:flex; align-items:center; justify-content:center; text-align:center;
        color:#c9808f; font-size:0.85rem; cursor:pointer; overflow:hidden; position:relative;
    }
    .dropzone img { width:100%; height:100%; object-fit:cover; position:absolute; inset:0; }
    .form-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem; }
    .form-row.full { grid-template-columns:1fr; }
    .form-group label { display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; }
    .form-group input, .form-group select, .form-group textarea {
        width:100%; padding:0.65rem 0.85rem; border:1px solid #e5dde0; border-radius:10px; font-size:0.88rem; font-family:inherit;
    }
</style>

@if(session('success'))
    <div style="background:#e3f9ee;color:#1e9e64;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('admin.products') }}" class="admin-toolbar">
    <input type="text" name="q" value="{{ $search }}" class="admin-search-input" placeholder="Cari nama atau SKU produk...">
    <button type="button" class="btn-pink" onclick="resetProductForm(); document.getElementById('product-form-card').scrollIntoView({behavior:'smooth'});">
        + Tambah produk
    </button>
</form>

<div class="admin-card" style="margin-bottom:1.5rem;">
    <div style="margin-bottom:0.5rem; font-size:0.85rem; color:#8a6a72;">Semua produk · {{ $products->count() }}</div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Produk</th><th>Kategori</th><th>Varian</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
                <tr>
                    <td>
                        <div class="prod-cell">
                            <img src="{{ asset($p['image']) }}" alt="{{ $p['title'] }}">
                            <span>{{ $p['title'] }}</span>
                        </div>
                    </td>
                    <td>{{ $p['category'] }}</td>
                    <td>{{ $p['sizes'] }} · {{ $p['colors_count'] }} warna</td>
                    <td>{{ $p['price'] }}</td>
                    <td>{{ $p['stock'] }}</td>
                    <td><span class="status-pill {{ $p['status_class'] }}">{{ $p['status_label'] }}</span></td>
                    <td>
                        <span class="action-link" onclick='editProduct(@json($p))'>Edit</span>
                        <form method="POST" action="{{ route('admin.products.destroy', $p['id']) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus produk ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-link" style="background:none;border:none;padding:0;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="admin-card" id="product-form-card">
    <h3 id="product-form-title" style="font-size:1rem; margin-bottom:1.2rem;">Tambah / edit produk</h3>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="product-form">
        @csrf
        <input type="hidden" name="_method" id="product-form-method" value="POST">

        <div class="product-form-grid">
            <label class="dropzone" id="dropzone-label">
                <span id="dropzone-text">+ Unggah foto produk<br>PNG/JPG maks. 5MB</span>
                <img id="dropzone-preview" style="display:none;">
                <input type="file" name="image" id="input-image" accept="image/*" style="display:none;" onchange="previewImage(this)">
            </label>

            <div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama produk</label>
                        <input type="text" name="title" id="input-title" required>
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" id="input-category" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="price" id="input-price" required>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="short_desc" id="input-desc" rows="2"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Ukuran (pisah koma)</label>
                        <input type="text" name="sizes" id="input-sizes" placeholder="S, M, L, XL">
                    </div>
                    <div class="form-group">
                        <label>Warna (pisah koma)</label>
                        <input type="text" name="colors" id="input-colors" placeholder="pink, cream">
                    </div>
                    <div class="form-group">
                        <label>SKU</label>
                        <input type="text" name="sku" id="input-sku">
                    </div>
                </div>

                <button type="submit" class="btn-pink">Simpan produk</button>
                <button type="button" class="btn-outline" onclick="resetProductForm()">Batalkan</button>
            </div>
        </div>
    </form>
</div>

<script>
    const productForm = document.getElementById('product-form');
    const formMethod = document.getElementById('product-form-method');
    const formTitle = document.getElementById('product-form-title');

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const url = URL.createObjectURL(input.files[0]);
            const preview = document.getElementById('dropzone-preview');
            preview.src = url;
            preview.style.display = 'block';
            document.getElementById('dropzone-text').style.display = 'none';
        }
    }

    function resetProductForm() {
        productForm.reset();
        productForm.action = "{{ route('admin.products.store') }}";
        formMethod.value = 'POST';
        formTitle.textContent = 'Tambah / edit produk';
        document.getElementById('dropzone-preview').style.display = 'none';
        document.getElementById('dropzone-text').style.display = 'block';
    }

    function editProduct(p) {
        productForm.action = `/admin/produk/${p.id}`;
        formMethod.value = 'PUT';
        formTitle.textContent = `Edit: ${p.title}`;

        document.getElementById('input-title').value = p.title;
        document.getElementById('input-category').value = p.category_id;
        document.getElementById('input-price').value = p.price_raw;
        document.getElementById('input-desc').value = p.short_desc || '';
        document.getElementById('input-sizes').value = p.sizes_raw;
        document.getElementById('input-colors').value = p.colors_raw;
        document.getElementById('input-sku').value = p.sku || '';

        const preview = document.getElementById('dropzone-preview');
        preview.src = '/' + p.image;
        preview.style.display = 'block';
        document.getElementById('dropzone-text').style.display = 'none';

        document.getElementById('product-form-card').scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endsection