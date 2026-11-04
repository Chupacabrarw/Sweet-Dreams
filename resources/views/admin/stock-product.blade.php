@extends('admin.layout')

@section('title', 'Stok ' . $product->title)
@section('page-title', 'Atur Stok')
@section('page-subtitle', $product->title)

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/stock.css')
@endpush

<a href="{{ route('admin.stock') }}" class="stock-back-link">← Kembali ke daftar produk</a>

<form method="POST" action="{{ route('admin.stock.product.update', $product) }}" id="stock-form">
    @csrf @method('PUT')

    <div class="admin-card stock-detail-head">
        <img src="{{ asset($product->image) }}" alt="{{ $product->title }}"
             onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
        <div class="stock-detail-info">
            <div class="stock-product-cat">{{ $product->category->name ?? '-' }}</div>
            <h2>{{ $product->title }}</h2>
            <div class="stock-detail-sku">SKU: {{ $product->sku ?: '-' }}</div>
        </div>
        <div class="stock-detail-total">
            <span>Total stok</span>
            <strong id="stock-total">{{ number_format($total, 0, ',', '.') }}</strong>
            <span>unit</span>
        </div>
    </div>

    <div class="admin-card">
        <div class="stock-toolbar">
            <h3 style="font-size:1rem; margin:0;">Stok per ukuran &amp; warna</h3>
            <div class="stock-fill-all">
                <label for="fill-all-value">Isi semua dengan</label>
                <input type="number" id="fill-all-value" min="0" placeholder="0">
                <button type="button" class="stock-edit-toggle" onclick="fillAll()">Terapkan</button>
            </div>
        </div>

        @if($sizes->isEmpty() || $colors->isEmpty())
            <p style="color:#8a6a72; font-size:0.88rem;">Produk ini belum punya ukuran/warna. Atur dulu di halaman <a href="{{ route('admin.products') }}" class="action-link">Manajemen Produk</a>.</p>
        @else
            <div class="stock-matrix-wrap">
                <table class="stock-matrix">
                    <thead>
                        <tr>
                            <th>Ukuran \ Warna</th>
                            @foreach($colors as $color)
                                <th>{{ ucfirst($color) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $size)
                            <tr>
                                <th>{{ strtoupper($size) }}</th>
                                @foreach($colors as $color)
                                    @php $v = $grid[strtolower($size)][strtolower($color)] ?? null; @endphp
                                    <td>
                                        @if($v)
                                            <div class="stock-stepper">
                                                <button type="button" onclick="step(this, -1)" aria-label="Kurangi">−</button>
                                                <input type="number" name="stock[{{ $v->id }}]" value="{{ $v->stock }}" min="0" required
                                                       data-original="{{ $v->stock }}" class="stock-cell-input">
                                                <button type="button" onclick="step(this, 1)" aria-label="Tambah">+</button>
                                            </div>
                                        @else
                                            <span class="stock-cell-none">—</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="stock-save-bar">
        <span id="stock-dirty-note">Belum ada perubahan</span>
        <button type="submit" class="stock-save-btn">Simpan Stok</button>
    </div>
</form>

<script>
    const stockInputs = [...document.querySelectorAll('.stock-cell-input')];
    let stockSubmitting = false;

    function refreshStock() {
        let total = 0, changed = 0;
        stockInputs.forEach(input => {
            const val = Math.max(0, parseInt(input.value, 10) || 0);
            total += val;
            const isChanged = String(val) !== input.dataset.original;
            input.classList.toggle('changed', isChanged);
            input.classList.toggle('zero', val === 0);
            if (isChanged) changed++;
        });
        document.getElementById('stock-total').textContent = total.toLocaleString('id-ID');
        document.getElementById('stock-dirty-note').textContent = changed
            ? `${changed} varian diubah — jangan lupa simpan`
            : 'Belum ada perubahan';
        document.querySelector('.stock-save-bar').classList.toggle('dirty', changed > 0);
    }

    function step(btn, delta) {
        const input = btn.parentElement.querySelector('input');
        input.value = Math.max(0, (parseInt(input.value, 10) || 0) + delta);
        refreshStock();
    }

    function fillAll() {
        const val = parseInt(document.getElementById('fill-all-value').value, 10);
        if (isNaN(val) || val < 0) return;
        stockInputs.forEach(input => input.value = val);
        refreshStock();
    }

    stockInputs.forEach(input => input.addEventListener('input', refreshStock));
    document.getElementById('stock-form').addEventListener('submit', () => stockSubmitting = true);
    window.addEventListener('beforeunload', e => {
        if (!stockSubmitting && stockInputs.some(i => i.classList.contains('changed'))) e.preventDefault();
    });
    refreshStock();
</script>
@endsection
