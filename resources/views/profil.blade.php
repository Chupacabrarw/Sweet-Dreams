@extends('layouts.app')

@section('title', 'Akun Saya - Sweet Dreams')

@section('content')
@push('page-styles')
    @vite('resources/css/pages/profil.css')
@endpush

<div class="profile-page-wrapper">
    {{-- Header --}}
    <div class="profile-header">
        <span class="profile-eyebrow">MY SWEET DREAM</span>
        <h1 id="page-user-greeting">Selamat datang, {{ explode(' ', $user['name'])[0] }}</h1>
        <p>Kelola profil, pantau pesanan, dan temukan kembali koleksi favoritmu.</p>
    </div>

    {{-- Layout Grid --}}
    <div class="profile-layout-grid">

        {{-- Toggle menu akun (khusus HP) --}}
        <button type="button" class="profile-sidebar-toggle" id="profile-sidebar-toggle" aria-expanded="false" aria-controls="profile-sidebar">
            <i data-lucide="menu" style="width:18px;height:18px;"></i>
            <span>Menu Akun</span>
        </button>

        {{-- Left: Sidebar --}}
        <aside class="profile-sidebar-card" id="profile-sidebar">
            <button type="button" class="profile-sidebar-close" id="profile-sidebar-close" aria-label="Tutup Menu Akun">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
            <div class="sidebar-avatar-container">
                <div class="sidebar-avatar-box" id="btn-sidebar-avatar-click" title="Klik untuk ganti avatar">
                    <img id="sidebar-avatar-img" src="{{ $user['avatar_url'] ?? asset($user['avatar']) }}" alt="{{ $user['name'] }}">
                    <div class="sidebar-avatar-overlay">
                        <i data-lucide="camera" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                </div>
                <button type="button" class="sidebar-avatar-badge-btn" id="btn-open-avatar-picker-sidebar" title="Ganti Avatar">
                    <i data-lucide="camera" style="width:14px;height:14px;"></i>
                </button>
            </div>
            <h3 class="sidebar-user-name" id="sidebar-user-name">{{ $user['name'] }}</h3>
            <p class="sidebar-user-email" id="sidebar-user-email">{{ $user['email'] }}</p>

            <nav class="profile-nav-menu">
                <button type="button" class="profile-nav-item active" data-tab="ringkasan" aria-controls="tab-panel-ringkasan">
                    <i data-lucide="layout-grid"></i>
                    <span>Ringkasan</span>
                </button>
                <button type="button" class="profile-nav-item" data-tab="pesanan" aria-controls="tab-panel-pesanan">
                    <i data-lucide="shopping-bag"></i>
                    <span>Pesanan saya</span>
                </button>
                <button type="button" class="profile-nav-item" data-tab="alamat" aria-controls="tab-panel-alamat">
                    <i data-lucide="map-pin"></i>
                    <span>Alamat</span>
                </button>
                <button type="button" class="profile-nav-item" data-tab="wishlist" aria-controls="tab-panel-wishlist">
                    <i data-lucide="heart"></i>
                    <span>Wishlist</span>
                </button>
                <button type="button" class="profile-nav-item" data-tab="pengaturan" aria-controls="tab-panel-pengaturan">
                    <i data-lucide="settings"></i>
                    <span>Pengaturan</span>
                </button>
                <button type="button" class="profile-nav-logout-btn" id="btn-profile-logout">
                    <i data-lucide="log-out"></i>
                    <span>Keluar</span>
                </button>
            </nav>
        </aside>

        {{-- Right: Tab Panels --}}
        <main class="profile-content-area">
            
            {{-- 1. TAB RINGKASAN --}}
            <div class="profile-tab-panel active" id="tab-panel-ringkasan">
                {{-- Stat Cards --}}
                <div class="stats-cards-row">
                    <div class="stat-card">
                        <div class="stat-num">{{ $stats['total_orders'] }}</div>
                        <p class="stat-label">Total pesanan</p>
                    </div>
                    <div class="stat-card highlight">
                        <div class="stat-num">{{ $stats['in_delivery'] }}</div>
                        <p class="stat-label">Dalam pengiriman</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-num">{{ $stats['wishlist_count'] }}</div>
                        <p class="stat-label">Wishlist</p>
                    </div>
                </div>

                {{-- Data Profil Card --}}
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Data profil</h2>
                        <button class="btn-edit-pill" id="btn-open-edit-modal">Edit profil</button>
                    </div>
                    <div class="profile-info-cols" style="grid-template-columns: repeat(4, 1fr);">
                        <div class="info-col-item">
                            <span class="info-col-label">Nama lengkap</span>
                            <span class="info-col-val" id="disp-user-name">{{ $user['name'] }}</span>
                        </div>
                        <div class="info-col-item">
                            <span class="info-col-label">Email</span>
                            <span class="info-col-val" id="disp-user-email">{{ $user['email'] }}</span>
                        </div>
                        <div class="info-col-item">
                            <span class="info-col-label">Nomor telepon</span>
                            <span class="info-col-val" id="disp-user-phone">{{ $user['phone'] }}</span>
                        </div>
                        <div class="info-col-item">
                            <span class="info-col-label">Tanggal lahir</span>
                            <span class="info-col-val" id="disp-user-birthdate">{{ $user['birthdate'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Pesanan Terbaru Card --}}
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Pesanan terbaru</h2>
                        <button type="button" class="link-view-all-pink" id="link-goto-myorders">Lihat semua</button>
                    </div>
                    <div class="recent-orders-list">
                        @foreach($recentOrders as $ro)
                            <div class="recent-order-row">
                                <div class="order-row-left">
                                    <span class="order-row-id">{{ $ro['id'] }}</span>
                                    <span class="order-row-name">{{ $ro['title'] }}</span>
                                </div>
                                <div class="order-row-right">
                                    <span class="order-status-badge {{ $ro['status_type'] }}">{{ $ro['status'] }}</span>
                                    <span class="order-row-price">{{ $ro['price'] }}</span>
                                    <a href="/pesanan/{{ $ro['slug'] }}" class="btn-lacak-mini">Lacak</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. TAB PESANAN SAYA --}}
            <div class="profile-tab-panel" id="tab-panel-pesanan">
                @if(session('success'))
                    <div class="profile-order-notice success" role="status">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="profile-order-notice error" role="alert">{{ session('error') }}</div>
                @endif
                <div class="my-orders-list">
                    @foreach($myOrders as $idx => $order)
                        <div class="my-order-card {{ $order['status_type'] === 'cancelled' ? 'cancelled' : '' }}" id="my-order-{{ $idx + 1 }}">
                            <div class="my-order-meta-header">
                                <div class="order-meta-left">
                                    <span class="order-meta-date">{{ $order['date'] }}</span>
                                    <span class="order-meta-invoice">{{ $order['id'] }}</span>
                                </div>
                                <span class="order-status-badge {{ $order['status_type'] }}">{{ $order['status'] }}</span>
                            </div>

                            <div class="my-order-product-row">
                                <div class="order-product-left">
                                    <div class="order-product-img">
                                        <img src="{{ asset($order['image']) }}" alt="{{ $order['title'] }}">
                                    </div>
                                    <div>
                                        <h4 class="order-product-title">{{ $order['title'] }}</h4>
                                        <p class="order-product-variant">{{ $order['variant'] }} &bull; Jumlah: {{ $order['qty'] }}</p>
                                    </div>
                                </div>
                                <div class="order-product-price">{{ $order['price'] }}</div>
                            </div>

                            <div class="my-order-footer">
                                <span class="order-total-text">
                                    Total Belanja (incl. ongkir): <strong>{{ $order['total'] }}</strong>
                                </span>
                                <div style="display:flex; align-items:center; gap:0.75rem;">
                                    <a href="{{ route('pesanan.detail', $order['slug']) }}" class="btn-lacak-order">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                        Lacak Pesanan
                                    </a>
                                    @if($order['can_cancel'])
                                        <form method="POST" action="{{ route('pesanan.cancel', $order['slug']) }}" onsubmit="return confirm('Batalkan pesanan ini? Stok produk akan dikembalikan.');">
                                            @csrf
                                            <button type="submit" class="btn-cancel-order">Batalkan</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. TAB ALAMAT --}}
            <div class="profile-tab-panel" id="tab-panel-alamat">
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Daftar Alamat Pengiriman</h2>
                        <button class="btn-edit-pill" id="btn-add-new-address">+ Tambah Alamat</button>
                    </div>
                    <div id="address-list-container">
                        @foreach($addresses as $addr)
                            <div class="address-card-item">
                                <div class="address-card-header">
                                    <div class="address-label-badge">
                                        <span>{{ $addr['label'] }}</span>
                                        @if($addr['is_primary']) <span class="address-primary-tag">Utama</span> @endif
                                    </div>
                                    <div class="address-actions">
                                        <button type="button" class="address-action-btn btn-edit-address" data-id="addr-default-{{ $loop->index + 1 }}">
                                            <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                            <span>Ubah</span>
                                        </button>
                                    </div>
                                </div>
                                <p class="address-recipient">{{ $addr['name'] }} <span style="font-weight:400;color:#8a6a72;">({{ $addr['phone'] }})</span></p>
                                <p class="address-detail-text">{{ $addr['address'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. TAB WISHLIST --}}
                       <div class="profile-tab-panel" id="tab-panel-wishlist">
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Wishlist Saya ({{ count($wishlistItems) }})</h2>
                        <a href="/katalog" class="link-view-all-pink">Lihat Katalog</a>
                    </div>
                    <p style="font-size:0.9rem; color:#8a6a72; margin:0 0 1.5rem 0;">Simpan busana tidur favorit Anda untuk dibeli kapan saja.</p>
                    @if(count($wishlistItems) === 0)
                        <p style="font-size:0.9rem; color:#8a6a72;">Belum ada produk di wishlist kamu.</p>
                    @else
                        <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                            @foreach($wishlistItems as $item)
                                <div style="display:flex; gap:1rem; padding:1rem; border:1px solid var(--blush-pale); border-radius:14px; align-items:center;">
                                    <img src="{{ asset($item['image']) }}" style="width:64px; height:64px; border-radius:10px; object-fit:cover;" alt="{{ $item['title'] }}">
                                    <div style="flex:1;">
                                        <h4 style="margin:0 0 0.25rem; font-size:0.92rem; color:var(--ink);">{{ $item['title'] }}</h4>
                                        <span style="font-size:0.88rem; font-weight:700; color:var(--blush);">{{ $item['price'] }}</span>
                                    </div>
                                    <a href="/produk/{{ $item['slug'] }}" class="btn-edit-pill">Lihat</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- 5. TAB PENGATURAN --}}
            <div class="profile-tab-panel" id="tab-panel-pengaturan">
                <div class="profile-content-card">
                    <h2 class="card-heading-title" style="margin-bottom:1.5rem;">Pengaturan Akun</h2>
                    <div style="display:flex; flex-direction:column; gap:1.25rem;">
                        {{-- Pengaturan Avatar & Foto Profil --}}
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div style="display:flex; align-items:center; gap:1rem;">
                                <div style="width:52px; height:52px; border-radius:50%; overflow:hidden; border:2px solid var(--blush-pale); flex-shrink:0; background:#fdf2f5; cursor:pointer;" id="btn-setting-avatar-click" title="Ganti Avatar">
                                    <img id="settings-avatar-preview" src="{{ $user['avatar_url'] ?? asset($user['avatar']) }}" alt="Avatar" style="width:100%; height:100%; object-fit:cover; display:block;">
                                </div>
                                <div>
                                    <strong style="display:block; font-size:0.92rem; color:var(--ink);">Foto Profil & Avatar</strong>
                                    <span style="font-size:0.8rem; color:#8a6a72;">Pilih avatar karakter manis atau pilih foto dari galeri perangkat</span>
                                </div>
                            </div>
                            <button class="btn-edit-pill" id="btn-setting-change-avatar">
                                <i data-lucide="image" style="width:14px;height:14px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>
                                Ganti Avatar
                            </button>
                        </div>

                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:var(--ink);">Pengaturan Alamat Pengiriman</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Kelola daftar alamat utama, rumah, kantor, atau lokasi pengiriman lainnya</span>
                            </div>
                            <button class="btn-edit-pill" id="btn-goto-address-settings">Kelola Alamat</button>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:var(--ink);">Notifikasi Email & Promo</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Dapatkan kabar diskon eksklusif dan status pesanan</span>
                            </div>
                            <input type="checkbox" checked style="accent-color:var(--blush); width:18px; height:18px;">
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:var(--ink);">Keamanan Akun & Profil</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Perbarui informasi profil atau nama akun Anda</span>
                            </div>
                            <button class="btn-edit-pill" id="btn-setting-edit-profile">Ubah Profil</button>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#f43f5e;">Keluar dari Akun</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Akhiri sesi Anda pada perangkat ini</span>
                            </div>
                            <button class="btn-edit-pill" style="color:#f43f5e; border-color:#fca5a5;" id="btn-setting-logout">Logout</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- 1. MODAL UBAH DATA PROFIL --}}
<div class="edit-profile-modal-overlay" id="edit-profile-modal">
    <div class="edit-modal-card">
        <div class="modal-header-row">
            <h3 class="modal-header-title">Ubah Data Profil</h3>
            <button class="modal-close-btn" id="btn-close-edit-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        {{-- Avatar with Camera Icon --}}
        <div class="modal-avatar-wrapper" style="cursor:pointer;" id="btn-edit-modal-avatar-wrapper" title="Ganti Avatar">
            <div class="modal-avatar-img">
                <img id="modal-avatar-preview" src="{{ $user['avatar_url'] ?? asset($user['avatar']) }}" alt="Avatar">
            </div>
            <div class="avatar-camera-badge" id="btn-edit-modal-avatar-badge" title="Ganti Foto">
                <i data-lucide="camera" style="width:14px;height:14px;"></i>
            </div>
        </div>

        {{-- Form Fields --}}
        <form id="edit-profile-form" onsubmit="event.preventDefault();">
            <div class="modal-form-group">
                <label for="edit-input-nama">Nama Lengkap</label>
                <input type="text" id="edit-input-nama" class="modal-input" placeholder="Nama lengkap Anda" required>
            </div>

            <div class="modal-form-group">
                <label for="edit-input-email">Alamat Email</label>
                <input type="email" id="edit-input-email" class="modal-input" placeholder="email@domain.com" required>
            </div>



            <div class="modal-btn-row">
                <button type="button" class="btn-modal-cancel" id="btn-cancel-edit">Batal</button>
                <button type="submit" class="btn-modal-save" id="btn-save-profile">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- 2. MODAL UBAH & TAMBAH ALAMAT PENGIRIMAN --}}
<div class="edit-profile-modal-overlay" id="edit-address-modal">
    <div class="edit-modal-card" style="max-width: 520px;">
        <div class="modal-header-row">
            <h3 class="modal-header-title" id="address-modal-title">Ubah Alamat Pengiriman</h3>
            <button class="modal-close-btn" id="btn-close-address-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        <form id="edit-address-form" onsubmit="event.preventDefault();">
            <input type="hidden" id="address-edit-id" value="">

            <div class="modal-form-group">
                <label for="address-input-label">Label Alamat <span style="color:#8a6a72;font-weight:400;">(Contoh: Rumah, Kantor, Kos)</span></label>
                <input type="text" id="address-input-label" class="modal-input" placeholder="Misal: Alamat Utama atau Rumah" required>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="address-input-name">Nama Penerima</label>
                    <input type="text" id="address-input-name" class="modal-input" placeholder="Nama penerima paket" required>
                </div>
                <div class="modal-form-group">
                    <label for="address-input-phone">Nomor Telepon</label>
                    <input type="tel" id="address-input-phone" class="modal-input" placeholder="08xxxxxxxxxx" required>
                </div>
            </div>

            <div class="modal-form-group">
                <label for="address-input-address">Alamat Lengkap <span style="color:#f43f5e;">*</span></label>
                <textarea id="address-input-address" class="modal-input" rows="3" style="height:auto; min-height:80px; padding:0.75rem 1rem; resize:vertical; font-family:var(--font-body); line-height:1.45;" placeholder="Nama jalan, nomor rumah/gedung, RT/RW, kelurahan, kecamatan" required></textarea>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="address-select-province">Provinsi <span style="color:#f43f5e;">*</span></label>
                    <select id="address-select-province" class="modal-input" required>
                        <option value="">-- Memuat Provinsi... --</option>
                    </select>
                    <input type="hidden" id="address-input-province" value="">
                    <input type="hidden" id="address-input-province-id" value="">
                </div>
                <div class="modal-form-group">
                    <label for="address-select-city">Kota / Kabupaten <span style="color:#f43f5e;">*</span></label>
                    <select id="address-select-city" class="modal-input" required disabled>
                        <option value="">-- Pilih Provinsi Dahulu --</option>
                    </select>
                    <input type="hidden" id="address-input-city" value="">
                    <input type="hidden" id="address-input-city-id" value="">
                </div>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="address-select-subdistrict">Kecamatan</label>
                    <input id="address-select-subdistrict" class="modal-input" list="address-subdistrict-list" placeholder="Pilih atau ketik kecamatan" autocomplete="off" disabled>
                    <datalist id="address-subdistrict-list"></datalist>
                    <input type="hidden" id="address-input-subdistrict" value="">
                </div>
                <div class="modal-form-group">
                    <label for="address-input-postal">Kode Pos</label>
                    <input type="text" id="address-input-postal" class="modal-input" placeholder="Kode pos 5 digit" required>
                </div>
            </div>

            <div class="modal-form-group" style="margin-top: 0.5rem;">
                <label style="display:flex; align-items:center; gap:0.6rem; cursor:pointer; font-weight:500; font-size:0.85rem; color:var(--ink);">
                    <input type="checkbox" id="address-input-primary" style="accent-color:var(--blush); width:17px; height:17px;">
                    <span>Jadikan sebagai Alamat Utama pengiriman</span>
                </label>
            </div>

            <div class="modal-btn-row">
                <button type="button" class="btn-modal-cancel" id="btn-cancel-address">Batal</button>
                <button type="submit" class="btn-modal-save" id="btn-save-address">Simpan Alamat</button>
            </div>
        </form>
    </div>
</div>

{{-- 3. MODAL GANTI FOTO PROFIL / AVATAR --}}
<div class="avatar-picker-modal-overlay" id="avatar-picker-modal">
    <div class="avatar-picker-card">
        <div class="modal-header-row" style="margin-bottom: 1.25rem;">
            <div>
                <h3 class="modal-header-title">Pilih Foto Profil / Avatar</h3>
                <p style="font-size:0.8rem; color:#8a6a72; margin-top:3px; margin-bottom:0;">Pilih avatar karakter eksklusif atau unggah foto dari galeri perangkatmu</p>
            </div>
            <button class="modal-close-btn" id="btn-close-avatar-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        {{-- Spotlight Active Preview --}}
        <div class="avatar-preview-spotlight">
            <img id="avatar-spotlight-img" class="avatar-preview-spotlight-img" src="{{ $user['avatar_url'] ?? asset($user['avatar']) }}" alt="Preview">
            <span class="avatar-preview-spotlight-badge" id="avatar-spotlight-badge">Avatar Saat Ini</span>
            <input type="hidden" id="active-selected-avatar-val" value="{{ $user['avatar'] }}">
        </div>

        {{-- Tab Switchers (Galeri Avatar vs Unggah dari Galeri Perangkat) --}}
        <div class="avatar-picker-tabs">
            <button type="button" class="avatar-picker-tab-btn active" id="tab-btn-avatar-gallery">
                <i data-lucide="sparkles" style="width:15px;height:15px;"></i>
                <span>Galeri Avatar</span>
            </button>
            <button type="button" class="avatar-picker-tab-btn" id="tab-btn-device-gallery">
                <i data-lucide="image" style="width:15px;height:15px;"></i>
                <span>Pilih dari Galeri Foto</span>
            </button>
        </div>

        {{-- View 1: Preset Avatar Gallery --}}
        <div id="view-avatar-preset-gallery">
            <div class="avatar-grid-selection">
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-1.svg" data-name="Sweet Bunny" title="Sweet Bunny">
                    <img src="{{ asset('images/avatars/avatar-1.svg') }}" alt="Sweet Bunny">
                    <span>Bunny</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-2.svg" data-name="Dreamy Cat" title="Dreamy Cat">
                    <img src="{{ asset('images/avatars/avatar-2.svg') }}" alt="Dreamy Cat">
                    <span>Cat</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-3.svg" data-name="Cloud Princess" title="Cloud Princess">
                    <img src="{{ asset('images/avatars/avatar-3.svg') }}" alt="Cloud Princess">
                    <span>Cloud</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-4.svg" data-name="Teddy Slumber" title="Teddy Slumber">
                    <img src="{{ asset('images/avatars/avatar-4.svg') }}" alt="Teddy Slumber">
                    <span>Teddy</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-5.svg" data-name="Velvet Swan" title="Velvet Swan">
                    <img src="{{ asset('images/avatars/avatar-5.svg') }}" alt="Velvet Swan">
                    <span>Swan</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-6.svg" data-name="Moon Dreamer" title="Moon Dreamer">
                    <img src="{{ asset('images/avatars/avatar-6.svg') }}" alt="Moon Dreamer">
                    <span>Moon</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-7.svg" data-name="Pastel Girl" title="Pastel Girl">
                    <img src="{{ asset('images/avatars/avatar-7.svg') }}" alt="Pastel Girl">
                    <span>Girl</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/avatars/avatar-8.svg" data-name="Silk Panda" title="Silk Panda">
                    <img src="{{ asset('images/avatars/avatar-8.svg') }}" alt="Silk Panda">
                    <span>Panda</span>
                </button>
                <button type="button" class="avatar-grid-item" data-path="images/alya-avatar.jpg" data-name="Alya Classic" title="Alya Classic">
                    <img src="{{ asset('images/alya-avatar.jpg') }}" alt="Alya Classic">
                    <span>Alya</span>
                </button>
            </div>
        </div>

        {{-- View 2: Upload / Pilih dari Galeri Perangkat --}}
        <div id="view-avatar-device-gallery" style="display: none;">
            <div class="device-upload-zone" id="btn-trigger-file-input">
                <i data-lucide="upload-cloud" style="width:36px;height:36px;display:block;margin:0 auto 0.6rem;"></i>
                <h4>Pilih Gambar dari Galeri Perangkat</h4>
                <p>Klik untuk memilih foto dari galeri foto di HP atau Komputer Anda (JPG, PNG, WEBP)</p>
                <input type="file" id="input-device-photo" accept="image/*" style="display: none;">
            </div>
            <div id="device-upload-status" style="display:none; text-align:center; margin-bottom:1rem; font-size:0.84rem; color:#10b981; font-weight:600;">
                <i data-lucide="check" style="width:16px;height:16px;display:inline-block;vertical-align:middle;margin-right:4px;"></i>
                Foto dari galeri siap disimpan!
            </div>
        </div>

        <div class="modal-btn-row">
            <button type="button" class="btn-modal-cancel" id="btn-cancel-avatar-modal">Batal</button>
            <button type="button" class="btn-modal-save" id="btn-save-avatar-modal">Simpan Foto Profil</button>
        </div>
    </div>
</div>

{{-- 4. MODAL KONFIRMASI LOGOUT --}}
<div class="edit-profile-modal-overlay" id="modal-logout-confirm" style="z-index: 1200;">
    <div class="edit-modal-card" style="max-width: 400px; text-align: center; padding: 2.25rem 2rem;">
        <div style="width: 58px; height: 58px; border-radius: 50%; background: #fff1f2; color: #f43f5e; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; border: 2px solid #fed7e2;">
            <i data-lucide="log-out" style="width: 28px; height: 28px;"></i>
        </div>
        <h3 style="font-family: var(--font-heading); font-size: 1.4rem; font-weight: 700; color: var(--ink); margin: 0 0 0.5rem 0;">Keluar dari Akun?</h3>
        <p style="font-size: 0.88rem; color: var(--ink-muted); line-height: 1.5; margin: 0 0 1.75rem 0;">Apakah Anda yakin ingin keluar dan mengakhiri sesi akun Sweet Dreams pada perangkat ini?</p>
        
        <div style="display: flex; gap: 0.85rem; justify-content: center;">
            <button type="button" class="btn-modal-cancel" id="btn-cancel-logout-modal" style="flex: 1;">Batal</button>
            <button type="button" class="btn-modal-save" id="btn-confirm-do-logout" style="flex: 1.2; background: #f43f5e; box-shadow: 0 3px 12px rgba(244, 63, 94, 0.3);">Ya, Keluar</button>
        </div>
    </div>
</div>

{{-- TOAST NOTIFICATION --}}
<div class="profile-toast" id="profile-toast">
    <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
    <span id="profile-toast-msg">Perubahan berhasil disimpan!</span>
</div>

<script>
window.initialAddresses = @json($addresses);
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide Icons
    lucide.createIcons();

    // Restore active tab from sessionStorage (e.g. when returning from tracking page)
    const savedTab = sessionStorage.getItem('sweetdreams_active_tab');
    if (savedTab) {
        sessionStorage.removeItem('sweetdreams_active_tab');
        // Will call switchTab after it's defined below
        window._pendingTab = savedTab;
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function showProfileToast(message) {
        const toast = document.getElementById('profile-toast');
        const toastMsg = document.getElementById('profile-toast-msg');
        if (!toast) return;
        if (toastMsg) toastMsg.textContent = message;
        toast.classList.add('show');
        if (window.profileToastTimeout) clearTimeout(window.profileToastTimeout);
        window.profileToastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 3200);
    }

    // 1. Sidebar Tab Switching
    const navItems = document.querySelectorAll('.profile-nav-item');
    const tabPanels = document.querySelectorAll('.profile-tab-panel');

    function switchTab(targetTab) {
        if (!Array.from(navItems).some(item => item.dataset.tab === targetTab)) return;

        navItems.forEach(item => {
            if (item.getAttribute('data-tab') === targetTab) {
                item.classList.add('active');
                item.setAttribute('aria-current', 'page');
            } else {
                item.classList.remove('active');
                item.removeAttribute('aria-current');
            }
        });

        tabPanels.forEach(panel => {
            if (panel.id === 'tab-panel-' + targetTab) {
                panel.classList.add('active');
            } else {
                panel.classList.remove('active');
            }
        });
    }

    const requestedTab = new URLSearchParams(window.location.search).get('tab');
    if (requestedTab) switchTab(requestedTab);

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');
            if (target) switchTab(target);
            // Tutup drawer di HP setelah pilih menu
            document.getElementById('profile-sidebar')?.classList.remove('open');
            document.getElementById('profile-sidebar-toggle')?.setAttribute('aria-expanded', 'false');
        });
    });

    // Drawer menu akun (HP)
    const profileSidebar = document.getElementById('profile-sidebar');
    const profileSidebarToggle = document.getElementById('profile-sidebar-toggle');
    const profileSidebarClose = document.getElementById('profile-sidebar-close');
    if (profileSidebarToggle && profileSidebar) {
        profileSidebarToggle.addEventListener('click', () => {
            const open = profileSidebar.classList.toggle('open');
            profileSidebarToggle.setAttribute('aria-expanded', String(open));
        });
    }
    if (profileSidebarClose && profileSidebar) {
        profileSidebarClose.addEventListener('click', () => {
            profileSidebar.classList.remove('open');
            profileSidebarToggle?.setAttribute('aria-expanded', 'false');
        });
    }

    // Link 'Lihat semua' in recent orders -> switches to 'pesanan'
    const linkGotoOrders = document.getElementById('link-goto-myorders');
    if (linkGotoOrders) {
        linkGotoOrders.addEventListener('click', function() {
            switchTab('pesanan');
        });
    }

    // Shortcut from Tab Pengaturan -> switches to 'alamat'
    const btnGotoAddrSettings = document.getElementById('btn-goto-address-settings');
    if (btnGotoAddrSettings) {
        btnGotoAddrSettings.addEventListener('click', function() {
            switchTab('alamat');
        });
    }

    // 2. Edit Profile Modal Handling
    const profileModal = document.getElementById('edit-profile-modal');
    const btnOpenProfileModal = document.getElementById('btn-open-edit-modal');
    const btnSettingProfileModal = document.getElementById('btn-setting-edit-profile');
    const btnCloseProfileModal = document.getElementById('btn-close-edit-modal');
    const btnCancelProfile = document.getElementById('btn-cancel-edit');
    const formProfile = document.getElementById('edit-profile-form');

    function openProfileModal() {
        const user = window.SweetDreamsAuth ? window.SweetDreamsAuth.getCurrentUser() : null;
        if (user) {
            document.getElementById('edit-input-nama').value = user.name || '';
            document.getElementById('edit-input-email').value = user.email || '';
            document.getElementById('edit-input-phone').value = user.phone || '';
            document.getElementById('edit-input-birthdate').value = user.birthdate || '';
            document.getElementById('edit-input-city').value = user.city || '';
        } else {
            document.getElementById('edit-input-nama').value = @json($user['name']);
            document.getElementById('edit-input-email').value = @json($user['email']);
            document.getElementById('edit-input-phone').value = @json($user['phone']);
            document.getElementById('edit-input-birthdate').value = @json($user['birthdate_raw']);
            document.getElementById('edit-input-city').value = @json($user['city']);
        }
        profileModal.classList.add('open');
    }

    function closeProfileModal() {
        profileModal.classList.remove('open');
    }

    if (btnOpenProfileModal) btnOpenProfileModal.addEventListener('click', openProfileModal);
    if (btnSettingProfileModal) btnSettingProfileModal.addEventListener('click', openProfileModal);
    if (btnCloseProfileModal) btnCloseProfileModal.addEventListener('click', closeProfileModal);
    if (btnCancelProfile) btnCancelProfile.addEventListener('click', closeProfileModal);

    if (formProfile) {
        formProfile.addEventListener('submit', function(event) {
            event.preventDefault();
            const newName = document.getElementById('edit-input-nama').value.trim();
            const newEmail = document.getElementById('edit-input-email').value.trim();

            if (!newName) {
                alert('Nama lengkap tidak boleh kosong.');
                return;
            }

            fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: newName, email: newEmail
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200) {
                    showProfileToast('Perubahan data profil berhasil disimpan!');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    const msg = body.message || (body.errors ? Object.values(body.errors)[0][0] : 'Gagal menyimpan perubahan.');
                    alert(msg);
                }
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }

    // 3. Edit & Add Address Modal Handling
    const addressModal = document.getElementById('edit-address-modal');
    const btnCloseAddressModal = document.getElementById('btn-close-address-modal');
    const btnCancelAddress = document.getElementById('btn-cancel-address');
    const btnAddNewAddress = document.getElementById('btn-add-new-address');
    const formAddress = document.getElementById('edit-address-form');

    function closeAddressModal() {
        addressModal.classList.remove('open');
    }

    if (btnCloseAddressModal) btnCloseAddressModal.addEventListener('click', closeAddressModal);
    if (btnCancelAddress) btnCancelAddress.addEventListener('click', closeAddressModal);

    // RajaOngkir handlers for address modal
    const addrSelectProv = document.getElementById('address-select-province');
    const addrSelectCity = document.getElementById('address-select-city');
    const addrInputProv = document.getElementById('address-input-province');
    const addrInputProvId = document.getElementById('address-input-province-id');
    const addrInputCity = document.getElementById('address-input-city');
    const addrInputCityId = document.getElementById('address-input-city-id');
    const addrSelectSub = document.getElementById('address-select-subdistrict');
    const addrSubList = document.getElementById('address-subdistrict-list');
    const addrInputSub = document.getElementById('address-input-subdistrict');
    const addrInputPostal = document.getElementById('address-input-postal');

    let profileProvincesCache = null;

    function loadProfileAddressProvinces(targetProvIdOrName = null, targetCityIdOrName = null) {
        if (!addrSelectProv) return;

        const populateProvs = (provs) => {
            let options = '<option value="">-- Pilih Provinsi --</option>';
            let matchedProv = null;

            provs.forEach(p => {
                const isSelected = targetProvIdOrName && (
                    String(p.id) === String(targetProvIdOrName) ||
                    p.name.toLowerCase().includes(String(targetProvIdOrName).toLowerCase())
                );
                if (isSelected && !matchedProv) matchedProv = p;
                options += `<option value="${p.id}" ${isSelected ? 'selected' : ''}>${escapeHtml(p.name)}</option>`;
            });

            addrSelectProv.innerHTML = options;

            if (matchedProv) {
                addrSelectProv.value = matchedProv.id;
                addrInputProv.value = matchedProv.name;
                addrInputProvId.value = matchedProv.id;
                loadProfileAddressCities(matchedProv.id, targetCityIdOrName);
            } else {
                addrSelectCity.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
                addrSelectCity.disabled = true;
            }
        };

        const FALLBACK_PROVINCES = [
            { id: 15, name: 'BALI' }, { id: 24, name: 'BANGKA BELITUNG' }, { id: 11, name: 'BANTEN' },
            { id: 6, name: 'BENGKULU' }, { id: 19, name: 'DI YOGYAKARTA' }, { id: 10, name: 'DKI JAKARTA' },
            { id: 17, name: 'GORONTALO' }, { id: 13, name: 'JAMBI' }, { id: 5, name: 'JAWA BARAT' },
            { id: 12, name: 'JAWA TENGAH' }, { id: 18, name: 'JAWA TIMUR' }, { id: 28, name: 'KALIMANTAN BARAT' },
            { id: 3, name: 'KALIMANTAN SELATAN' }, { id: 4, name: 'KALIMANTAN TENGAH' }, { id: 7, name: 'KALIMANTAN TIMUR' },
            { id: 31, name: 'KALIMANTAN UTARA' }, { id: 8, name: 'KEPULAUAN RIAU' }, { id: 30, name: 'LAMPUNG' },
            { id: 2, name: 'MALUKU' }, { id: 32, name: 'MALUKU UTARA' }, { id: 9, name: 'NANGGROE ACEH DARUSSALAM (NAD)' },
            { id: 1, name: 'NUSA TENGGARA BARAT (NTB)' }, { id: 21, name: 'NUSA TENGGARA TIMUR (NTT)' }, { id: 14, name: 'PAPUA' },
            { id: 29, name: 'PAPUA BARAT' }, { id: 25, name: 'RIAU' }, { id: 34, name: 'SULAWESI BARAT' },
            { id: 33, name: 'SULAWESI SELATAN' }, { id: 27, name: 'SULAWESI TENGAH' }, { id: 20, name: 'SULAWESI TENGGARA' },
            { id: 22, name: 'SULAWESI UTARA' }, { id: 23, name: 'SUMATERA BARAT' }, { id: 26, name: 'SUMATERA SELATAN' },
            { id: 16, name: 'SUMATERA UTARA' }
        ];

        if (profileProvincesCache && profileProvincesCache.length > 0) {
            populateProvs(profileProvincesCache);
        } else {
            populateProvs(FALLBACK_PROVINCES);
            fetch('/api/shipping/provinces')
                .then(res => res.json())
                .then(data => {
                    if (data && data.length > 0) {
                        profileProvincesCache = data;
                        populateProvs(profileProvincesCache);
                    }
                })
                .catch(() => {});
        }
    }

    function loadProfileAddressCities(provId, targetCityIdOrName = null) {
        if (!addrSelectCity) return;
        addrSelectCity.innerHTML = '<option value="">-- Memuat Kota / Kabupaten... --</option>';
        addrSelectCity.disabled = true;

        if (!provId) {
            addrSelectCity.innerHTML = '<option value="">-- Pilih Provinsi Dahulu --</option>';
            return;
        }

        fetch(`/api/shipping/cities?province_id=${provId}`)
            .then(res => res.json())
            .then(cities => {
                let options = '<option value="">-- Pilih Kota / Kabupaten --</option>';
                let matchedCity = null;

                (cities || []).forEach(c => {
                    const isSelected = targetCityIdOrName && (
                        String(c.id) === String(targetCityIdOrName) ||
                        c.name.toLowerCase().includes(String(targetCityIdOrName).toLowerCase())
                    );
                    if (isSelected && !matchedCity) matchedCity = c;
                    options += `<option value="${c.id}" ${isSelected ? 'selected' : ''}>${escapeHtml(c.name)}</option>`;
                });

                addrSelectCity.innerHTML = options;
                addrSelectCity.disabled = false;

                if (matchedCity) {
                    addrSelectCity.value = matchedCity.id;
                    addrInputCity.value = matchedCity.name;
                    addrInputCityId.value = matchedCity.id;
                }
            })
            .catch(() => {
                addrSelectCity.innerHTML = '<option value="">-- Gagal Memuat Kota (Klik untuk coba lagi) --</option>';
                addrSelectCity.disabled = false;
            });
    }

    if (addrSelectProv) {
        addrSelectProv.addEventListener('change', function() {
            const provId = this.value;
            const provName = this.options[this.selectedIndex]?.text || '';
            addrInputProv.value = provId ? provName : '';
            addrInputProvId.value = provId || '';

            addrInputCity.value = '';
            addrInputCityId.value = '';
            if (addrSelectSub) {
                addrSelectSub.value = '';
                addrSelectSub.placeholder = '-- Pilih Kota Dahulu --';
                addrSelectSub.disabled = true;
            }
            if (addrSubList) addrSubList.innerHTML = '';
            if (addrInputSub) addrInputSub.value = '';
            loadProfileAddressCities(provId);
        });
    }

    if (addrSelectCity) {
        addrSelectCity.addEventListener('change', function() {
            const cityId = this.value;
            const cityName = this.options[this.selectedIndex]?.text || '';
            addrInputCity.value = cityId ? cityName : '';
            addrInputCityId.value = cityId || '';
            // Kecamatan ikut kota (sama seperti form checkout)
            loadProfileSubdistricts(cityId, null, cityName);
        });
    }

    function syncProfileSubdistrict() {
        if (!addrSelectSub) return;
        const typed = addrSelectSub.value.trim();
        let matchedId = '';
        let matchedPostal = '';
        if (addrSubList) {
            const opt = [...addrSubList.options].find(o => o.value.toLowerCase() === typed.toLowerCase());
            if (opt) {
                matchedId = opt.dataset.id || '';
                matchedPostal = opt.dataset.postal || '';
            }
        }
        if (addrInputSub) addrInputSub.value = typed;
        if (addrInputPostal && !addrInputPostal.value && matchedPostal) {
            addrInputPostal.value = matchedPostal;
        }
        return matchedId;
    }

    function loadProfileSubdistricts(cityId, preselectNameOrId = null, cityName = '') {
        if (!addrSelectSub) return;
        addrSelectSub.value = '';
        if (addrInputSub) addrInputSub.value = '';
        if (addrSubList) addrSubList.innerHTML = '';
        if (!cityId) {
            addrSelectSub.placeholder = '-- Pilih Kota Dahulu --';
            addrSelectSub.disabled = true;
            return;
        }
        addrSelectSub.placeholder = '-- Memuat Kecamatan... --';
        addrSelectSub.disabled = true;
        fetch(`/api/shipping/subdistricts?city_id=${cityId}&city=${encodeURIComponent(cityName || '')}`)
            .then(res => res.json())
            .then(list => {
                if (addrSubList) {
                    addrSubList.innerHTML = (list || []).map(s =>
                        `<option value="${s.name}" data-id="${s.id}" data-postal="${s.postal_code || ''}"></option>`
                    ).join('');
                }
                addrSelectSub.placeholder = 'Pilih atau ketik kecamatan';
                addrSelectSub.disabled = false;
                if (preselectNameOrId) {
                    const want = String(preselectNameOrId).toLowerCase();
                    const opt = addrSubList ? [...addrSubList.options].find(o =>
                        o.value.toLowerCase() === want || String(o.dataset.id) === String(preselectNameOrId)
                    ) : null;
                    addrSelectSub.value = opt ? opt.value : preselectNameOrId;
                    syncProfileSubdistrict();
                }
            })
            .catch(() => {
                addrSelectSub.placeholder = 'Ketik kecamatan manual';
                addrSelectSub.disabled = false;
            });
    }

    if (addrSelectSub) {
        addrSelectSub.addEventListener('input', syncProfileSubdistrict);
        addrSelectSub.addEventListener('change', syncProfileSubdistrict);
    }

    function openAddAddressModal() {
        document.getElementById('address-modal-title').textContent = 'Tambah Alamat Pengiriman';
        document.getElementById('address-edit-id').value = '';
        document.getElementById('address-input-label').value = 'Rumah';
        document.getElementById('address-input-name').value = @json($user['name']);
        document.getElementById('address-input-phone').value = @json($user['phone'] === 'Belum diisi' ? '' : $user['phone']);
        document.getElementById('address-input-address').value = '';
        document.getElementById('address-input-postal').value = '';
        document.getElementById('address-input-primary').checked = false;

        addrInputProv.value = '';
        addrInputProvId.value = '';
        addrInputCity.value = '';
        addrInputCityId.value = '';
        if (addrSelectSub) {
            addrSelectSub.value = '';
            addrSelectSub.placeholder = '-- Pilih Kota Dahulu --';
            addrSelectSub.disabled = true;
        }
        if (addrSubList) addrSubList.innerHTML = '';
        if (addrInputSub) addrInputSub.value = '';

        loadProfileAddressProvinces();

        addressModal.classList.add('open');
        document.getElementById('address-input-address').focus();
    }

    if (btnAddNewAddress) btnAddNewAddress.addEventListener('click', openAddAddressModal);

    function openEditAddressModal(addrId) {
        const addresses = window.initialAddresses || [];
        const addr = addresses.find(a => String(a.id) === String(addrId));
        if (!addr) {
            openAddAddressModal();
            return;
        }

        document.getElementById('address-modal-title').textContent = 'Ubah Alamat Pengiriman';
        document.getElementById('address-edit-id').value = addr.id;
        document.getElementById('address-input-label').value = addr.label || 'Alamat';
        document.getElementById('address-input-name').value = addr.name || '';
        document.getElementById('address-input-phone').value = addr.phone || '';
        document.getElementById('address-input-address').value = addr.address || '';
        document.getElementById('address-input-postal').value = addr.postal_code || '';
        document.getElementById('address-input-primary').checked = !!addr.is_primary;

        addrInputProv.value = addr.province || '';
        addrInputProvId.value = addr.province_id || '';
        addrInputCity.value = addr.city || '';
        addrInputCityId.value = addr.city_id || '';
        if (addrInputSub) addrInputSub.value = addr.subdistrict || '';

        loadProfileAddressProvinces(addr.province_id || addr.province, addr.city_id || addr.city);
        loadProfileSubdistricts(addr.city_id || null, addr.subdistrict || null, addr.city || '');

        addressModal.classList.add('open');
        document.getElementById('address-input-address').focus();
    }

    if (formAddress) {
        formAddress.addEventListener('submit', function() {
            const addrId = document.getElementById('address-edit-id').value.trim();
            const label = document.getElementById('address-input-label').value.trim();
            const name = document.getElementById('address-input-name').value.trim();
            const phone = document.getElementById('address-input-phone').value.trim();
            const address = document.getElementById('address-input-address').value.trim();
            const city = addrInputCity.value.trim() || document.getElementById('address-select-city').options[document.getElementById('address-select-city').selectedIndex]?.text || '';
            const city_id = addrInputCityId.value.trim() || document.getElementById('address-select-city').value;
            const subSelect = document.getElementById('address-select-subdistrict');
            const subdistrict = subSelect ? subSelect.value.trim() : '';
            const province = addrInputProv.value.trim() || document.getElementById('address-select-province').options[document.getElementById('address-select-province').selectedIndex]?.text || '';
            const province_id = addrInputProvId.value.trim() || document.getElementById('address-select-province').value;
            const postal_code = document.getElementById('address-input-postal').value.trim();
            const is_primary = document.getElementById('address-input-primary').checked;

            if (!name) {
                alert('Nama penerima wajib diisi.');
                return;
            }
            if (!address) {
                alert('Kolom alamat lengkap wajib diisi.');
                return;
            }
            if (!province_id || !city_id) {
                alert('Harap pilih provinsi dan kota tujuan pengiriman.');
                return;
            }

            const addrData = {
                label: label || 'Alamat',
                recipient_name: name,
                phone: phone,
                address: address,
                city: city,
                city_id: city_id,
                subdistrict: subdistrict,
                province: province,
                province_id: province_id,
                postal_code: postal_code,
                is_primary: is_primary
            };

            const url = addrId ? `/api/addresses/${addrId}` : '/api/addresses';
            const method = addrId ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(addrData)
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                if (status === 200 || status === 201) {
                    closeAddressModal();
                    showProfileToast(addrId ? 'Alamat pengiriman berhasil diubah!' : 'Alamat baru berhasil ditambahkan!');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    const msg = body.message || (body.errors ? Object.values(body.errors)[0][0] : 'Gagal menyimpan alamat.');
                    alert(msg);
                }
            })
            .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
        });
    }

    // 4. Render Address List Dynamically
    function renderAddressList() {
        const container = document.getElementById('address-list-container');
        if (!container) return;

               const addresses = window.initialAddresses || [];

        if (addresses.length === 0) {
            container.innerHTML = `
                <div style="text-align:center; padding: 2.5rem 1rem; color: var(--ink-muted);">
                    <p style="margin-bottom:1rem; font-size:0.92rem;">Belum ada alamat pengiriman yang tersimpan.</p>
                    <button type="button" class="btn-edit-pill" id="btn-add-address-empty">+ Tambah Alamat Pengiriman</button>
                </div>
            `;
            document.getElementById('btn-add-address-empty')?.addEventListener('click', openAddAddressModal);
            return;
        }

        let html = '';
        addresses.forEach(addr => {
            const locDetails = [addr.subdistrict ? 'Kec. ' + addr.subdistrict : '', addr.city, addr.province, addr.postal_code].filter(Boolean).join(', ');
            html += `
                <div class="address-card-item" data-id="${addr.id}">
                    <div class="address-card-header">
                        <div class="address-label-badge">
                            <span>${escapeHtml(addr.label || 'Alamat')}</span>
                            ${addr.is_primary ? '<span class="address-primary-tag">Utama</span>' : ''}
                        </div>
                        <div class="address-actions">
                            ${!addr.is_primary ? `<button type="button" class="address-action-btn btn-set-primary" data-id="${addr.id}">Jadikan Utama</button>` : ''}
                            <button type="button" class="address-action-btn btn-edit-addr-item" data-id="${addr.id}">
                                <i data-lucide="edit-3" style="width:14px;height:14px;"></i>
                                <span>Ubah</span>
                            </button>
                            ${addresses.length > 1 ? `
                            <button type="button" class="address-action-btn delete btn-delete-addr-item" data-id="${addr.id}" title="Hapus Alamat">
                                <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                <span>Hapus</span>
                            </button>` : ''}
                        </div>
                    </div>
                    <p class="address-recipient">${escapeHtml(addr.name)} <span style="font-weight:400;color:#8a6a72;">(${escapeHtml(addr.phone || '-')})</span></p>
                    <p class="address-detail-text">
                        ${escapeHtml(addr.address || 'Alamat belum diatur')}${locDetails ? '<br><small style="color:#8a6a72;">' + escapeHtml(locDetails) + '</small>' : ''}
                    </p>
                </div>
            `;
        });

        container.innerHTML = html;
        lucide.createIcons();

        // Bind address buttons
        container.querySelectorAll('.btn-edit-addr-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                openEditAddressModal(id);
            });
        });

             container.querySelectorAll('.btn-set-primary').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                fetch(`/api/addresses/${id}/primary`, {
                    method: 'PATCH',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        showProfileToast('Alamat utama berhasil diubah!');
                        setTimeout(() => window.location.reload(), 600);
                    } else {
                        alert(body.message || 'Gagal mengubah alamat utama.');
                    }
                })
                .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
            });
        });

                container.querySelectorAll('.btn-delete-addr-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!confirm('Apakah Anda yakin ingin menghapus alamat pengiriman ini?')) return;

                fetch(`/api/addresses/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json().then(data => ({ status: res.status, body: data })))
                .then(({ status, body }) => {
                    if (status === 200) {
                        showProfileToast('Alamat berhasil dihapus.');
                        setTimeout(() => window.location.reload(), 600);
                    } else {
                        alert(body.message || 'Gagal menghapus alamat.');
                    }
                })
                .catch(() => alert('Tidak bisa menghubungi server, coba lagi.'));
            });
        });
    }

    // 5. Sync Active User from SweetDreamsAuth
    function syncActiveUserData() {
        if (!window.SweetDreamsAuth) return;
        const activeUser = window.SweetDreamsAuth.getCurrentUser();
        // if (activeUser) {
        //     const firstName = (activeUser.name || 'Pengguna').split(' ')[0];
        //     const greetingEl = document.getElementById('page-user-greeting');
        //     const sideNameEl = document.getElementById('sidebar-user-name');
        //     const sideEmailEl = document.getElementById('sidebar-user-email');
        //     const dispNameEl = document.getElementById('disp-user-name');
        //     const dispEmailEl = document.getElementById('disp-user-email');
        //     const dispPhoneEl = document.getElementById('disp-user-phone');
        //     const dispBirthEl = document.getElementById('disp-user-birthdate');

        //     if (greetingEl) greetingEl.textContent = 'Selamat datang, ' + firstName;
        //     if (sideNameEl) sideNameEl.textContent = activeUser.name;
        //     if (sideEmailEl) sideEmailEl.textContent = activeUser.email;
        //     if (dispNameEl) dispNameEl.textContent = activeUser.name;
        //     if (dispEmailEl) dispEmailEl.textContent = activeUser.email;
        //     if (dispPhoneEl) dispPhoneEl.textContent = activeUser.phone || '-';
        //     if (dispBirthEl) dispBirthEl.textContent = activeUser.birthdate || '-';

        //     // Sync Avatars Across All Profile Containers
        //     if (activeUser.avatar) {
        //         const avatarSrc = activeUser.avatar.startsWith('data:') || activeUser.avatar.startsWith('http') || activeUser.avatar.startsWith('/') 
        //             ? activeUser.avatar 
        //             : '/' + activeUser.avatar;

        //         const sideImg = document.getElementById('sidebar-avatar-img');
        //         const modalImg = document.getElementById('modal-avatar-preview');
        //         const setImg = document.getElementById('settings-avatar-preview');
        //         const spotImg = document.getElementById('avatar-spotlight-img');

        //         if (sideImg) sideImg.src = avatarSrc;
        //         if (modalImg) modalImg.src = avatarSrc;
        //         if (setImg) setImg.src = avatarSrc;
        //         if (spotImg) spotImg.src = avatarSrc;
        //     }

        //     // Admin badge
        //     if (activeUser.role === 'admin') {
        //         const badge = document.createElement('span');
        //         badge.textContent = 'ADMIN';
        //         badge.style.cssText = 'background:var(--blush);color:#fff;font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:10px;margin-left:6px;vertical-align:middle;';
        //         if (sideNameEl) sideNameEl.appendChild(badge);
        //     }
        // }

        renderAddressList();
    }

    // ===== AVATAR PICKER MODAL LOGIC =====
    const avatarModal = document.getElementById('avatar-picker-modal');
    const btnCloseAvatarModal = document.getElementById('btn-close-avatar-modal');
    const btnCancelAvatarModal = document.getElementById('btn-cancel-avatar-modal');
    const btnSaveAvatarModal = document.getElementById('btn-save-avatar-modal');

    const btnOpenSidebarAvatar = document.getElementById('btn-sidebar-avatar-click');
    const btnOpenSidebarBadge = document.getElementById('btn-open-avatar-picker-sidebar');
    const btnSettingAvatarClick = document.getElementById('btn-setting-avatar-click');
    const btnSettingChangeAvatar = document.getElementById('btn-setting-change-avatar');
    const btnModalAvatarClick = document.getElementById('btn-edit-modal-avatar-wrapper');
    const btnModalAvatarBadge = document.getElementById('btn-edit-modal-avatar-badge');

    const spotlightImg = document.getElementById('avatar-spotlight-img');
    const spotlightBadge = document.getElementById('avatar-spotlight-badge');
    const activeAvatarInput = document.getElementById('active-selected-avatar-val');

    const tabBtnPreset = document.getElementById('tab-btn-avatar-gallery');
    const tabBtnDevice = document.getElementById('tab-btn-device-gallery');
    const viewPreset = document.getElementById('view-avatar-preset-gallery');
    const viewDevice = document.getElementById('view-avatar-device-gallery');

    const btnTriggerUpload = document.getElementById('btn-trigger-file-input');
    const inputDevicePhoto = document.getElementById('input-device-photo');
    const uploadStatus = document.getElementById('device-upload-status');

    let currentSelectedAvatar = '';

    function openAvatarModal() {
        const currentAvatar = document.getElementById('active-selected-avatar-val')?.value || @json($user['avatar']);
        currentSelectedAvatar = currentAvatar;

        const avatarSrc = currentAvatar.startsWith('data:') || currentAvatar.startsWith('http') || currentAvatar.startsWith('/') 
            ? currentAvatar 
            : '/' + currentAvatar;

        if (spotlightImg) spotlightImg.src = avatarSrc;
        if (activeAvatarInput) activeAvatarInput.value = currentAvatar;
        if (spotlightBadge) spotlightBadge.textContent = 'Avatar Saat Ini';

        // Select matching item in preset grid if matches
        let foundMatch = false;
        document.querySelectorAll('.avatar-grid-item').forEach(item => {
            const path = item.getAttribute('data-path');
            if (path === currentAvatar) {
                item.classList.add('selected');
                if (spotlightBadge) spotlightBadge.textContent = item.getAttribute('data-name');
                foundMatch = true;
            } else {
                item.classList.remove('selected');
            }
        });

        if (!foundMatch && currentAvatar.startsWith('data:')) {
            if (spotlightBadge) spotlightBadge.textContent = 'Foto Galeri Perangkat';
        }

        switchAvatarTab('preset');
        avatarModal.classList.add('open');
    }

    function closeAvatarModal() {
        avatarModal.classList.remove('open');
    }

    function switchAvatarTab(tab) {
        if (tab === 'preset') {
            if (tabBtnPreset) tabBtnPreset.classList.add('active');
            if (tabBtnDevice) tabBtnDevice.classList.remove('active');
            if (viewPreset) viewPreset.style.display = 'block';
            if (viewDevice) viewDevice.style.display = 'none';
        } else {
            if (tabBtnDevice) tabBtnDevice.classList.add('active');
            if (tabBtnPreset) tabBtnPreset.classList.remove('active');
            if (viewPreset) viewPreset.style.display = 'none';
            if (viewDevice) viewDevice.style.display = 'block';
        }
    }

    if (tabBtnPreset) tabBtnPreset.addEventListener('click', () => switchAvatarTab('preset'));
    if (tabBtnDevice) tabBtnDevice.addEventListener('click', () => switchAvatarTab('device'));

    // Open triggers
    if (btnOpenSidebarAvatar) btnOpenSidebarAvatar.addEventListener('click', openAvatarModal);
    if (btnOpenSidebarBadge) btnOpenSidebarBadge.addEventListener('click', openAvatarModal);
    if (btnSettingAvatarClick) btnSettingAvatarClick.addEventListener('click', openAvatarModal);
    if (btnSettingChangeAvatar) btnSettingChangeAvatar.addEventListener('click', openAvatarModal);
    if (btnModalAvatarClick) btnModalAvatarClick.addEventListener('click', openAvatarModal);
    if (btnModalAvatarBadge) btnModalAvatarBadge.addEventListener('click', openAvatarModal);

    // Close triggers
    if (btnCloseAvatarModal) btnCloseAvatarModal.addEventListener('click', closeAvatarModal);
    if (btnCancelAvatarModal) btnCancelAvatarModal.addEventListener('click', closeAvatarModal);

    // Preset Grid item click
    document.querySelectorAll('.avatar-grid-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.avatar-grid-item').forEach(i => i.classList.remove('selected'));
            this.classList.add('selected');

            const path = this.getAttribute('data-path');
            const name = this.getAttribute('data-name');
            currentSelectedAvatar = path;

            if (spotlightImg) spotlightImg.src = '/' + path;
            if (spotlightBadge) spotlightBadge.textContent = name;
            if (activeAvatarInput) activeAvatarInput.value = path;
            if (uploadStatus) uploadStatus.style.display = 'none';
        });
    });

    // Upload from device gallery
    if (btnTriggerUpload && inputDevicePhoto) {
        btnTriggerUpload.addEventListener('click', () => {
            inputDevicePhoto.click();
        });

        inputDevicePhoto.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('Silakan pilih file gambar (JPG, PNG, atau WEBP).');
                return;
            }

            // Max 4MB
            if (file.size > 4 * 1024 * 1024) {
                alert('Ukuran foto maksimal 4MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(evt) {
                const base64Data = evt.target.result;
                currentSelectedAvatar = base64Data;

                if (spotlightImg) spotlightImg.src = base64Data;
                if (spotlightBadge) spotlightBadge.textContent = 'Foto Galeri Perangkat';
                if (activeAvatarInput) activeAvatarInput.value = base64Data;

                document.querySelectorAll('.avatar-grid-item').forEach(i => i.classList.remove('selected'));
                if (uploadStatus) uploadStatus.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    // Save avatar choice permanently
    if (btnSaveAvatarModal) {
        btnSaveAvatarModal.addEventListener('click', function() {
            if (!currentSelectedAvatar) {
                closeAvatarModal();
                return;
            }

            btnSaveAvatarModal.disabled = true;
            btnSaveAvatarModal.textContent = 'Menyimpan...';

            fetch('/api/profile', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    avatar: currentSelectedAvatar
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                btnSaveAvatarModal.disabled = false;
                btnSaveAvatarModal.textContent = 'Simpan Foto Profil';

                if (status === 200) {
                    const avatarUrl = body.avatar_url || (
                        currentSelectedAvatar.startsWith('data:') || currentSelectedAvatar.startsWith('http') || currentSelectedAvatar.startsWith('/')
                            ? currentSelectedAvatar
                            : '/' + currentSelectedAvatar
                    );

                    const sideImg = document.getElementById('sidebar-avatar-img');
                    const modalImg = document.getElementById('modal-avatar-preview');
                    const setImg = document.getElementById('settings-avatar-preview');
                    const spotImg = document.getElementById('avatar-spotlight-img');
                    const navImg = document.querySelector('#btn-user img');
                    const hiddenVal = document.getElementById('active-selected-avatar-val');

                    if (sideImg) sideImg.src = avatarUrl;
                    if (modalImg) modalImg.src = avatarUrl;
                    if (setImg) setImg.src = avatarUrl;
                    if (spotImg) spotImg.src = avatarUrl;
                    if (navImg) navImg.src = avatarUrl;
                    if (hiddenVal) hiddenVal.value = body.avatar;
                    currentSelectedAvatar = body.avatar;

                    if (window.SweetDreamsAuth) {
                        try {
                            const cur = window.SweetDreamsAuth.getCurrentUser() || {};
                            cur.avatar = body.avatar;
                            window.SweetDreamsAuth.saveCurrentUser(cur);
                        } catch(e) {}
                    }

                    closeAvatarModal();
                    showProfileToast('Foto profil baru berhasil disimpan!');
                } else {
                    const msg = body.message || 'Gagal menyimpan foto profil.';
                    alert(msg);
                }
            })
            .catch(() => {
                btnSaveAvatarModal.disabled = false;
                btnSaveAvatarModal.textContent = 'Simpan Foto Profil';
                alert('Tidak bisa menghubungi server, coba lagi.');
            });
        });
    }

    // Initial sync
    syncActiveUserData();
    window.addEventListener('sweetdreams_user_updated', syncActiveUserData);

    // Restore saved tab if returning from tracking page
    if (window._pendingTab) {
        switchTab(window._pendingTab);
        delete window._pendingTab;
    }

   

    // 7. Logout Handlers with Custom Confirmation Modal
    const logoutModal = document.getElementById('modal-logout-confirm');
    const btnCancelLogout = document.getElementById('btn-cancel-logout-modal');
    const btnConfirmLogout = document.getElementById('btn-confirm-do-logout');
    const btnSidebarLogout = document.getElementById('btn-profile-logout');
    const btnSettingLogout = document.getElementById('btn-setting-logout');

    function openLogoutModal(e) {
        if (e) e.preventDefault();
        if (logoutModal) logoutModal.classList.add('open');
    }

    function closeLogoutModal() {
        if (logoutModal) logoutModal.classList.remove('open');
    }

    function executeLogout() {
        try {
            localStorage.removeItem('sweetdreams_auth_user');
            if (window.SweetDreamsAuth) {
                localStorage.removeItem(window.SweetDreamsAuth.USER_KEY);
            }
        } catch(e) {}
        window.location.href = '/logout';
    }

    if (btnSidebarLogout) btnSidebarLogout.addEventListener('click', openLogoutModal);
    if (btnSettingLogout) btnSettingLogout.addEventListener('click', openLogoutModal);
    if (btnCancelLogout) btnCancelLogout.addEventListener('click', closeLogoutModal);
    if (btnConfirmLogout) btnConfirmLogout.addEventListener('click', executeLogout);

    // Also close on background click
    if (logoutModal) {
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) closeLogoutModal();
        });
    }
});
</script>
@endsection
