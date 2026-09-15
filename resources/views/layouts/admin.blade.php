<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Sweet Dream Admin</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-ink antialiased bg-[#F7F5F4]">

<div class="flex min-h-screen">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-[235px] shrink-0 bg-ink text-white flex flex-col">
        <div class="flex items-center gap-3 px-6 py-6">
            <div class="w-9 h-9 rounded-lg bg-brand flex items-center justify-center font-bold text-white">S</div>
            <div>
                <div class="font-semibold leading-tight">Sweet Dream</div>
                <div class="text-[11px] text-white/40 leading-tight tracking-wide">ADMIN CONSOLE</div>
            </div>
        </div>

        <nav class="flex-1 px-3 mt-2 space-y-1">
            @php
    $navItems = [
        ['route' => 'admin.dashboard', 'icon' => 'layout-grid', 'label' => 'Dashboard'],
        ['route' => 'admin.produk.index', 'icon' => 'shopping-bag', 'label' => 'Produk'],
        ['route' => 'admin.stok.index', 'icon' => 'boxes', 'label' => 'Stok'],
        ['route' => 'admin.pesanan.index', 'icon' => 'file-text', 'label' => 'Pesanan'],
        ['route' => 'admin.pelanggan.index', 'icon' => 'users', 'label' => 'Pelanggan'],
        ['route' => 'admin.konten.index', 'icon' => 'image', 'label' => 'Konten'],
        ['route' => 'admin.promo.index', 'icon' => 'ticket-percent', 'label' => 'Promo & Voucher'],
        ['route' => 'admin.laporan.index', 'icon' => 'bar-chart-3', 'label' => 'Laporan'],
    ];
@endphp

            @foreach ($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ Route::has($item['route']) ? route($item['route']) : '#' }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition
                          {{ $active ? 'bg-brand text-white' : 'text-white/60 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px]"></i> {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-6 py-5 border-t border-white/10">
            <div class="text-[10px] tracking-wider text-white/30 mb-1.5">STATUS TOKO</div>
            <div class="flex items-center gap-2 text-sm font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Toko aktif
            </div>
        </div>
    </aside>

    {{-- ===== MAIN ===== --}}
    <main class="flex-1 min-w-0">

        <header class="flex items-center justify-between px-8 py-6">
            <div>
                <h1 class="text-2xl font-bold text-ink">@yield('page-title', 'Dashboard')</h1>
                <p class="text-sm text-ink-muted mt-0.5">@yield('page-subtitle')</p>
            </div>
            <div class="flex items-center gap-4">
                <button class="relative w-10 h-10 rounded-full bg-white border border-black/5 flex items-center justify-center">
                    <i data-lucide="bell" class="w-[18px] h-[18px] text-ink-soft"></i>
                    <span class="absolute top-2 right-2.5 w-1.5 h-1.5 rounded-full bg-brand"></span>
                </button>
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar_url ?? 'https://i.pravatar.cc/80?img=47' }}"
                         class="w-9 h-9 rounded-full object-cover" alt="">
                    <div class="leading-tight">
                        <div class="text-sm font-semibold text-ink">{{ auth()->user()->name ?? 'Nadia Putri' }}</div>
                        <div class="text-xs text-ink-muted">{{ auth()->user()->role ?? 'Super Admin' }}</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="px-8 pb-10 space-y-6">
            @yield('content')
        </div>
    </main>
</div>

<script>lucide.createIcons();</script>
@stack('scripts')
</body>
</html>