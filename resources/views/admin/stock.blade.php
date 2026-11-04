@extends('admin.layout')

@section('title', 'Manajemen Stok')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Pilih produk untuk mengatur stok tiap ukuran dan warna')

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
        <h3 style="font-size:1rem; margin:0;">Produk ({{ $products->count() }})</h3>
        <form method="GET" action="{{ route('admin.stock') }}" class="stock-search-form">
            <input type="text" name="q" value="{{ $search ?? '' }}" class="stock-search-input" placeholder="Cari nama produk, SKU atau kategori">
            @if(!empty($search))
                <a href="{{ route('admin.stock') }}" class="action-link">Reset</a>
            @endif
        </form>
    </div>

    @if($products->isEmpty())
        <p style="color:#8a6a72; font-size:0.88rem;">{{ !empty($search) ? 'Tidak ada produk yang cocok dengan pencarian.' : 'Belum ada produk. Tambah produk dulu di halaman Manajemen Produk.' }}</p>
    @else
        <div class="stock-product-grid">
            @foreach($products as $p)
                <a href="{{ route('admin.stock.product', $p['id']) }}" class="stock-product-card">
                    <div class="stock-product-img">
                        <img src="{{ asset($p['image']) }}" alt="{{ $p['title'] }}" loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                        <span class="status-pill {{ $p['indicator_class'] }}">{{ $p['indicator_label'] }}</span>
                    </div>
                    <div class="stock-product-body">
                        <div class="stock-product-cat">{{ $p['category'] }}</div>
                        <div class="stock-product-title">{{ $p['title'] }}</div>
                        <div class="stock-product-meta">
                            <span><strong>{{ number_format($p['total'], 0, ',', '.') }}</strong> unit</span>
                            <span>{{ $p['variant_count'] }} varian</span>
                        </div>
                        @if($p['empty_variants'] > 0 && $p['total'] > 0)
                            <div class="stock-product-warn">{{ $p['empty_variants'] }} varian habis</div>
                        @endif
                        <span class="stock-product-cta">Atur stok →</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
