<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Sweet Dreams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#f4f3f5; color:#2a1f24; }
        a { text-decoration:none; color:inherit; }

        .admin-layout { display:flex; min-height:100vh; }

        /* Sidebar */
        .admin-sidebar {
            width:236px; background:#20161b; color:#fff;
            display:flex; flex-direction:column; padding:1.5rem 0;
            flex-shrink:0;
            position:fixed; top:0; left:0; bottom:0;
            overflow-y:auto;
        }
        .admin-brand { display:flex; align-items:center; gap:0.7rem; padding:0 1.25rem 1.5rem; }
        .admin-brand-logo {
            width:36px; height:36px; border-radius:10px; background:#d44d6e;
            display:flex; align-items:center; justify-content:center;
            font-family:'Playfair Display',serif; font-weight:700; font-size:1.1rem;
        }
        .admin-brand-text strong { display:block; font-size:0.95rem; }
        .admin-brand-text span { display:block; font-size:0.68rem; color:#a08a90; letter-spacing:0.04em; }

        .admin-nav { flex:1; display:flex; flex-direction:column; gap:0.2rem; padding:0 0.75rem; }
        .admin-nav-item {
            display:flex; align-items:center; gap:0.7rem;
            padding:0.65rem 0.85rem; border-radius:10px;
            font-size:0.88rem; color:#cbb7bd; font-weight:500;
        }
        .admin-nav-item:hover { background:rgba(255,255,255,0.06); color:#fff; }
        .admin-nav-item.active { background:#d44d6e; color:#fff; }

        .admin-sidebar-footer { padding:1rem 1.25rem 0; }
        .admin-status-label { font-size:0.68rem; color:#a08a90; letter-spacing:0.04em; margin-bottom:0.4rem; }
        .admin-status-value { display:flex; align-items:center; gap:0.4rem; font-size:0.85rem; font-weight:600; }
        .admin-status-dot { width:7px; height:7px; border-radius:50%; background:#3ecf8e; }

        /* Main */
        .admin-main { flex:1; display:flex; flex-direction:column; min-width:0; margin-left:236px; }
        .admin-header {
            display:flex; justify-content:space-between; align-items:center;
            padding:1.5rem 2rem; background:#f4f3f5;
        }
        .admin-header h1 { font-size:1.5rem; font-weight:700; }
        .admin-header p { font-size:0.85rem; color:#8a6a72; margin-top:0.15rem; }
        .admin-header-right { display:flex; align-items:center; gap:1rem; }
        .admin-bell {
            width:38px; height:38px; border-radius:10px; background:#fff;
            display:flex; align-items:center; justify-content:center; position:relative;
        }
        .admin-bell .dot { position:absolute; top:8px; right:8px; width:7px; height:7px; border-radius:50%; background:#d44d6e; }
        .admin-avatar-row { display:flex; align-items:center; gap:0.6rem; }
        .admin-avatar-circle {
            width:36px; height:36px; border-radius:50%; background:#f2c9d3;
            display:flex; align-items:center; justify-content:center;
            font-weight:700; color:#a13655;
        }
        .admin-avatar-text strong { display:block; font-size:0.85rem; }
        .admin-avatar-text span { display:block; font-size:0.72rem; color:#8a6a72; }

        .admin-content { padding:0 2rem 3rem; }

        /* Shared card styles used across admin pages */
        .admin-card { background:#fff; border-radius:16px; padding:1.5rem; }
        .admin-stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:1.2rem; margin-bottom:1.5rem; }
        .admin-stat-card { background:#fff; border-radius:16px; padding:1.3rem 1.5rem; }
        .admin-stat-label { font-size:0.82rem; color:#8a6a72; margin-bottom:0.5rem; }
        .admin-stat-value { font-size:1.5rem; font-weight:700; }
        @media (max-width: 1100px) { .admin-stat-grid { grid-template-columns:repeat(2,1fr); } }
    </style>
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-brand">
            <div class="admin-brand-logo">S</div>
            <div class="admin-brand-text">
                <strong>Sweet Dream</strong>
                <span>ADMIN CONSOLE</span>
            </div>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-grid" style="width:18px;height:18px;"></i> Dashboard
            </a>
            <a href="{{ route('admin.products') }}" class="admin-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i data-lucide="shopping-bag" style="width:18px;height:18px;"></i> Produk
            </a>
            <a href="{{ route('admin.stock') }}" class="admin-nav-item {{ request()->routeIs('admin.stock*') ? 'active' : '' }}">
                <i data-lucide="boxes" style="width:18px;height:18px;"></i> Stok
            </a>
            <a href="{{ route('admin.orders') }}" class="admin-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i data-lucide="file-text" style="width:18px;height:18px;"></i> Pesanan
            </a>
            <a href="{{ route('admin.customers') }}" class="admin-nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <i data-lucide="users" style="width:18px;height:18px;"></i> Pelanggan
            </a>
            <a href="{{ route('admin.content') }}" class="admin-nav-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}">
                <i data-lucide="image" style="width:18px;height:18px;"></i> Konten
            </a>
                        <a href="{{ route('admin.vouchers') }}" class="admin-nav-item {{ request()->routeIs('admin.vouchers*') ? 'active' : '' }}">
                <i data-lucide="ticket" style="width:18px;height:18px;"></i> Promo & Voucher
            </a>
            <a href="{{ route('admin.reports') }}" class="admin-nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                <i data-lucide="bar-chart-2" style="width:18px;height:18px;"></i> Laporan
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-bottom:1rem;">
                @csrf
                <button type="submit" class="admin-nav-item" style="width:100%; background:none; border:none; color:#e8a4b0; cursor:pointer; text-align:left;">
                    <i data-lucide="log-out" style="width:18px;height:18px;"></i> Logout
                </button>
            </form>
            <div class="admin-status-label">STATUS TOKO</div>
            <div class="admin-status-value"><span class="admin-status-dot"></span> Toko aktif</div>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-header">
            <div>
                <h1>@yield('page-title')</h1>
                <p>@yield('page-subtitle')</p>
            </div>
            <div class="admin-header-right">
                <div class="admin-bell"><i data-lucide="bell" style="width:18px;height:18px;"></i><span class="dot"></span></div>
                <div class="admin-avatar-row">
                    <div class="admin-avatar-circle">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="admin-avatar-text">
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@yield('scripts')
</body>
</html>