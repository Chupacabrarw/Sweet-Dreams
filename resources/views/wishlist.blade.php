@extends('layouts.app')

@section('title', 'Wishlist Saya - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/wishlist.css')
@endpush

<div class="wishlist-page-wrapper">
        <div class="wishlist-page-header">
        <div>
            <h1>Wishlist Saya</h1>
            <p><strong>{{ count($wishlistItems) }} Produk</strong> tersimpan di daftar keinginan Anda</p>
        </div>
    </div>

    @if(count($wishlistItems) === 0)
        <div class="wishlist-empty-state">
            <p>Belum ada produk di wishlist kamu.</p>
            <a href="/katalog">Yuk lihat katalog</a>
        </div>
    @else
        <div class="wishlist-grid" id="wishlist-grid">
            @foreach($wishlistItems as $item)
                <div class="wishlist-card" data-product-id="{{ $item['id'] }}">
                    <div class="wishlist-card-img-box">
                        <img src="{{ asset($item['image']) }}" alt="{{ $item['title'] }}">
                        <button class="btn-remove-wishlist" data-action="remove" aria-label="Hapus dari wishlist">
                            <i data-lucide="x" style="width:16px;height:16px;"></i>
                        </button>
                    </div>
                    <div class="wishlist-card-body">
                        <div class="wishlist-card-tag-row">
                            <span class="wishlist-card-tag">{{ $item['category_name'] }}</span>
                            <button class="wishlist-card-heart" data-action="remove" aria-label="Hapus dari wishlist">
                                <i data-lucide="heart" style="width:18px;height:18px;fill:var(--blush);"></i>
                            </button>
                        </div>
                        <h4 class="wishlist-card-title">{{ $item['title'] }}</h4>
                                                <div class="wishlist-card-price-row">
                            <span class="wishlist-card-price">{{ $item['price'] }}</span>
                            <a href="/produk/{{ $item['slug'] }}" class="btn-wishlist-beli">
                                <i data-lucide="eye" style="width:15px;height:15px;"></i>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    function csrfHeaders(extra = {}) {
        return Object.assign({
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }, extra);
    }

    // Hapus dari wishlist (tombol X & tombol hati)
    document.querySelectorAll('[data-action="remove"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.wishlist-card');
            const productId = card.getAttribute('data-product-id');

            fetch('/api/wishlist/toggle', {
                method: 'POST',
                headers: csrfHeaders(),
                body: JSON.stringify({ product_id: productId })
            })
            .then(res => res.json())
            .then(data => {
                card.remove();
                const navBadge = document.querySelector('#btn-wishlist .badge');
                if (navBadge && data.wishlist_count !== undefined) {
                    navBadge.textContent = data.wishlist_count;
                    navBadge.style.display = data.wishlist_count > 0 ? 'flex' : 'none';
                }
                try {
                    localStorage.setItem('sweetdreams_wishlist_sync', Date.now().toString());
                } catch(e) {}
                if (document.querySelectorAll('.wishlist-card').length === 0) {
                    window.location.reload();
                }
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    });

});
</script>
@endsection