@extends('layouts.app')

@section('title', 'Akun Saya - Sweet Dreams')

@section('content')
<style>
    /* ===== PROFILE PAGE WRAPPER ===== */
    .profile-page-wrapper {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.5rem 2rem 5rem;
    }

    /* Header */
    .profile-header {
        margin-bottom: 2.5rem;
    }
    .profile-eyebrow {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #b87b58;
        display: block;
        margin-bottom: 0.4rem;
    }
    .profile-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2.5rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.5rem 0;
    }
    .profile-header p {
        font-size: 0.95rem;
        color: #8a6a72;
        margin: 0;
    }

    /* Main Grid */
    .profile-layout-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    /* ===== SIDEBAR ===== */
    .profile-sidebar-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 24px;
        padding: 2rem 1.5rem;
        box-shadow: 0 4px 20px rgba(212, 77, 110, 0.04);
        position: sticky;
        top: 88px;
    }
    .sidebar-avatar-box {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 0.85rem;
        border: 2.5px solid #fbd5df;
        background: #fdf2f5;
    }
    .sidebar-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .sidebar-user-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.2rem;
        font-weight: 700;
        color: #3a2a2e;
        text-align: center;
        margin: 0 0 0.2rem 0;
    }
    .sidebar-user-email {
        font-size: 0.8rem;
        color: #8a6a72;
        text-align: center;
        margin: 0 0 1.75rem 0;
    }

    /* Sidebar Navigation Menu */
    .profile-nav-menu {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .profile-nav-item {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        color: #5a3a42;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
        width: 100%;
    }
    .profile-nav-item:hover {
        background: #fdf2f5;
        color: #d44d6e;
    }
    .profile-nav-item.active {
        background: #fdf0f4;
        color: #d44d6e;
        font-weight: 700;
    }
    .profile-nav-item svg {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    /* ===== RIGHT: CONTENT PANELS ===== */
    .profile-tab-panel {
        display: none;
        animation: fadeInTab 0.3s ease;
    }
    .profile-tab-panel.active {
        display: block;
    }
    @keyframes fadeInTab {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== STATS ROW (RINGKASAN) ===== */
    .stats-cards-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.75rem;
    }
    .stat-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.03);
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-card.highlight {
        background: #fef2f5;
        border-color: #fbd5df;
    }
    .stat-num {
        font-family: 'Playfair Display', serif;
        font-size: 2.25rem;
        font-weight: 700;
        color: #3a2a2e;
        line-height: 1;
        margin: 0 0 0.5rem 0;
    }
    .stat-label {
        font-size: 0.85rem;
        color: #8a6a72;
        margin: 0;
    }

    /* White Section Cards */
    .profile-content-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
        padding: 1.75rem 2rem;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.03);
        margin-bottom: 1.75rem;
    }
    .card-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.35rem;
    }
    .card-heading-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .btn-edit-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border: 1.5px solid #d4b8c0;
        border-radius: 50px;
        background: #ffffff;
        color: #5a3a42;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 18px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-edit-pill:hover {
        border-color: #d44d6e;
        color: #d44d6e;
        background: #fffbfa;
    }
    .link-view-all-pink {
        color: #d44d6e;
        font-size: 0.88rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: color 0.2s;
    }
    .link-view-all-pink:hover {
        color: #b83a58;
    }

    /* Data Profil 3 Columns */
    .profile-info-cols {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .info-col-item {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .info-col-label {
        font-size: 0.78rem;
        color: #8a6a72;
    }
    .info-col-val {
        font-size: 0.92rem;
        font-weight: 700;
        color: #3a2a2e;
    }

    /* Pesanan Terbaru Rows */
    .recent-orders-list {
        display: flex;
        flex-direction: column;
    }
    .recent-order-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #fae6ec;
    }
    .recent-order-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .order-row-left {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .order-row-id {
        font-size: 0.78rem;
        color: #8a6a72;
    }
    .order-row-name {
        font-size: 0.92rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .order-row-right {
        display: flex;
        align-items: center;
        gap: 2rem;
    }
    .order-status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 50px;
    }
    .order-status-badge.shipping {
        background: #fdf2f5;
        color: #d44d6e;
    }
    .order-status-badge.completed {
        background: #ecfdf5;
        color: #10b981;
    }
    .order-status-badge.pending {
        background: #fef9c3;
        color: #b45309;
    }
    .order-row-price {
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #3a2a2e;
        min-width: 100px;
        text-align: right;
    }

    /* ===== TAB: PESANAN SAYA ===== */
    .my-orders-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    .my-order-card {
        background: #ffffff;
        border: 1.5px solid #fbd5df;
        border-radius: 20px;
        padding: 1.5rem 1.75rem;
        box-shadow: 0 4px 16px rgba(212, 77, 110, 0.03);
    }
    .my-order-meta-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 1rem;
        border-bottom: 1px solid #fae6ec;
        margin-bottom: 1.25rem;
    }
    .order-meta-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
    }
    .order-meta-date {
        color: #3a2a2e;
        font-weight: 600;
    }
    .order-meta-invoice {
        color: #8a6a72;
        font-weight: 700;
    }
    .my-order-product-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }
    .order-product-left {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .order-product-img {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        overflow: hidden;
        border: 1.5px solid #fbd5df;
        background: #faf6f7;
        flex-shrink: 0;
    }
    .order-product-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .order-product-title {
        font-family: 'Inter', sans-serif;
        font-size: 0.98rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0 0 0.35rem 0;
    }
    .order-product-variant {
        font-size: 0.82rem;
        color: #8a6a72;
        margin: 0;
    }
    .order-product-price {
        font-family: 'Inter', sans-serif;
        font-size: 1.05rem;
        font-weight: 700;
        color: #3a2a2e;
    }
    .my-order-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 1rem;
        border-top: 1px solid #fae6ec;
    }
    .order-total-text {
        font-size: 0.88rem;
        color: #5a3a42;
    }
    .order-total-text strong {
        color: #d44d6e;
        font-size: 1.05rem;
    }
    .btn-cancel-order {
        background: #ffffff;
        border: 1.5px solid #e06b88;
        color: #d44d6e;
        font-family: 'Inter', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 7px 20px;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-cancel-order:hover {
        background: #fdf2f5;
        border-color: #d44d6e;
    }

    /* ===== EDIT PROFILE MODAL ===== */
    .edit-profile-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(58, 42, 46, 0.45);
        backdrop-filter: blur(4px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
    }
    .edit-profile-modal-overlay.open {
        display: flex;
    }
    .edit-modal-card {
        background: #ffffff;
        border-radius: 24px;
        max-width: 480px;
        width: 100%;
        padding: 2.25rem 2rem;
        box-shadow: 0 16px 40px rgba(0,0,0,0.18);
        animation: scaleUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }
    .modal-header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
    }
    .modal-header-title {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        font-weight: 700;
        color: #3a2a2e;
        margin: 0;
    }
    .modal-close-btn {
        background: none;
        border: none;
        color: #8a6a72;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .modal-close-btn:hover {
        color: #d44d6e;
    }

    /* Avatar with Camera Badge */
    .modal-avatar-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
        margin: 0 auto 1.75rem;
    }
    .modal-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #fbd5df;
        background: #fdf2f5;
    }
    .modal-avatar-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .avatar-camera-badge {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #ffffff;
        border: 1.5px solid #d4b8c0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3a2a2e;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
    }
    .avatar-camera-badge:hover {
        border-color: #d44d6e;
        color: #d44d6e;
        transform: scale(1.1);
    }

    /* Modal Form Fields */
    .modal-form-group {
        margin-bottom: 1rem;
    }
    .modal-form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #5a3a42;
        margin-bottom: 0.35rem;
    }
    .modal-input {
        width: 100%;
        height: 44px;
        padding: 0 1rem;
        border: 1.5px solid #e8d0d6;
        border-radius: 10px;
        background: #ffffff;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        color: #3a2a2e;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .modal-input:focus {
        border-color: #d44d6e;
    }
    .modal-row-2col {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.85rem;
    }
    .modal-btn-row {
        display: flex;
        justify-content: space-between;
        gap: 1rem;
        margin-top: 1.75rem;
    }
    .btn-modal-cancel {
        flex: 1;
        height: 44px;
        border: 1.5px solid #d4b8c0;
        background: #ffffff;
        color: #5a3a42;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-modal-cancel:hover {
        border-color: #3a2a2e;
    }
    .btn-modal-save {
        flex: 1.4;
        height: 44px;
        border: none;
        background: #e06b88;
        color: #ffffff;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-size: 0.88rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        box-shadow: 0 3px 12px rgba(224, 107, 136, 0.3);
    }
    .btn-modal-save:hover {
        background: #d44d6e;
    }

    /* Toast Notification */
    .profile-toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: #3a2a2e;
        color: #ffffff;
        padding: 0.85rem 1.4rem;
        border-radius: 12px;
        font-size: 0.88rem;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 0.65rem;
        z-index: 1001;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
    }
    .profile-toast.show {
        transform: translateY(0);
        opacity: 1;
        pointer-events: auto;
    }
    .profile-toast svg {
        color: #10b981;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 900px) {
        .profile-layout-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        .stats-cards-row {
            grid-template-columns: 1fr;
        }
        .profile-info-cols {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>

<div class="profile-page-wrapper">
    {{-- Header --}}
    <div class="profile-header">
        <span class="profile-eyebrow">MY SWEET DREAM</span>
        <h1 id="page-user-greeting">Selamat datang, Alya</h1>
        <p>Kelola profil, pantau pesanan, dan temukan kembali koleksi favoritmu.</p>
    </div>

    {{-- Layout Grid --}}
    <div class="profile-layout-grid">
        
        {{-- Left: Sidebar --}}
        <aside class="profile-sidebar-card">
            <div class="sidebar-avatar-box">
                <img id="sidebar-avatar-img" src="{{ asset($user['avatar']) }}" alt="Alya Putri">
            </div>
            <h3 class="sidebar-user-name" id="sidebar-user-name">{{ $user['name'] }}</h3>
            <p class="sidebar-user-email" id="sidebar-user-email">{{ $user['email'] }}</p>

            <nav class="profile-nav-menu">
                <button class="profile-nav-item active" data-tab="ringkasan">
                    <i data-lucide="layout-grid"></i>
                    <span>Ringkasan</span>
                </button>
                <button class="profile-nav-item" data-tab="pesanan">
                    <i data-lucide="shopping-bag"></i>
                    <span>Pesanan saya</span>
                </button>
                <button class="profile-nav-item" data-tab="alamat">
                    <i data-lucide="map-pin"></i>
                    <span>Alamat</span>
                </button>
                <button class="profile-nav-item" data-tab="wishlist">
                    <i data-lucide="heart"></i>
                    <span>Wishlist</span>
                </button>
                <button class="profile-nav-item" data-tab="pengaturan">
                    <i data-lucide="settings"></i>
                    <span>Pengaturan</span>
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
                    <div class="profile-info-cols">
                        <div class="info-col-item">
                            <span class="info-col-label">Nama lengkap</span>
                            <span class="info-col-val" id="disp-user-name">{{ $user['name'] }}</span>
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
                        <span class="link-view-all-pink" id="link-goto-myorders">Lihat semua</span>
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. TAB PESANAN SAYA --}}
            <div class="profile-tab-panel" id="tab-panel-pesanan">
                <div class="my-orders-list">
                    @foreach($myOrders as $idx => $order)
                        <div class="my-order-card" id="my-order-{{ $idx + 1 }}">
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
                                <button class="btn-cancel-order" onclick="if(confirm('Batalkan pesanan ini?')) { document.getElementById('my-order-{{ $idx + 1 }}').style.opacity = '0.5'; this.textContent = 'Dibatalkan'; this.disabled = true; }">
                                    Batalkan Pesanan
                                </button>
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
                        <button class="btn-edit-pill" onclick="alert('Form tambah alamat baru siap dibuka');">+ Tambah Alamat</button>
                    </div>
                    @foreach($addresses as $addr)
                        <div style="padding: 1rem 0; border-bottom: 1px solid #fae6ec;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.4rem;">
                                <span style="font-weight:700; color:#3a2a2e;">{{ $addr['label'] }} @if($addr['is_primary']) <small style="background:#fce7ee; color:#d44d6e; padding:2px 8px; border-radius:50px; font-size:0.72rem;">Utama</small> @endif</span>
                                <span style="color:#d44d6e; font-size:0.82rem; cursor:pointer; font-weight:600;" onclick="document.getElementById('btn-open-edit-modal').click();">Ubah</span>
                            </div>
                            <p style="margin:0 0 0.25rem 0; font-size:0.88rem; color:#5a3a42;"><strong>{{ $addr['name'] }}</strong> ({{ $addr['phone'] }})</p>
                            <p style="margin:0; font-size:0.85rem; color:#8a6a72; line-height:1.5;">{{ $addr['address'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 4. TAB WISHLIST --}}
            <div class="profile-tab-panel" id="tab-panel-wishlist">
                <div class="profile-content-card">
                    <div class="card-header-row">
                        <h2 class="card-heading-title">Wishlist Saya (12)</h2>
                        <a href="/katalog" class="link-view-all-pink">Lihat Katalog</a>
                    </div>
                    <p style="font-size:0.9rem; color:#8a6a72; margin:0 0 1.5rem 0;">Simpan busana tidur favorit Anda untuk dibeli kapan saja.</p>
                    <div style="display:grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                        <div style="display:flex; gap:1rem; padding:1rem; border:1px solid #fbd5df; border-radius:14px; align-items:center;">
                            <img src="{{ asset('images/sailor-rabbit-main.jpg') }}" style="width:64px; height:64px; border-radius:10px; object-fit:cover;" alt="Sailor Rabbit Set">
                            <div style="flex:1;">
                                <h4 style="margin:0 0 0.25rem; font-size:0.92rem; color:#3a2a2e;">Sailor Rabbit Set</h4>
                                <span style="font-size:0.88rem; font-weight:700; color:#d44d6e;">Rp 280.000</span>
                            </div>
                            <a href="/produk/sailor-rabbit-set" class="btn-edit-pill">Lihat</a>
                        </div>
                        <div style="display:flex; gap:1rem; padding:1rem; border:1px solid #fbd5df; border-radius:14px; align-items:center;">
                            <img src="{{ asset('images/product-kimono-silk.jpg') }}" style="width:64px; height:64px; border-radius:10px; object-fit:cover;" alt="Kimono Silk Premium">
                            <div style="flex:1;">
                                <h4 style="margin:0 0 0.25rem; font-size:0.92rem; color:#3a2a2e;">Kimono Silk Premium</h4>
                                <span style="font-size:0.88rem; font-weight:700; color:#d44d6e;">Rp 450.000</span>
                            </div>
                            <a href="/produk/kimono-silk-premium" class="btn-edit-pill">Lihat</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. TAB PENGATURAN --}}
            <div class="profile-tab-panel" id="tab-panel-pengaturan">
                <div class="profile-content-card">
                    <h2 class="card-heading-title" style="margin-bottom:1.5rem;">Pengaturan Akun</h2>
                    <div style="display:flex; flex-direction:column; gap:1.25rem;">
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#3a2a2e;">Notifikasi Email & Promo</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Dapatkan kabar diskon eksklusif dan status pesanan</span>
                            </div>
                            <input type="checkbox" checked style="accent-color:#d44d6e; width:18px; height:18px;">
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1rem; border-bottom:1px solid #fae6ec;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#3a2a2e;">Keamanan Kata Sandi</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Terakhir diubah 3 bulan lalu</span>
                            </div>
                            <button class="btn-edit-pill" onclick="document.getElementById('btn-open-edit-modal').click();">Ubah Sandi</button>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div>
                                <strong style="display:block; font-size:0.9rem; color:#f43f5e;">Keluar dari Akun</strong>
                                <span style="font-size:0.8rem; color:#8a6a72;">Akhiri sesi Anda pada perangkat ini</span>
                            </div>
                            <button class="btn-edit-pill" style="color:#f43f5e; border-color:#fca5a5;" onclick="alert('Berhasil keluar'); window.location.href='/';">Logout</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- EDIT PROFILE MODAL --}}
<div class="edit-profile-modal-overlay" id="edit-profile-modal">
    <div class="edit-modal-card">
        <div class="modal-header-row">
            <h3 class="modal-header-title">Ubah Alamat Pengiriman</h3>
            <button class="modal-close-btn" id="btn-close-edit-modal" aria-label="Tutup Modal">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        {{-- Avatar with Camera Icon --}}
        <div class="modal-avatar-wrapper">
            <div class="modal-avatar-img">
                <img src="{{ asset($user['avatar']) }}" alt="{{ $user['name'] }}">
            </div>
            <div class="avatar-camera-badge" title="Ganti Foto">
                <i data-lucide="camera" style="width:14px;height:14px;"></i>
            </div>
        </div>

        {{-- Form Fields --}}
        <form id="edit-profile-form" onsubmit="event.preventDefault();">
            <div class="modal-form-group">
                <label for="edit-input-nama">Nama Lengkap</label>
                <input type="text" id="edit-input-nama" class="modal-input" value="{{ $user['name'] }}" required>
            </div>

            <div class="modal-form-group">
                <label for="edit-input-email">Email</label>
                <input type="email" id="edit-input-email" class="modal-input" value="alyaputri@gmail.com" required>
            </div>

            <div class="modal-row-2col">
                <div class="modal-form-group">
                    <label for="edit-input-birthdate">Tanggal Lahir</label>
                    <input type="text" id="edit-input-birthdate" class="modal-input" value="{{ $user['city'] }}">
                </div>
                <div class="modal-form-group">
                    <label for="edit-input-password">Verifikasi Kata Sandi</label>
                    <input type="password" id="edit-input-password" class="modal-input" value="*********">
                </div>
            </div>

            <div class="modal-btn-row">
                <button type="button" class="btn-modal-cancel" id="btn-cancel-edit">Batal</button>
                <button type="submit" class="btn-modal-save" id="btn-save-profile">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- TOAST NOTIFICATION --}}
<div class="profile-toast" id="profile-toast">
    <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
    <span id="profile-toast-msg">Perubahan profil berhasil disimpan!</span>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Re-init Lucide Icons
    lucide.createIcons();

    // 1. Sidebar Tab Switching
    const navItems = document.querySelectorAll('.profile-nav-item');
    const tabPanels = document.querySelectorAll('.profile-tab-panel');

    function switchTab(targetTab) {
        navItems.forEach(item => {
            if (item.getAttribute('data-tab') === targetTab) {
                item.classList.add('active');
            } else {
                item.classList.remove('active');
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

    navItems.forEach(item => {
        item.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');
            switchTab(target);
        });
    });

    // Link 'Lihat semua' in recent orders -> switches to 'pesanan'
    const linkGotoOrders = document.getElementById('link-goto-myorders');
    if (linkGotoOrders) {
        linkGotoOrders.addEventListener('click', function() {
            switchTab('pesanan');
        });
    }

    // 2. Edit Profile Modal
    const btnOpenModal = document.getElementById('btn-open-edit-modal');
    const btnCloseModal = document.getElementById('btn-close-edit-modal');
    const btnCancelEdit = document.getElementById('btn-cancel-edit');
    const modal = document.getElementById('edit-profile-modal');
    const form = document.getElementById('edit-profile-form');
    const toast = document.getElementById('profile-toast');
    const toastMsg = document.getElementById('profile-toast-msg');

    function openModal() {
        modal.classList.add('open');
    }
    function closeModal() {
        modal.classList.remove('open');
    }

    if (btnOpenModal) btnOpenModal.addEventListener('click', openModal);
    if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
    if (btnCancelEdit) btnCancelEdit.addEventListener('click', closeModal);

    // Save profile changes
    if (form) {
        form.addEventListener('submit', function() {
            const newName = document.getElementById('edit-input-nama').value.trim();
            const newEmail = document.getElementById('edit-input-email').value.trim();
            const newCity = document.getElementById('edit-input-birthdate').value.trim();

            if (newName) {
                document.getElementById('sidebar-user-name').textContent = newName;
                document.getElementById('page-user-greeting').textContent = 'Selamat datang, ' + newName.split(' ')[0];
                document.getElementById('disp-user-name').textContent = newName;
            }
            if (newEmail) {
                document.getElementById('sidebar-user-email').textContent = newEmail;
            }

            closeModal();

            // Show toast feedback
            if (toast) {
                toastMsg.textContent = 'Perubahan profil berhasil disimpan!';
                toast.classList.add('show');
                setTimeout(() => {
                    toast.classList.remove('show');
                }, 3000);
            }
        });
    }
});
</script>
@endsection
