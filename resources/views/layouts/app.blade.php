<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sweet Dreams - Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu. Temukan baju tidur, kimono, lingerie terbaik.">
    <meta name="keywords" content="sleepwear, baju tidur, lingerie, kimono, pakaian dalam, sweet dreams">
    <title>@yield('title', 'Sweet Dreams - Premium Sleepwear & Lingerie')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== GLOBAL ===== */
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            color: #3a2a2e;
            background: #fefdfb;
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(244,141,168,0.12);
            transition: box-shadow 0.3s ease;
        }
        .navbar.scrolled {
            box-shadow: 0 4px 24px rgba(232,107,138,0.08);
        }
        .navbar-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }
        .nav-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #d44d6e;
            text-decoration: none;
            letter-spacing: -0.02em;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            text-decoration: none;
            color: #3a2a2e;
            font-size: 0.9rem;
            font-weight: 500;
            position: relative;
            padding-bottom: 4px;
            transition: color 0.3s ease;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #d44d6e, #f48da8);
            border-radius: 1px;
            transition: width 0.3s ease;
        }
        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }
        .nav-links a:hover {
            color: #d44d6e;
        }
        .nav-links a.active {
            color: #d44d6e;
            font-weight: 600;
        }
        .nav-search {
            position: relative;
            display: flex;
            align-items: center;
        }
        .nav-search input {
            border: 1.5px solid #fbd5df;
            border-radius: 50px;
            padding: 8px 16px 8px 40px;
            font-size: 0.85rem;
            width: 220px;
            background: #fef5f7;
            color: #3a2a2e;
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        .nav-search input::placeholder {
            color: #c4a0aa;
        }
        .nav-search input:focus {
            border-color: #d44d6e;
            background: #fff;
            width: 260px;
            box-shadow: 0 0 0 3px rgba(212,77,110,0.1);
        }
        .nav-search .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #d44d6e;
            width: 16px;
            height: 16px;
        }
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .nav-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3a2a2e;
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-icon-btn:hover {
            background: #fef5f7;
            color: #d44d6e;
            transform: translateY(-1px);
        }
        .nav-icon-btn .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 16px;
            height: 16px;
            background: #d44d6e;
            color: #fff;
            border-radius: 50%;
            font-size: 0.6rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            width: 40px;
            height: 40px;
            border: none;
            background: transparent;
            cursor: pointer;
            color: #3a2a2e;
        }

        /* Mobile Nav Overlay - hidden on desktop */
        .mobile-nav {
            display: none;
        }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            width: 100%;
            height: 520px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 20%;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(105deg,
                rgba(212,77,110,0.75) 0%,
                rgba(212,77,110,0.50) 40%,
                rgba(212,77,110,0.10) 70%,
                transparent 100%
            );
        }
        .hero-content {
            position: absolute;
            top: 50%;
            left: 6%;
            transform: translateY(-50%);
            z-index: 2;
            max-width: 480px;
        }
        .hero-content h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.15;
            margin: 0 0 1rem 0;
            text-shadow: 0 2px 12px rgba(0,0,0,0.10);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.8s ease 0.2s forwards;
        }
        .hero-content p {
            font-size: 1rem;
            color: rgba(255,255,255,0.92);
            line-height: 1.6;
            margin: 0 0 1.5rem 0;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.8s ease 0.4s forwards;
        }
        .btn-shop-now {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #fff;
            color: #d44d6e;
            padding: 14px 32px;
            border-radius: 50px;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 16px rgba(0,0,0,0.10);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp 0.8s ease 0.6s forwards;
        }
        .btn-shop-now:hover {
            background: #d44d6e;
            color: #fff;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 28px rgba(212,77,110,0.35);
        }
        .btn-shop-now svg {
            transition: transform 0.3s ease;
        }
        .btn-shop-now:hover svg {
            transform: translateX(4px);
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== SECTIONS ===== */
        .section {
            max-width: 1280px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }
        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .section-header h2 {
            font-size: 1.85rem;
            font-weight: 700;
            color: #3a2a2e;
            margin: 0;
        }
        .section-header p {
            font-size: 0.9rem;
            color: #8a6a72;
            margin: 0.25rem 0 0 0;
        }
        .section-header .view-all {
            text-decoration: none;
            color: #d44d6e;
            font-size: 0.9rem;
            font-weight: 600;
            transition: color 0.3s ease;
            white-space: nowrap;
        }
        .section-header .view-all:hover {
            color: #b83a58;
        }

        /* ===== CATEGORIES ===== */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
        }
        .category-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1.5px solid #fbd5df;
            transition: all 0.4s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        .category-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(212,77,110,0.12);
            border-color: #f48da8;
        }
        .category-card-img {
            width: 100%;
            aspect-ratio: 1;
            overflow: hidden;
            background: #fef5f7;
        }
        .category-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .category-card:hover .category-card-img img {
            transform: scale(1.08);
        }
        .category-card-body {
            padding: 1rem 1.15rem;
        }
        .category-card-body h3 {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.2rem 0;
            color: #3a2a2e;
        }
        .category-card-body span {
            font-size: 0.8rem;
            color: #b48a92;
        }

        /* ===== PRODUCTS ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.75rem;
        }
        .product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            border: 1.5px solid #fbd5df;
            transition: all 0.4s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            position: relative;
        }
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 32px rgba(212,77,110,0.12);
            border-color: #f48da8;
        }
        .product-card-img {
            width: 100%;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: #fef5f7;
            position: relative;
        }
        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-card-img img {
            transform: scale(1.06);
        }
        .product-card-wishlist {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #d44d6e;
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease;
        }
        .product-card:hover .product-card-wishlist {
            opacity: 1;
            transform: scale(1);
        }
        .product-card-wishlist:hover {
            background: #d44d6e;
            color: #fff;
        }
        .product-card-body {
            padding: 1rem 1.25rem 1.25rem;
        }
        .product-card-body h3 {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.5rem 0;
            color: #3a2a2e;
        }
        .product-card-body .price {
            font-size: 0.9rem;
            font-weight: 600;
            color: #d44d6e;
        }

        /* ===== TESTIMONIALS ===== */
        .testimonials-section {
            background: #fef5f7;
            padding: 4rem 0;
            margin-top: 2rem;
        }
        .testimonials-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .testimonials-title {
            text-align: center;
            font-size: 1.85rem;
            font-weight: 700;
            color: #3a2a2e;
            margin: 0 0 2.5rem 0;
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.75rem;
        }
        .testimonial-card {
            background: #fff;
            border-radius: 16px;
            padding: 2rem;
            border: 1.5px solid #fbd5df;
            transition: all 0.3s ease;
            position: relative;
        }
        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(212,77,110,0.10);
        }
        .testimonial-card .quote-icon {
            color: #f48da8;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        .testimonial-card p {
            font-size: 0.88rem;
            line-height: 1.7;
            color: #5a3a42;
            margin: 0 0 1.25rem 0;
            font-style: italic;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .testimonial-author .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f48da8, #d44d6e);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
        }
        .testimonial-author .info h4 {
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: #3a2a2e;
            margin: 0;
        }
        .testimonial-author .info span {
            font-size: 0.78rem;
            color: #b48a92;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: #3a2a2e;
            color: #e8d5da;
            padding: 3.5rem 0 0 0;
        }
        .footer-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1fr;
            gap: 2.5rem;
        }
        .footer-brand h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 600;
            color: #f48da8;
            margin: 0 0 0.75rem 0;
        }
        .footer-brand p {
            font-size: 0.85rem;
            color: #c4a0aa;
            line-height: 1.7;
            margin: 0;
        }
        .footer-col h4 {
            font-family: 'Inter', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            margin: 0 0 1rem 0;
        }
        .footer-col ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .footer-col ul li {
            margin-bottom: 0.6rem;
        }
        .footer-col ul li a {
            text-decoration: none;
            color: #c4a0aa;
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }
        .footer-col ul li a:hover {
            color: #f48da8;
        }
        .footer-social {
            display: flex;
            gap: 0.75rem;
            margin-top: 0.25rem;
        }
        .footer-social a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(244,141,168,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f48da8;
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .footer-social a:hover {
            background: #d44d6e;
            color: #fff;
            transform: translateY(-2px);
        }
        .footer-bottom {
            max-width: 1280px;
            margin: 2.5rem auto 0;
            padding: 1.25rem 2rem;
            border-top: 1px solid rgba(244,141,168,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-bottom p {
            font-size: 0.78rem;
            color: #8a6a72;
            margin: 0;
        }
        .footer-payments {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        .footer-payments span {
            font-size: 0.72rem;
            font-weight: 700;
            color: #8a6a72;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        /* ===== SCROLL ANIMATIONS ===== */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .testimonials-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer-inner {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 768px) {
            .navbar-inner {
                padding: 0 1rem;
            }
            .nav-links,
            .nav-search {
                display: none;
            }
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            /* Mobile Nav Overlay */
            .mobile-nav {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 200;
                background: rgba(58,42,46,0.5);
                backdrop-filter: blur(4px);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            .mobile-nav.open {
                opacity: 1;
                pointer-events: all;
            }
            .mobile-nav-content {
                position: absolute;
                top: 0;
                right: 0;
                width: 280px;
                height: 100%;
                background: #fff;
                padding: 1.5rem;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                overflow-y: auto;
            }
            .mobile-nav.open .mobile-nav-content {
                transform: translateX(0);
            }
            .mobile-nav-close {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                margin-bottom: 1rem;
            }
            .mobile-nav-close button {
                width: 36px;
                height: 36px;
                border: none;
                background: transparent;
                cursor: pointer;
                color: #3a2a2e;
            }
            .mobile-nav-links {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .mobile-nav-links li {
                margin-bottom: 0;
            }
            .mobile-nav-links a {
                display: block;
                padding: 0.85rem 0;
                text-decoration: none;
                color: #3a2a2e;
                font-size: 1rem;
                font-weight: 500;
                border-bottom: 1px solid #fbd5df;
                transition: color 0.3s ease;
            }
            .mobile-nav-links a:hover,
            .mobile-nav-links a.active {
                color: #d44d6e;
            }
            .mobile-nav-search {
                margin-top: 1.25rem;
                position: relative;
            }
            .mobile-nav-search input {
                width: 100%;
                border: 1.5px solid #fbd5df;
                border-radius: 50px;
                padding: 10px 16px 10px 40px;
                font-size: 0.85rem;
                background: #fef5f7;
                outline: none;
                font-family: 'Inter', sans-serif;
            }
            .mobile-nav-search input:focus {
                border-color: #d44d6e;
                background: #fff;
            }
            .mobile-nav-search .search-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #d44d6e;
                width: 16px;
                height: 16px;
            }
            .hero {
                height: 400px;
            }
            .hero-content h1 {
                font-size: 2rem;
            }
            .hero-content p {
                font-size: 0.88rem;
            }
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }
            .testimonials-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            .footer-inner {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .footer-bottom {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
        @media (max-width: 480px) {
            .hero {
                height: 340px;
            }
            .hero-content {
                left: 5%;
                max-width: 90%;
            }
            .hero-content h1 {
                font-size: 1.7rem;
            }
            .section-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    {{-- NAVBAR --}}
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="/" class="nav-logo" id="nav-logo">Sweet Dreams</a>

            <ul class="nav-links" id="nav-links">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}" id="nav-home">Home</a></li>
                <li><a href="/katalog" class="{{ request()->is('katalog*') ? 'active' : '' }}" id="nav-katalog">Katalog</a></li>
                <li><a href="/tentang" class="{{ request()->is('tentang') ? 'active' : '' }}" id="nav-tentang">Tentang</a></li>
                <li><a href="/kontak" class="{{ request()->is('kontak') ? 'active' : '' }}" id="nav-kontak">Kontak</a></li>
            </ul>

            <div class="nav-search" id="nav-search">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" placeholder="Cari Produk" id="search-input">
            </div>

            <div class="nav-icons">
                <a href="/profil" class="nav-icon-btn {{ request()->is('profil*') ? 'active' : '' }}" id="btn-user" aria-label="Akun">
                    <i data-lucide="user" style="width:20px;height:20px;"></i>
                </a>
                <button class="nav-icon-btn" id="btn-wishlist" aria-label="Wishlist">
                    <i data-lucide="heart" style="width:20px;height:20px;"></i>
                </button>
                <a href="/keranjang" class="nav-icon-btn" id="btn-cart" aria-label="Keranjang">
                    <i data-lucide="shopping-bag" style="width:20px;height:20px;"></i>
                    <span class="badge">2</span>
                </a>
                <button class="mobile-menu-btn" id="mobile-menu-btn" aria-label="Menu">
                    <i data-lucide="menu" style="width:24px;height:24px;"></i>
                </button>
            </div>
        </div>
    </nav>

    {{-- MOBILE NAV --}}
    <div class="mobile-nav" id="mobile-nav">
        <div class="mobile-nav-content">
            <div class="mobile-nav-close">
                <button id="mobile-nav-close-btn" aria-label="Tutup Menu">
                    <i data-lucide="x" style="width:24px;height:24px;"></i>
                </button>
            </div>
            <ul class="mobile-nav-links">
                <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                <li><a href="/katalog" class="{{ request()->is('katalog*') ? 'active' : '' }}">Katalog</a></li>
                <li><a href="/keranjang" class="{{ request()->is('keranjang*') ? 'active' : '' }}">Keranjang</a></li>
                <li><a href="/profil" class="{{ request()->is('profil*') ? 'active' : '' }}">Akun Saya</a></li>
                <li><a href="/tentang" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a></li>
                <li><a href="/kontak" class="{{ request()->is('kontak') ? 'active' : '' }}">Kontak</a></li>
            </ul>
            <div class="mobile-nav-search">
                <i data-lucide="search" class="search-icon"></i>
                <input type="text" placeholder="Cari Produk">
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    <footer class="footer" id="footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <h3>Sweet Dreams</h3>
                <p>Menghadirkan kenyamanan tidur berbahan kemewahan sutra dan kelembutan renda. Dibuat dengan cinta untuk setiap wanita istimewa.</p>
            </div>
            <div class="footer-col">
                <h4>Tentang Kami</h4>
                <ul>
                    <li><a href="#">Visi & Misi</a></li>
                    <li><a href="#">Karier</a></li>
                    <li><a href="#">Koleksi Sutra</a></li>
                    <li><a href="#">Bahan Sutra Kami</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Layanan Pelanggan</h4>
                <ul>
                    <li><a href="#">Hubungi Kami</a></li>
                    <li><a href="#">FAQ & Pengiriman</a></li>
                    <li><a href="#">Panduan Ukuran Lengkap</a></li>
                    <li><a href="#">Kebijakan Pengembalian</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Ikuti Kami</h4>
                <div class="footer-social">
                    <a href="#" aria-label="Instagram"><i data-lucide="instagram" style="width:18px;height:18px;"></i></a>
                    <a href="#" aria-label="Facebook"><i data-lucide="facebook" style="width:18px;height:18px;"></i></a>
                    <a href="#" aria-label="Twitter"><i data-lucide="twitter" style="width:18px;height:18px;"></i></a>
                    <a href="#" aria-label="YouTube"><i data-lucide="youtube" style="width:18px;height:18px;"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 Sweet Dreams Lingerie. Hak Cipta Dilindungi Undang-Undang.</p>
            <div class="footer-payments">
                <span>VISA</span>
                <span>MASTERCARD</span>
                <span>BANK TRANSFER</span>
                <span>GOPAY</span>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Mobile Menu
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileNav = document.getElementById('mobile-nav');
        const mobileNavCloseBtn = document.getElementById('mobile-nav-close-btn');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                mobileNav.classList.add('open');
                document.body.style.overflow = 'hidden';
            });
        }
        if (mobileNavCloseBtn) {
            mobileNavCloseBtn.addEventListener('click', () => {
                mobileNav.classList.remove('open');
                document.body.style.overflow = '';
            });
        }
        if (mobileNav) {
            mobileNav.addEventListener('click', (e) => {
                if (e.target === mobileNav) {
                    mobileNav.classList.remove('open');
                    document.body.style.overflow = '';
                }
            });
        }

        // Scroll Reveal
        const revealElements = document.querySelectorAll('.reveal');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        revealElements.forEach(el => revealObserver.observe(el));

        // ===== GLOBAL SWEET DREAMS CART HELPER =====
        window.SweetDreamsCart = {
            STORAGE_KEY: 'sweetdreams_cart',
            CLEARED_KEY: 'sweetdreams_cart_cleared',

            getCart: function() {
                try {
                    const raw = localStorage.getItem(this.STORAGE_KEY);
                    if (raw) {
                        return JSON.parse(raw) || [];
                    }
                    if (localStorage.getItem(this.CLEARED_KEY) === 'true') {
                        return [];
                    }
                    return [];
                } catch (e) {
                    console.error('Error parsing cart:', e);
                    return [];
                }
            },

            saveCart: function(items) {
                try {
                    localStorage.setItem(this.STORAGE_KEY, JSON.stringify(items));
                    localStorage.removeItem(this.CLEARED_KEY);
                    this.updateNavbarBadge();
                    window.dispatchEvent(new CustomEvent('sweetdreams_cart_updated', { detail: items }));
                } catch (e) {
                    console.error('Error saving cart:', e);
                }
            },

            addItem: function(newItem) {
                let items = this.getCart();
                const itemId = newItem.id || `${newItem.slug || 'product'}-${newItem.color || 'default'}-${newItem.size || 'M'}`;
                newItem.id = itemId;
                newItem.qty = parseInt(newItem.qty) || 1;
                newItem.price = parseInt(newItem.price) || 0;

                const existingIndex = items.findIndex(i => i.id === itemId);
                if (existingIndex > -1) {
                    items[existingIndex].qty += newItem.qty;
                } else {
                    items.push(newItem);
                }

                this.saveCart(items);
                return items;
            },

            updateQty: function(itemId, newQty) {
                let items = this.getCart();
                newQty = parseInt(newQty);
                if (newQty <= 0) {
                    return this.removeItem(itemId);
                }
                const item = items.find(i => i.id === itemId);
                if (item) {
                    item.qty = newQty;
                    this.saveCart(items);
                }
                return items;
            },

            removeItem: function(itemId) {
                let items = this.getCart().filter(i => i.id !== itemId);
                this.saveCart(items);
                return items;
            },

            clearCart: function() {
                localStorage.setItem(this.STORAGE_KEY, JSON.stringify([]));
                localStorage.setItem(this.CLEARED_KEY, 'true');
                this.updateNavbarBadge();
                window.dispatchEvent(new CustomEvent('sweetdreams_cart_updated', { detail: [] }));
            },

            getTotalCount: function() {
                const items = this.getCart();
                return items.reduce((sum, item) => sum + (parseInt(item.qty) || 0), 0);
            },

            getTotalPrice: function() {
                const items = this.getCart();
                return items.reduce((sum, item) => sum + ((parseInt(item.price) || 0) * (parseInt(item.qty) || 0)), 0);
            },

            updateNavbarBadge: function() {
                const totalCount = this.getTotalCount();
                const badge = document.querySelector('.nav-icons .badge') || document.querySelector('#btn-cart .badge');
                if (badge) {
                    badge.textContent = totalCount;
                    badge.style.transform = 'scale(1.25)';
                    setTimeout(() => {
                        badge.style.transform = 'scale(1)';
                    }, 200);
                }
            }
        };

        // Initialize and listen for cart updates
        window.addEventListener('storage', () => window.SweetDreamsCart.updateNavbarBadge());
        window.addEventListener('sweetdreams_cart_updated', () => window.SweetDreamsCart.updateNavbarBadge());
        document.addEventListener('DOMContentLoaded', () => window.SweetDreamsCart.updateNavbarBadge());
        window.SweetDreamsCart.updateNavbarBadge();
    </script>
</body>
</html>
