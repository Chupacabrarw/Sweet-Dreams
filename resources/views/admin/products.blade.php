@extends('admin.layout')

@section('title', 'Manajemen Produk')
@section('page-title', 'Manajemen Produk')
@section('page-subtitle', 'Kelola katalog, varian, harga, status produk, dan kategori')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/products.css')
@endpush

<div class="admin-products-page">
<div class="admin-toolbar is-sticky">
    <form method="GET" action="{{ route('admin.products') }}" style="display:flex; flex:1; max-width:400px; position:relative;">
        <i data-lucide="search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#a8939a; width:18px; height:18px;"></i>
        <input type="text" name="q" value="{{ $search }}" class="admin-search-input" style="padding-left:36px; max-width:100%;" placeholder="Cari nama atau SKU produk...">
        @if($activeCat)<input type="hidden" name="cat" value="{{ $activeCat }}">@endif
    </form>
    
    <div class="toolbar-actions">
        <button type="button" class="btn-outline" onclick="openCategoryModal()">
            <i data-lucide="folder-plus" style="width:18px;height:18px;"></i> Kategori
        </button>
        <button type="button" class="btn-pink" onclick="resetProductForm(); document.getElementById('product-form-card').scrollIntoView({behavior:'smooth', block:'start'});">
            <i data-lucide="plus" style="width:18px;height:18px;"></i> Tambah Produk
        </button>
    </div>
</div>

<div class="admin-card" id="products-list-card" style="margin-bottom:1.5rem; padding: 1.5rem 1rem 0 1rem;">
    
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
            <button type="button" class="btn-outline" style="margin-top:1rem;" onclick="resetProductForm({{ $cat->id }}); document.getElementById('product-form-card').scrollIntoView({behavior:'smooth', block:'start'});">
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
                        <input type="text" name="title" id="input-title" required placeholder="Contoh: Kimono Silk Premium" value="{{ old('title') }}">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="category_id" id="input-category" required>
                            <option value="">Pilih kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" data-sku-prefix="{{ \App\Models\Product::skuPrefix($cat->slug) }}" @selected(old('category_id') == $cat->id)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Harga (Rp)</label>
                        <input type="number" name="price" id="input-price" required placeholder="Contoh: 150000" min="0" value="{{ old('price') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Harga Coret (Rp) <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="number" name="original_price" id="input-original-price" placeholder="Contoh: 200000" min="0" value="{{ old('original_price') }}">
                        <small style="color:#a8939a;">Diisi kalau produk diskon.</small>
                    </div>
                    <div class="form-group">
                        <label>Label Diskon <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="text" name="discount" id="input-discount" placeholder="Contoh: -16%" maxlength="20" value="{{ old('discount') }}">
                        <small style="color:#a8939a;">Kosongkan = otomatis dihitung dari harga coret.</small>
                    </div>
                </div>

                {{-- Baris Pengiriman: berat + dimensi dalam satu grid horizontal --}}
                <div class="form-row cols-4">
                    <div class="form-group">
                        <label>Berat (gram)</label>
                        <input type="number" name="weight" id="input-weight" value="{{ old('weight', 250) }}" placeholder="Contoh: 250" min="1" max="50000">
                    </div>
                    <div class="form-group">
                        <label>Panjang (cm) <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="number" name="length" id="input-length" placeholder="Contoh: 30" min="1" max="500" value="{{ old('length') }}">
                    </div>
                    <div class="form-group">
                        <label>Lebar (cm) <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="number" name="width" id="input-width" placeholder="Contoh: 20" min="1" max="500" value="{{ old('width') }}">
                    </div>
                    <div class="form-group">
                        <label>Tinggi (cm) <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="number" name="height" id="input-height" placeholder="Contoh: 10" min="1" max="500" value="{{ old('height') }}">
                    </div>
                </div>
                <small class="field-hint">Berat dipakai tiap checkout. Dimensi opsional — isi hanya jika packing besar; kosong = pakai berat aktual.</small>


                <div class="form-row full">
                    <div class="form-group">
                        <label>Deskripsi Singkat</label>
                        <textarea name="short_desc" id="input-desc" rows="2" placeholder="Deskripsi menarik tentang produk ini...">{{ old('short_desc') }}</textarea>
                    </div>
                </div>

                <div class="form-row full">
                    <div class="form-group">
                        <label>Deskripsi Lengkap</label>
                        <textarea name="long_desc" id="input-long-desc" rows="6" placeholder="Detail bahan, perawatan, isi paket, dll...">{{ old('long_desc') }}</textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Ukuran <small style="font-weight:normal;color:#a8939a;">(Pisah koma)</small></label>
                        <input type="text" name="sizes" id="input-sizes" placeholder="S, M, L, XL" value="{{ old('sizes') }}">
                    </div>
                    <div class="form-group">
                        <div class="color-picker-heading">
                            <label>Warna</label>
                            <button type="button" class="action-link" onclick="openColorModal()">+ Tambah warna</button>
                        </div>
                        <div class="product-color-options" id="product-color-options">
                            @foreach($colorOptions as $color)
                                <label class="product-color-option" data-color-slug="{{ $color->slug }}">
                                    <input type="checkbox" name="colors[]" value="{{ $color->id }}" data-color-name="{{ $color->name }}" @checked(in_array($color->id, old('colors', [])))>
                                    <span class="product-color-swatch" style="--swatch-color:{{ $color->hex }}"></span>
                                    <span>{{ $color->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label>SKU <small style="font-weight:normal;color:#a8939a;">(Opsional)</small></label>
                        <input type="text" name="sku" id="input-sku" placeholder="Otomatis saat disimpan" value="{{ old('sku') }}">
                        <small style="color:#a8939a;">Kosongkan untuk membuat SKU sesuai kategori, misalnya BT-00001.</small>
                    </div>
                </div>

                <section class="product-gallery-manager" aria-labelledby="gallery-manager-title">
                    <div class="product-gallery-manager-heading">
                        <div>
                            <strong id="gallery-manager-title">Galeri Foto</strong>
                            <p>Foto utama dipakai di katalog. Tambahkan foto umum, lalu foto khusus warna jika tersedia.</p>
                        </div>
                    </div>
                    <div class="gallery-upload-group">
                        <label for="gallery-general-input">Foto tambahan umum</label>
                        <input type="file" id="gallery-general-input" name="gallery_general[]" accept="image/png,image/jpeg,image/webp" multiple>
                        <small>Maksimal 8 foto, masing-masing 5 MB. Foto ini tampil untuk semua warna.</small>
                        <div class="gallery-image-previews" id="gallery-general-previews"></div>
                    </div>
                    <div class="color-gallery-fields" id="color-gallery-fields"></div>
                </section>
                <p class="product-variant-help">Ukuran dan warna membuat pilihan varian produk. Stok setiap pasangan ukuran–warna awalnya 0; isi jumlah stoknya satu per satu di halaman <a href="{{ route('admin.stock') }}">Kelola Stok</a>. Filter katalog hanya menampilkan varian yang stoknya tersedia.</p>

                <section class="product-stock-summary" id="product-stock-summary" hidden aria-live="polite">
                    <div class="product-stock-summary-header">
                        <div>
                            <strong>Stok produk saat ini</strong>
                            <span><span id="product-stock-total">0</span> unit</span>
                        </div>
                        <a href="{{ route('admin.stock') }}">Kelola stok per varian</a>
                    </div>
                    <ul id="product-variant-stock-list"></ul>
                </section>

                <div style="display:flex; gap:1rem; margin-top:1rem;">
                    <button type="submit" class="btn-pink">Simpan Produk</button>
                    <button type="button" class="btn-outline" onclick="resetProductForm()">Batalkan</button>
                </div>
            </div>
        </div>
    </form>
</div>

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

<div class="modal-overlay" id="color-modal">
    <div class="modal-card">
        <div class="modal-header">
            <h3>Tambah Warna</h3>
            <button type="button" class="modal-close" aria-label="Tutup" onclick="closeColorModal()">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>
        <form id="color-form">
            <div class="form-group" style="margin-bottom:1rem;">
                <label for="color-input-name">Nama warna</label>
                <input type="text" id="color-input-name" name="name" required maxlength="60" placeholder="Contoh: Ungu Muda">
            </div>
            <div class="form-group" style="margin-bottom:1rem;">
                <label for="color-input-hex">Pilih tampilan warna</label>
                <input type="color" id="color-input-hex" name="hex" value="#8665a7" required>
            </div>
            <p class="color-form-error" id="color-form-error" role="alert"></p>
            <button type="submit" class="btn-pink" style="width:100%; justify-content:center;">Simpan Warna</button>
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
    const categoryInput = document.getElementById('input-category');
    const skuInput = document.getElementById('input-sku');
    const productColorOptions = document.getElementById('product-color-options');
    const colorGalleryFields = document.getElementById('color-gallery-fields');
    const galleryGeneralInput = document.getElementById('gallery-general-input');
    const galleryGeneralPreviews = document.getElementById('gallery-general-previews');
    const colorModal = document.getElementById('color-modal');
    const colorForm = document.getElementById('color-form');
    const colorFormError = document.getElementById('color-form-error');
    const colorOptions = @json($colorOptionsData);

    function addColorOption(color, checked = false) {
        if (productColorOptions.querySelector(`[data-color-slug="${CSS.escape(color.slug)}"]`)) {
            return;
        }

        const label = document.createElement('label');
        label.className = 'product-color-option';
        label.dataset.colorSlug = color.slug;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'colors[]';
        checkbox.value = color.id;
        checkbox.dataset.colorName = color.name;
        checkbox.checked = checked;

        const swatch = document.createElement('span');
        swatch.className = 'product-color-swatch';
        swatch.style.setProperty('--swatch-color', color.hex);

        const name = document.createElement('span');
        name.textContent = color.name;

        label.append(checkbox, swatch, name);
        productColorOptions.appendChild(label);
        colorOptions.push(color);
        syncColorGalleryFields();
    }

    const colorGalleryFieldById = new Map();
    const productAssetBase = @json(asset(''));

    function galleryImageUrl(path) {
        return new URL(path.replace(/^\/+/, ''), productAssetBase).href;
    }

    function addExistingGalleryPreview(container, path, hiddenInputName) {
        const preview = document.createElement('div');
        preview.className = 'gallery-image-preview';

        const image = document.createElement('img');
        image.src = galleryImageUrl(path);
        image.alt = 'Foto galeri produk';

        const keepInput = document.createElement('input');
        keepInput.type = 'hidden';
        keepInput.name = hiddenInputName;
        keepInput.value = path;

        const removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.className = 'gallery-image-remove';
        removeButton.textContent = 'Hapus';
        removeButton.addEventListener('click', () => preview.remove());

        preview.append(image, keepInput, removeButton);
        container.appendChild(preview);
    }

    function showSelectedGalleryFiles(input, container) {
        container.replaceChildren();
        Array.from(input.files || []).forEach(file => {
            const preview = document.createElement('div');
            preview.className = 'gallery-image-preview is-new';

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = file.name;

            const fileName = document.createElement('span');
            fileName.textContent = file.name;

            preview.append(image, fileName);
            container.appendChild(preview);
        });
    }

    function createColorGalleryField(color, existingPaths = []) {
        const key = String(color.id);
        if (colorGalleryFieldById.has(key)) {
            return colorGalleryFieldById.get(key);
        }

        const section = document.createElement('section');
        section.className = 'gallery-upload-group color-gallery-group';
        section.dataset.colorId = key;

        const heading = document.createElement('label');
        heading.htmlFor = `color-gallery-input-${key}`;
        heading.textContent = `Foto warna ${color.name}`;

        const input = document.createElement('input');
        input.type = 'file';
        input.id = `color-gallery-input-${key}`;
        input.name = `color_gallery[${key}][]`;
        input.accept = 'image/png,image/jpeg,image/webp';
        input.multiple = true;
        input.addEventListener('change', () => showSelectedGalleryFiles(input, previews));

        const help = document.createElement('small');
        help.textContent = 'Opsional, maksimal 8 foto tambahan untuk warna ini; tiap foto maksimal 5 MB.';

        const previews = document.createElement('div');
        previews.className = 'gallery-image-previews';
        existingPaths.forEach(path => {
            addExistingGalleryPreview(previews, path, `keep_color_gallery[${key}][]`);
        });

        section.append(heading, input, help, previews);
        colorGalleryFields.appendChild(section);
        colorGalleryFieldById.set(key, section);
        return section;
    }

    function syncColorGalleryFields(galleryByColorId = {}) {
        const selectedColorIds = new Set(
            Array.from(productColorOptions.querySelectorAll('input[type="checkbox"]:checked'))
                .map(checkbox => String(checkbox.value))
        );

        selectedColorIds.forEach(colorId => {
            const color = colorOptions.find(option => String(option.id) === colorId);
            if (color) {
                createColorGalleryField(color, galleryByColorId[colorId] || []);
            }
        });

        colorGalleryFieldById.forEach((section, colorId) => {
            section.hidden = !selectedColorIds.has(colorId);
        });
    }

    galleryGeneralInput.addEventListener('change', () => {
        showSelectedGalleryFiles(galleryGeneralInput, galleryGeneralPreviews);
    });
    productColorOptions.addEventListener('change', event => {
        if (event.target.matches('input[type="checkbox"]')) {
            syncColorGalleryFields();
        }
    });

    window.openColorModal = function() {
        colorForm.reset();
        colorFormError.textContent = '';
        colorModal.classList.add('show');
        document.getElementById('color-input-name').focus();
    };

    window.closeColorModal = function() {
        colorModal.classList.remove('show');
    };

    colorModal.addEventListener('click', (event) => {
        if (event.target === colorModal) closeColorModal();
    });

    colorForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        colorFormError.textContent = '';

        try {
            const response = await fetch("{{ route('admin.colors.store') }}", {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    name: document.getElementById('color-input-name').value.trim(),
                    hex: document.getElementById('color-input-hex').value,
                }),
            });
            const result = await response.json();

            if (!response.ok) {
                colorFormError.textContent = Object.values(result.errors || {})
                    .flat()
                    .join(' ');
                if (!colorFormError.textContent) {
                    colorFormError.textContent = result.message || 'Warna gagal disimpan. Coba lagi.';
                }
                return;
            }

            addColorOption(result.color, true);
            closeColorModal();
        } catch (error) {
            colorFormError.textContent = 'Warna gagal disimpan karena server tidak dapat dihubungi. Coba lagi.';
            console.error('Gagal menyimpan warna produk:', error);
        }
    });

    function updateSkuPlaceholder() {
        const prefix = categoryInput.selectedOptions[0]?.dataset.skuPrefix;
        skuInput.placeholder = prefix ? `${prefix}-XXXXX (otomatis)` : 'Otomatis saat disimpan';
    }

    categoryInput.addEventListener('change', updateSkuPlaceholder);

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const url = URL.createObjectURL(input.files[0]);
            const preview = document.getElementById('dropzone-preview');
            preview.src = url;
            preview.style.display = 'block';
            document.getElementById('dropzone-text').style.display = 'none';
            document.getElementById('dropzone-label').classList.remove('dropzone-error');
        }
    }

    // Cegah submit saat tambah baru tapi gambar belum dipilih (file input
    // tidak bisa diingat browser, jadi validasi di depan sebelum server me-reset)
    productForm.addEventListener('submit', function(e) {
        const isCreate = formMethod.value === 'POST';
        const hasFile = document.getElementById('input-image').files.length > 0;
        if (isCreate && !hasFile) {
            e.preventDefault();
            document.getElementById('dropzone-label').classList.add('dropzone-error');
            if (window.adminToast) window.adminToast('Foto produk wajib diunggah dulu sebelum simpan.', 'error');
            document.getElementById('product-form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });

    function resetProductForm(categoryId = '') {
        productForm.reset();
        productForm.action = "{{ route('admin.products.store') }}";
        formMethod.value = 'POST';
        formTitle.textContent = 'Tambah Produk Baru';
        document.getElementById('dropzone-preview').style.display = 'none';
        document.getElementById('dropzone-text').style.display = 'block';
        document.getElementById('dropzone-label').classList.remove('dropzone-error');
        document.getElementById('product-stock-summary').hidden = true;
        document.getElementById('product-variant-stock-list').replaceChildren();
        galleryGeneralPreviews.replaceChildren();
        colorGalleryFields.replaceChildren();
        colorGalleryFieldById.clear();
        productColorOptions.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
            checkbox.checked = false;
        });
        
        if(categoryId) {
            categoryInput.value = categoryId;
        }
        updateSkuPlaceholder();
    }

    function editProduct(p) {
        document.getElementById('input-image').value = '';
        galleryGeneralInput.value = '';
        galleryGeneralPreviews.replaceChildren();
        colorGalleryFields.replaceChildren();
        colorGalleryFieldById.clear();
        productForm.action = `/admin/produk/${p.id}`;
        formMethod.value = 'PUT';
        formTitle.textContent = `Edit Produk: ${p.title}`;

        document.getElementById('input-title').value = p.title;
        categoryInput.value = p.category_id;
        document.getElementById('input-price').value = p.price_raw;
        document.getElementById('input-original-price').value = p.original_price_raw ?? '';
        document.getElementById('input-discount').value = p.discount || '';
        document.getElementById('input-weight').value = p.weight ?? 250;
        document.getElementById('input-desc').value = p.short_desc || '';
        document.getElementById('input-long-desc').value = p.long_desc || '';
        document.getElementById('input-length').value = p.length ?? '';
        document.getElementById('input-width').value = p.width ?? '';
        document.getElementById('input-height').value = p.height ?? '';
        document.getElementById('input-sizes').value = p.sizes_raw;
        const selectedColorIds = new Set((p.colors_ids || []).map(Number));
        productColorOptions.querySelectorAll('input[type="checkbox"]').forEach((checkbox) => {
            checkbox.checked = selectedColorIds.has(Number(checkbox.value));
        });
        (p.gallery_general || []).forEach(path => {
            addExistingGalleryPreview(galleryGeneralPreviews, path, 'keep_gallery_general[]');
        });
        syncColorGalleryFields(p.color_gallery || {});
        skuInput.value = p.sku || '';
        updateSkuPlaceholder();
        const stockVariants = p.variant_stocks || [];
        const totalStock = stockVariants.length
            ? stockVariants.reduce((total, variant) => total + Number(variant.stock || 0), 0)
            : Number(p.stock || 0);
        document.getElementById('product-stock-total').textContent = totalStock.toLocaleString('id-ID');
        const stockList = document.getElementById('product-variant-stock-list');
        stockList.replaceChildren();
        if (stockVariants.length) {
            stockVariants.forEach((variant) => {
                const item = document.createElement('li');
                item.textContent = `${variant.size || 'Tanpa ukuran'} / ${variant.color || 'Tanpa warna'}: ${Number(variant.stock || 0).toLocaleString('id-ID')} unit`;
                stockList.appendChild(item);
            });
        } else {
            const item = document.createElement('li');
            item.textContent = 'Stok belum diatur per varian.';
            stockList.appendChild(item);
        }
        document.getElementById('product-stock-summary').hidden = false;

        const preview = document.getElementById('dropzone-preview');
        preview.src = '/' + p.image;
        preview.style.display = 'block';
        document.getElementById('dropzone-text').style.display = 'none';

        document.getElementById('product-form-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
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
