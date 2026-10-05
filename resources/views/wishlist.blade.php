@extends('layouts.app')

@section('title', 'Wishlist Saya - Sweet Dreams')

@section('content')
<style>
    .wishlist-page-wrapper {
        max-width: 1320px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }
    .wishlist-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    .wishlist-page-header h1 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 2.25rem;
        color: var(--ink);
        margin: 0 0 0.35rem;
    }
    .wishlist-page-header p {
        color: var(--ink-muted);
        font-size: 0.95rem;
        margin: 0;
    }
    .wishlist-page-header p strong {
        color: var(--ink);
    }
    .btn-add-all-cart {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--blush);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 0.8rem 1.3rem;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-add-all-cart:hover { background: #b83d5c; }
    .btn-add-all-cart:disabled { opacity: 0.6; cursor: not-allowed; }

    .wishlist-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
    @media (max-width: 1024px) { .wishlist-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 560px) { .wishlist-grid { grid-template-columns: 1fr; } }

    .wishlist-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        transition: box-shadow 0.2s ease;
    }
    .wishlist-card:hover { box-shadow: 0 8px 24px rgba(201,122,140,0.12); }

    .wishlist-card-img-box {
        position: relative;
        aspect-ratio: 3/4;
        overflow: hidden;
    }
    .wishlist-card-img-box img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .btn-remove-wishlist {
        position: absolute;
        top: 0.75rem; right: 0.75rem;
        width: 32px; height: 32px;
        border-radius: 50%;
        background: rgba(255,255,255,0.9);
        border: none;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        color: var(--ink);
    }
    .btn-remove-wishlist:hover { background: #fff; color: var(--blush); }

    .wishlist-card-body { padding: 1rem 1.1rem 1.2rem; }
    .wishlist-card-tag-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.6rem;
    }
    .wishlist-card-tag {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--blush);
        background: #fdeef1;
        padding: 0.25rem 0.6rem;
        border-radius: var(--radius-lg);
    }
    .wishlist-card-heart {
        background: none; border: none; cursor: pointer;
        color: var(--blush);
    }
    .wishlist-card-title {
        font-size: 0.98rem;
        font-weight: 600;
        color: var(--ink);
        margin: 0 0 0.4rem;
    }
    .wishlist-card-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wishlist-card-price { font-weight: 700; color: var(--ink); }
    .btn-wishlist-beli {
        display: flex; align-items: center; gap: 0.4rem;
        background: var(--blush); color: #fff;
        border: none; border-radius: 10px;
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem; font-weight: 600;
        cursor: pointer;
    }
    .btn-wishlist-beli:hover { background: #b83d5c; }

    .wishlist-empty-state {
        text-align: center;
        padding: 4rem 1rem;
        color: var(--ink-muted);
    }
    .wishlist-empty-state a {
        color: var(--blush);
        font-weight: 600;
    }
</style>

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