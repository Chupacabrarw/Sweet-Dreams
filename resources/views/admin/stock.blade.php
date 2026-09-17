@extends('admin.layout')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Pantau persediaan untuk setiap ukuran dan warna')

@section('content')
<style>
    .admin-table { width:100%; border-collapse:collapse; }
    .admin-table th { text-align:left; font-size:0.72rem; text-transform:uppercase; color:#8a6a72; padding:0.75rem 0.5rem; border-bottom:1px solid #f1e4e7; }
    .admin-table td { padding:0.9rem 0.5rem; border-bottom:1px solid #f1e4e7; font-size:0.88rem; vertical-align:middle; }
    .status-pill { padding:0.25rem 0.7rem; border-radius:20px; font-size:0.76rem; font-weight:600; }
    .status-pill.aman { background:#e3f9ee; color:#1e9e64; }
    .status-pill.menipis { background:#fff3d9; color:#b7791f; }
    .status-pill.kritis { background:#fde2e6; color:#c53660; }
    .status-pill.habis { background:#f1e4e7; color:#8a6a72; }
    .action-link { color:#d44d6e; font-weight:600; font-size:0.85rem; cursor:pointer; }
    .stock-edit-form { display:none; align-items:center; gap:0.4rem; }
    .stock-edit-form input { width:70px; padding:0.35rem 0.5rem; border:1px solid #e5dde0; border-radius:8px; }
    .stock-edit-form button { background:#d44d6e; color:#fff; border:none; border-radius:8px; padding:0.35rem 0.7rem; font-size:0.78rem; cursor:pointer; }
</style>

@if(session('success'))
    <div style="background:#e3f9ee;color:#1e9e64;padding:0.8rem 1.2rem;border-radius:10px;margin-bottom:1.2rem;font-size:0.88rem;">
        {{ session('success') }}
    </div>
@endif

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
    <h3 style="font-size:1rem; margin-bottom:1rem;">Stok per varian</h3>

    @if($rows->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">Belum ada varian produk. Tambah/edit produk dulu di halaman Manajemen Produk supaya variannya otomatis terbuat.</p>
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
                            <span class="action-link" id="toggle-{{ $row['id'] }}" onclick="toggleStockEdit({{ $row['id'] }})">Ubah jumlah</span>
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