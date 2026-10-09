@extends('admin.layout')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Pantau persediaan untuk setiap ukuran dan warna')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/admin/stock.css')
@endpush

<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-label">Total unit</div>
        <div class="admin-stat-value">{{ number_format($totalUnit, 0, ',', '.') }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Stok menipis</div>
        <div class="admin-stat-value">{{ $menipis }} varian</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Stok habis</div>
        <div class="admin-stat-value" style="color:#c53660;">{{ $habis }} varian</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-label">Diperbarui</div>
        <div class="admin-stat-value" style="font-size:1.1rem;">{{ $lastUpdated ?? '-' }}</div>
    </div>
</div>

<div class="admin-card">
    <div class="stock-toolbar">
        <h3 style="font-size:1rem; margin:0;">Stok per varian</h3>
        <form method="GET" action="{{ route('admin.stock') }}" class="stock-search-form">
            <input type="text" name="q" value="{{ $search ?? '' }}" class="stock-search-input" placeholder="Cari produk, SKU, ukuran, warna...">
            @if(!empty($search))
                <a href="{{ route('admin.stock') }}" class="action-link">Reset</a>
            @endif
        </form>
    </div>

    @if($rows->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">{{ !empty($search) ? 'Tidak ada varian yang cocok dengan pencarian.' : 'Belum ada varian produk. Tambah/edit produk dulu di halaman Manajemen Produk supaya variannya otomatis terbuat.' }}</p>
    @else
        <table class="admin-table">
            <thead>
                <tr><th>SKU</th><th>Produk</th><th>Ukuran</th><th>Warna</th><th>Stok</th><th>Indikator</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row['sku'] }}</td>
                        <td>{{ $row['product'] }}</td>
                        <td>{{ $row['size'] }}</td>
                        <td>{{ $row['color'] }}</td>
                        <td>
                            <span id="stock-value-{{ $row['id'] }}">{{ $row['stock'] }}</span>
                        </td>
                        <td><span class="status-pill {{ $row['indicator_class'] }}">{{ $row['indicator_label'] }}</span></td>
                        <td>
                            <button type="button" class="stock-edit-toggle" id="toggle-{{ $row['id'] }}" onclick="toggleStockEdit({{ $row['id'] }})">Ubah jumlah</button>
                            <form class="stock-edit-form" id="form-{{ $row['id'] }}" method="POST" action="{{ route('admin.stock.update', $row['id']) }}">
                                @csrf @method('PUT')
                                <input type="number" name="stock" min="0" value="{{ $row['stock'] }}" required>
                                <button type="submit">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
    function toggleStockEdit(id) {
        document.getElementById('toggle-' + id).style.display = 'none';
        document.getElementById('form-' + id).style.display = 'inline-flex';
    }
</script>
@endsection