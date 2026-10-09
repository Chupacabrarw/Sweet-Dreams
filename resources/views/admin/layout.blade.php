<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Sweet Dreams</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/layouts/admin.css')
    @stack('page-styles')
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar" id="admin-sidebar">
        <div class="admin-brand">
            <a class="admin-brand-logo" href="{{ route('admin.dashboard') }}" aria-label="Sweet Dreams Admin">
                <img src="/images/logo.png" alt="Sweet Dream Logo">
            </a>
        </div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                <i data-lucide="layout-grid" style="width:18px;height:18px;"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('admin.products') }}" class="admin-nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}" title="Produk">
                <i data-lucide="shopping-bag" style="width:18px;height:18px;"></i><span>Produk</span>
            </a>
            <a href="{{ route('admin.stock') }}" class="admin-nav-item {{ request()->routeIs('admin.stock*') ? 'active' : '' }}" title="Stok">
                <i data-lucide="boxes" style="width:18px;height:18px;"></i><span>Stok</span>
            </a>
            <a href="{{ route('admin.orders') }}" class="admin-nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" title="Pesanan">
                <i data-lucide="file-text" style="width:18px;height:18px;"></i><span>Pesanan</span>
            </a>
            <a href="{{ route('admin.customers') }}" class="admin-nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}" title="Pelanggan">
                <i data-lucide="users" style="width:18px;height:18px;"></i><span>Pelanggan</span>
            </a>
            <a href="{{ route('admin.content') }}" class="admin-nav-item {{ request()->routeIs('admin.content*') ? 'active' : '' }}" title="Konten">
                <i data-lucide="image" style="width:18px;height:18px;"></i><span>Konten</span>
            </a>
            @php($unreadContactMessages = \App\Models\ContactMessage::whereNull('read_at')->count())
            <a href="{{ route('admin.contact-messages') }}" class="admin-nav-item admin-contact-nav-item {{ request()->routeIs('admin.contact-messages*') ? 'active' : '' }}" title="Pesan Kontak">
                <i data-lucide="messages-square" style="width:18px;height:18px;"></i><span>Pesan Kontak</span>
                @if($unreadContactMessages > 0)
                    <span class="admin-nav-badge" aria-label="{{ $unreadContactMessages }} pesan belum dibaca">{{ $unreadContactMessages > 99 ? '99+' : $unreadContactMessages }}</span>
                @endif
            </a>
                        <a href="{{ route('admin.vouchers') }}" class="admin-nav-item {{ request()->routeIs('admin.vouchers*') ? 'active' : '' }}" title="Promo & Voucher">
                <i data-lucide="ticket" style="width:18px;height:18px;"></i><span>Promo & Voucher</span>
            </a>
            <a href="{{ route('admin.reports') }}" class="admin-nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" title="Laporan">
                <i data-lucide="bar-chart-2" style="width:18px;height:18px;"></i><span>Laporan</span>
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-bottom:1rem;">
                @csrf
                <button type="submit" class="admin-nav-item admin-logout-item" style="width:100%; background:none; border:none; color:#e8a4b0; cursor:pointer; text-align:left;">
                    <i data-lucide="log-out" style="width:18px;height:18px;"></i><span>Logout</span>
                </button>
            </form>
            <div class="admin-status-label">STATUS TOKO</div>
            <div class="admin-status-value"><span class="admin-status-dot"></span><span>Toko aktif</span></div>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-header">
            <div class="admin-header-title">
                <button type="button" class="admin-sidebar-toggle" id="admin-sidebar-toggle"
                        aria-label="Ciutkan sidebar" aria-controls="admin-sidebar" aria-expanded="true">
                    <i data-lucide="panel-left-close" style="width:19px;height:19px;"></i>
                </button>
                <div>
                    <h1>@yield('page-title')</h1>
                    <p>@yield('page-subtitle')</p>
                </div>
            </div>
            <div class="admin-header-right">
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
<button type="button" class="admin-sidebar-backdrop" id="admin-sidebar-backdrop" aria-label="Tutup menu"></button>

<?php
    $adminToasts = collect([
        'success' => session('success'),
        'error' => session('error'),
        'warning' => session('warning'),
        'info' => session('info'),
        'status' => session('status'),
    ])->filter(fn ($message) => filled($message));

    if ($errors->any()) {
        $adminToasts->put('error', implode(' ', $errors->all()));
    }
?>
<div class="admin-toast-stack" aria-live="polite" aria-atomic="false">
    @foreach($adminToasts as $type => $message)
        <div class="admin-toast admin-toast-{{ $type }}" role="{{ $type === 'error' ? 'alert' : 'status' }}">
            <i data-lucide="{{ $type === 'error' ? 'circle-alert' : 'circle-check' }}" aria-hidden="true"></i>
            <span>{{ $message }}</span>
            <button type="button" class="admin-toast-close" aria-label="Tutup notifikasi">&times;</button>
        </div>
    @endforeach
</div>

<script>
    const adminSidebar = document.getElementById('admin-sidebar');
    const sidebarToggle = document.getElementById('admin-sidebar-toggle');
    const sidebarBackdrop = document.getElementById('admin-sidebar-backdrop');
    const mobileSidebar = window.matchMedia('(max-width: 900px)');

    function setSidebarExpanded(expanded) {
        document.body.classList.toggle('admin-sidebar-open', expanded && mobileSidebar.matches);
        document.body.classList.toggle('admin-sidebar-collapsed', !expanded && !mobileSidebar.matches);
        sidebarToggle.setAttribute('aria-expanded', String(expanded));
        sidebarToggle.setAttribute('aria-label', expanded ? 'Ciutkan sidebar' : 'Luaskan sidebar');
        sidebarToggle.innerHTML = `<i data-lucide="${expanded ? 'panel-left-close' : 'panel-left-open'}" style="width:19px;height:19px;"></i>`;
        lucide.createIcons();
    }

    setSidebarExpanded(!mobileSidebar.matches);

    sidebarToggle.addEventListener('click', () => {
        const expanded = mobileSidebar.matches
            ? !document.body.classList.contains('admin-sidebar-open')
            : document.body.classList.contains('admin-sidebar-collapsed');
        setSidebarExpanded(expanded);
    });

    sidebarBackdrop.addEventListener('click', () => setSidebarExpanded(false));
    adminSidebar.querySelectorAll('.admin-nav-item').forEach((item) => {
        item.addEventListener('click', () => {
            if (mobileSidebar.matches) setSidebarExpanded(false);
        });
    });

    mobileSidebar.addEventListener('change', () => {
        document.body.classList.remove('admin-sidebar-open', 'admin-sidebar-collapsed');
        setSidebarExpanded(!mobileSidebar.matches);
    });

    document.querySelectorAll('.admin-toast').forEach((toast) => {
        let gone = false;
        const dismiss = () => {
            if (gone) return;
            gone = true;
            toast.classList.add('admin-toast-leaving');
            // Fallback: hapus paksa bila transisi CSS tidak jalan
            window.setTimeout(() => toast.remove(), 600);
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        };

        toast.querySelector('.admin-toast-close').addEventListener('click', dismiss);
        window.setTimeout(dismiss, 5000);
    });

    // Toast via JS (popup kanan atas, sama seperti notifikasi server)
    window.adminToast = function(message, type = 'error') {
        const stack = document.querySelector('.admin-toast-stack');
        if (!stack) {
            alert(message);
            return;
        }
        const toast = document.createElement('div');
        toast.className = `admin-toast admin-toast-${type}`;
        toast.setAttribute('role', type === 'error' ? 'alert' : 'status');
        toast.innerHTML = `<i data-lucide="${type === 'error' ? 'circle-alert' : 'circle-check'}" aria-hidden="true"></i><span></span><button type="button" class="admin-toast-close" aria-label="Tutup notifikasi">&times;</button>`;
        toast.querySelector('span').textContent = message;
        let gone = false;
        const dismiss = () => {
            if (gone) return;
            gone = true;
            toast.classList.add('admin-toast-leaving');
            window.setTimeout(() => toast.remove(), 600);
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        };
        toast.querySelector('.admin-toast-close').addEventListener('click', dismiss);
        window.setTimeout(dismiss, 5000);
        stack.appendChild(toast);
        if (window.lucide) lucide.createIcons();
    };

    lucide.createIcons();
</script>
@yield('scripts')
</body>
</html>