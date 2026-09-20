@extends('admin.layout')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola katalog, varian, harga, status produk, dan kategori')

@section('content')
<style>
    .admin-toolbar { display:flex; justify-content:space-between; align-items: center; gap:1rem; margin-bottom:1.5rem; flex-wrap: wrap; }
    .admin-search-input {
        flex:1; min-width: 250px; max-width:400px; padding:0.7rem 1rem; border:1px solid #e5dde0;
        border-radius:10px; font-size:0.88rem; background:#fff;
    }
    .toolbar-actions { display: flex; gap: 0.75rem; }
    .btn-pink { background:#d44d6e; color:#fff; border:none; border-radius:10px; padding:0.7rem 1.2rem; font-weight:600; font-size:0.88rem; cursor:pointer; display:inline-flex; align-items:center; gap:0.5rem; }
    .btn-pink:hover { background:#b83d5c; }
    .btn-outline { background:#fff; border:1px solid #e5dde0; border-radius:10px; padding:0.7rem 1.2rem; font-weight:600; font-size:0.88rem; cursor:pointer; color: #3a2a2e; }
    .btn-outline:hover { background:#fef5f7; border-color: #d44d6e; color: #d44d6e; }

    /* Tabs */
    .admin-tabs { display:flex; gap:0.5rem; margin-bottom:1.5rem; border-bottom:1px solid #f1e4e7; overflow-x:auto; padding-bottom:1px; }
    .admin-tab { 
        padding:0.75rem 1.25rem; background:transparent; border:none; border-bottom:2px solid transparent;
        font-size:0.9rem; font-weight:600; color:#8a6a72; cursor:pointer; white-space:nowrap;
        transition:all 0.2s;
    }
    .admin-tab:hover { color:#d44d6e; }
    .admin-tab.active { color:#d44d6e; border-bottom-color:#d44d6e; }
    .tab-content { display:none; }
    .tab-content.active { display:block; }

    /* Tables */
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 1rem; border-bottom:1px solid #f1e4e7; }
    .admin-table td { padding:1rem; border-bottom:1px solid #f1e4e7; font-size:0.88rem; vertical-align:middle; }
    .prod-cell { display:flex; align-items:center; gap:0.75rem; }
    .prod-cell img { width:44px; height:44px; border-radius:8px; object-fit:cover; border: 1px solid #f1e4e7; }
    .status-pill { padding:0.25rem 0.7rem; border-radius:20px; font-size:0.76rem; font-weight:600; white-space: nowrap; }
    .status-pill.aktif { background:#e3f9ee; color:#1e9e64; }
    .status-pill.tipis { background:#fff3d9; color:#b7791f; }
    .status-pill.nonaktif { background:#fde2e6; color:#c53660; }
    .action-link { color:#d44d6e; font-weight:600; font-size:0.85rem; cursor:pointer; margin-right:0.75rem; background:none; border:none; padding:0; text-decoration:none; }
    .action-link:hover { text-decoration:underline; }

    /* Form Grid */
    .product-form-grid { display:grid; grid-template-columns:220px 1fr; gap:2rem; }
    .dropzone {
        border:2px dashed #f2c9d3; border-radius:14px; height:220px;
        display:flex; align-items:center; justify-content:center; text-align:center;
        color:#c9808f; font-size:0.85rem; cursor:pointer; overflow:hidden; position:relative;
        background: #fef5f7; transition: all 0.2s;
    }
    .dropzone:hover { border-color: #d44d6e; background: #fff0f4; }
    .dropzone img { width:100%; height:100%; object-fit:cover; position:absolute; inset:0; }
    .form-row { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem; }
    .form-row.full { grid-template-columns:1fr; }
    .form-group label { display:block; font-size:0.8rem; font-weight:600; margin-bottom:0.4rem; color: #3a2a2e; }
    .form-group input, .form-group select, .form-group textarea {
        width:100%; padding:0.65rem 0.85rem; border:1px solid #e5dde0; border-radius:10px; font-size:0.88rem; font-family:inherit;
        transition: border-color 0.2s;
    }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
        outline: none; border-color: #d44d6e;
    }
    
    /* Modal / Popup for Category */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(42,31,34,0.5); backdrop-filter: blur(4px);
        display: none; align-items: center; justify-content: center; z-index: 1000;
        opacity: 0; transition: opacity 0.3s;
    }
    .modal-overlay.show { display: flex; opacity: 1; }
    .modal-card {
        background: #fff; width: 100%; max-width: 400px; border-radius: 20px;
        padding: 2rem; box-shadow: 0 12px 48px rgba(42,31,34,0.12);
        transform: translateY(20px); transition: transform 0.3s;
    }
    .modal-overlay.show .modal-card { transform: translateY(0); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
    .modal-header h3 { font-size: 1.25rem; color: #2a1f22; margin: 0; }
    .modal-close { background: none; border: none; color: #a8939a; cursor: pointer; display: flex; }
    .modal-close:hover { color: #d44d6e; }

    /* Category Header inside Tab */
    .cat-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 0 1rem; }
    .cat-header h4 { font-size: 1.1rem; color: #3a2a2e; margin: 0; }
    .cat-actions { display: flex; gap: 0.75rem; }

    .empty-state { text-align: center; padding: 3rem 1rem; color: #8a6a72; }
</style>

@if(session('success'))
    <div style="background:#e3f9ee;color:#1e9e64;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;display:flex;align-items:center;gap:0.5rem;">
        <i data-lucide="check-circle" style="width:18px;height:18px;"></i>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fde2e6;color:#c53660;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;display:flex;align-items:center;gap:0.5rem;">
        <i data-lucide="alert-circle" style="width:18px;height:18px;"></i>
        {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div style="background:#fde2e6;color:#c53660;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;">
        <ul style="margin:0; padding-left:1.5rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="admin-toolbar">
    <form method="GET" action="{{ route('admin.products') }}" style="display:flex; flex:1; max-width:400px; position:relative;">
        <i data-lucide="search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#a8939a; width:18px; height:18px;"></i>
        <input type="text" name="q" value="{{ $search }}" class="admin-search-input" style="padding-left:36px; max-width:100%;" placeholder="Cari nama atau SKU produk...">
        @if($activeCat)<input type="hidden" name="cat" value="{{ $activeCat }}">@endif
    </form>
    
    <div class="toolbar-actions">
        <button type="button" class="btn-outline" onclick="openCategoryModal()">
            <i data-lucide="folder-plus" style="width:18px;height:18px;"></i> Kategori
        </button>
        <button type="button" class="btn-pink" onclick="resetProductForm(); document.getElementById('product-form-card').scrollIntoView({behavior:'smooth'});">
            <i data-lucide="plus" style="width:18px;height:18px;"></i> Tambah Produk
        </button>
    </div>
</div>

<div class="admin-card" style="margin-bottom:1.5rem; padding: 1.5rem 1rem 0 1rem;">
    
    {{-- TABS --}}
    <div class="admin-tabs">
        <button class="admin-tab {{ !$activeCat ? 'active' : '' }}" onclick="switchTab('semua')">
            Semua Produk ({{ $allProducts->count() }})
        </button>
        @foreach($categories as $cat)
            <button class="admin-tab {{ $activeCat == $cat->id ? 'active' : '' }}" onclick="switchTab('cat-{{ $cat->id }}')">
                {{ $cat->name }} ({{ $cat->products_count }})
            </button>
        @endforeach
    </div>

    {{-- TAB CONTENTS --}}
    
    <!-- Semua Produk -->
    <div class="tab-content {{ !$activeCat ? 'active' : '' }}" id="tab-semua">
        @if($allProducts->count() > 0)
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produk</th><th>Kategori</th><th>Varian</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allProducts as $p)
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
                                <button type="button" class="action-link" onclick='editProduct(@json($p))'>Edit</button>
                                <form method="POST" action="{{ route('admin.products.destroy', $p['id']) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus produk ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-link">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i data-lucide="package" style="width:48px;height:48px;color:#f2dce3;margin-bottom:1rem;"></i>
            <p>Belum ada produk yang ditambahkan atau tidak ada hasil pencarian.</p>
        </div>
        @endif
    </div>

    <!-- Per Kategori -->
    @foreach($categories as $cat)
    <div class="tab-content {{ $activeCat == $cat->id ? 'active' : '' }}" id="tab-cat-{{ $cat->id }}">
        <div class="cat-header">
            <h4>Kategori: {{ $cat->name }}</h4>
            <div class="cat-actions">
                <button type="button" class="action-link" onclick="editCategory({{ $cat->id }}, '{{ $cat->name }}')"><i data-lucide="edit-2" style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>Edit Nama</button>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus kategori {{ $cat->name }}? Pastikan tidak ada produk di dalamnya.');">
                    @csrf @method('DELETE')
                    <button type="submit" class="action-link" style="color:#c53660;"><i data-lucide="trash-2" style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>Hapus Kategori</button>
                </form>
            </div>
        </div>

        @php $catProducts = $productsByCategory[$cat->id] ?? collect(); @endphp
        
        @if($catProducts->count() > 0)
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Produk</th><th>Varian</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($catProducts as $p)
                        <tr>
                            <td>
                                <div class="prod-cell">
                                    <img src="{{ asset($p['image']) }}" alt="{{ $p['title'] }}">
                                    <span>{{ $p['title'] }}</span>
                                </div>
                            </td>
                            <td>{{ $p['sizes'] }} · {{ $p['colors_count'] }} warna</td>
                            <td>{{ $p['price'] }}</td>
                            <td>{{ $p['stock'] }}</td>
                            <td><span class="status-pill {{ $p['status_class'] }}">{{ $p['status_label'] }}</span></td>
                            <td>
                                <button type="button" class="action-link" onclick='editProduct(@json($p))'>Edit</button>
                                <form method="POST" action="{{ route('admin.products.destroy', $p['id']) }}" style="display:inline;" onsubmit="return confirm('Yakin hapus produk ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-link">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <p>Belum ada produk di kategori ini.</p>
            <button type="button" class="btn-outline" style="margin-top:1rem;" onclick="resetProductForm({{ $cat->id }}); document.getElementById('product-form-card').scrollIntoView({behavior:'smooth'});">
                Tambah Produk ke {{ $cat->name }}
            </button>
        </div>
        @endif
    </div>
    @endforeach

</div>

{{-- PRODUCT FORM --}}
<div class="admin-card" id="product-form-card">
    <h3 id="product-form-title" style="font-size:1.2rem; color:#2a1f22; margin-bottom:1.5rem;">Tambah Produk Baru</h3>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" id="product-form">
        @csrf
        <input type="hidden" name="_method" id="product-form-method" value="POST">

        <div class="product-form-grid">
            <label class="dropzone" id="dropzone-label">
                <span id="dropzone-text">
                    <i data-lucide="image-plus" style="width:32px;height:32px;margin-bottom:0.5rem;color:#d44d6e;"></i><br>
                    Unggah foto produk<br><small style="color:#a8939a;">PNG/JPG maks. 5MB</small>
                </span>
                <img id="dropzone-preview" style="display:none;">
                <input type="file" name="image" id="input-image" accept="image/*" style="display:none;" onchange="previewImage(this)">
            </label>

            <div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="title" id="input-title" required placeholder="Contoh: Kimono Silk Premium">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" id="input-category" required>
                            <option value="">Pilih kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" id="input-price" required placeholder="Contoh: 150000" min="0">
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="short_desc" id="input-desc" rows="2" placeholder="Deskripsi menarik tentang produk ini..."></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Ukuran <small style="font-weight:normal;color:#a8939a;">(Pisah koma)</small></label>
                        <input type="text" name="sizes" id="input-sizes" placeholder="S, M, L, XL">
                    </div>
                    <div class="form-group">
                        <label>Warna <small style="font-weight:normal;color:#a8939a;">(Pisah koma)</small></label>
                        <input type="text" name="colors" id="input-colors" placeholder="Pink, Hitam, Putih">
                    </div>
                    <div class="form-group">
                        <label>SKU <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="text" name="sku" id="input-sku" placeholder="SD-KM-01">
                    </div>
                </div>

                <div style="display:flex; gap:1rem; margin-top:1rem;">
                    <button type="submit" class="btn-pink">Simpan Produk</button>
                    <button type="button" class="btn-outline" onclick="resetProductForm()">Batalkan</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- CATEGORY MODAL --}}
<div class="modal-overlay" id="category-modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3 id="cat-modal-title">Tambah Kategori</h3>
            <button class="modal-close" onclick="closeCategoryModal()">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.categories.store') }}" id="cat-form">
            @csrf
            <input type="hidden" name="_method" id="cat-form-method" value="POST">
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label>Nama Kategori</label>
                <input type="text" name="name" id="cat-input-name" required placeholder="Contoh: Lingerie">
            </div>
            <button type="submit" class="btn-pink" style="width:100%; justify-content:center;">Simpan Kategori</button>
        </form>
    </div>
</div>

<script>
    // Tab switching
    function switchTab(tabId) {
        document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        event.currentTarget.classList.add('active');
        document.getElementById('tab-' + tabId).classList.add('active');
        
        // Update URL cat parameter without reload (optional, for refresh persistence)
        const url = new URL(window.location);
        if(tabId === 'semua') {
            url.searchParams.delete('cat');
        } else {
            url.searchParams.set('cat', tabId.replace('cat-', ''));
        }
        window.history.replaceState({}, '', url);
    }

    // Product Form
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

    function resetProductForm(categoryId = '') {
        productForm.reset();
        productForm.action = "{{ route('admin.products.store') }}";
        formMethod.value = 'POST';
        formTitle.textContent = 'Tambah Produk Baru';
        document.getElementById('dropzone-preview').style.display = 'none';
        document.getElementById('dropzone-text').style.display = 'block';
        
        if(categoryId) {
            document.getElementById('input-category').value = categoryId;
        }
    }

    function editProduct(p) {
        productForm.action = `/admin/produk/${p.id}`;
        formMethod.value = 'PUT';
        formTitle.textContent = `Edit Produk: ${p.title}`;

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

    // Category Modal
    const catModal = document.getElementById('category-modal');
    const catForm = document.getElementById('cat-form');
    const catMethod = document.getElementById('cat-form-method');
    const catTitle = document.getElementById('cat-modal-title');
    const catInput = document.getElementById('cat-input-name');

    function openCategoryModal() {
        catForm.action = "{{ route('admin.categories.store') }}";
        catMethod.value = 'POST';
        catTitle.textContent = 'Tambah Kategori';
        catInput.value = '';
        catModal.classList.add('show');
    }

    function editCategory(id, name) {
        catForm.action = `/admin/kategori/${id}`;
        catMethod.value = 'PUT';
        catTitle.textContent = 'Edit Kategori';
        catInput.value = name;
        catModal.classList.add('show');
    }

    function closeCategoryModal() {
        catModal.classList.remove('show');
    }

    // Close modal on outside click
    catModal.addEventListener('click', function(e) {
        if(e.target === catModal) closeCategoryModal();
    });

    // Run Lucide again to catch new icons
    if(window.lucide) {
        lucide.createIcons();
    }
</script>
@endsection