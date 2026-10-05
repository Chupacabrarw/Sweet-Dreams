<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sweet Dreams - Koleksi sleepwear & lingerie premium untuk kenyamanan dan kepercayaan dirimu. Temukan baju tidur, kimono, lingerie terbaik.">
    <meta name="keywords" content="sleepwear, baju tidur, lingerie, kimono, pakaian dalam, sweet dreams">
    <title>@yield('title', 'Sweet Dreams - Premium Sleepwear & Lingerie')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300;1,9..40,400&family=DM+Serif+Display:ital@0;1&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ===== RESET & GLOBAL ===== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink:        #2a1f22;
            --ink-muted:  #6e5a60;
            --ink-faint:  #a8939a;
            --blush:      #c97a8c;
            --blush-dark: #a85e72;
            --blush-pale: #f2dce3;
            --bg:         #faf8f6;
            --bg-warm:    #f4ede9;
            --white:      #ffffff;
            --border:     rgba(180,140,150,0.18);
            --shadow-sm:  0 1px 4px rgba(42,31,34,0.06);
            --shadow-md:  0 4px 20px rgba(42,31,34,0.09);
            --shadow-lg:  0 12px 48px rgba(42,31,34,0.12);
            --radius:     12px;
            --radius-lg:  20px;
        }
        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            font-weight: 400;
            color: var(--ink);
            background: var(--bg);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        h1, h2, h3 {
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-weight: 500;
            line-height: 1.2;
        }
        img { display: block; max-width: 100%; }
        a { text-decoration: none; color: inherit; }
        button { font-family: inherit; cursor: pointer; border: none; background: none; }

        /* ===== NAVBAR ===== */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(250,248,246,0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            transition: border-color 0.3s ease;
        }
        .navbar.scrolled {
            border-bottom-color: rgba(180,140,150,0.30);
            box-shadow: 0 1px 16px rgba(42,31,34,0.06);
        }
        .navbar-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            gap: 1.5rem;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            flex-shrink: 0;
        }
        .nav-logo img {
            height: 50px;
            width: auto;
            object-fit: contain;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2.25rem;
            list-style: none;
        }
        .nav-links a {
            color: var(--ink-muted);
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.25s;
            position: relative;
        }
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 1px;
            background: var(--blush);
            transition: width 0.3s ease;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--ink);
        }
        .nav-links a:hover::after, .nav-links a.active::after {
            width: 100%;
        }
        .nav-search {
            position: relative;
            flex: 1;
            max-width: 200px;
        }
        .nav-search input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 7px 12px 7px 34px;
            font-size: 0.82rem;
            font-family: 'DM Sans', sans-serif;
            background: var(--white);
            color: var(--ink);
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .nav-search input::placeholder { color: var(--ink-faint); }
        .nav-search input:focus {
            border-color: var(--blush);
            box-shadow: 0 0 0 3px rgba(201,122,140,0.12);
        }
        .nav-search .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink-faint);
            width: 15px;
            height: 15px;
            pointer-events: none;
        }
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-shrink: 0;
        }
        .nav-icon-btn {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-muted);
            transition: background 0.2s, color 0.2s;
            position: relative;
        }
        .nav-icon-btn:hover {
            background: var(--bg-warm);
            color: var(--ink);
        }
        .nav-icon-btn .badge {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 15px;
            height: 15px;
            background: var(--blush);
            color: #fff;
            border-radius: 50%;
            font-size: 0.58rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid var(--bg);
        }

        /* User Dropdown */
        .nav-user-dropdown-wrapper {
            position: relative;
        }
        .nav-user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 260px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 12px 36px rgba(42,31,34,0.15);
            border: 1px solid rgba(180,140,150,0.22);
            padding: 1rem 0 0.5rem 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
            z-index: 1000;
        }
        .nav-user-dropdown-wrapper:hover .nav-user-dropdown,
        .nav-user-dropdown-wrapper:focus-within .nav-user-dropdown,
        .nav-user-dropdown.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .user-dropdown-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0 1.15rem 0.85rem 1.15rem;
            border-bottom: 1px solid rgba(180,140,150,0.15);
        }
        .user-dropdown-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--blush);
            background: #fff8fa;
            flex-shrink: 0;
        }
        .user-dropdown-info {
            overflow: hidden;
            text-align: left;
        }
        .user-dropdown-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.25;
        }
        .user-dropdown-email {
            font-size: 0.75rem;
            color: var(--ink-faint);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-dropdown-menu {
            list-style: none;
            padding: 0.5rem 0.5rem 0 0.5rem;
            margin: 0;
        }
        .user-dropdown-menu li a,
        .user-dropdown-menu li button {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: 10px;
            font-size: 0.85rem;
            color: var(--ink-muted);
            font-weight: 500;
            text-align: left;
            transition: all 0.2s;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
        }
        .user-dropdown-menu li a:hover,
        .user-dropdown-menu li button:hover {
            background: #fff3f6;
            color: var(--blush-dark);
        }
        .user-dropdown-menu li a svg,
        .user-dropdown-menu li button svg {
            width: 17px;
            height: 17px;
            color: var(--ink-faint);
            transition: color 0.2s;
        }
        .user-dropdown-menu li a:hover svg,
        .user-dropdown-menu li button:hover svg {
            color: var(--blush);
        }
        .user-dropdown-divider {
            height: 1px;
            background: rgba(180,140,150,0.15);
            margin: 0.4rem 0.5rem;
        }
        .user-dropdown-menu li.logout-item a,
        .user-dropdown-menu li.logout-item button {
            color: #f43f5e;
            font-weight: 600;
        }
        .user-dropdown-menu li.logout-item a:hover,
        .user-dropdown-menu li.logout-item button:hover {
            background: #fff1f2;
            color: #e11d48;
        }
        .user-dropdown-menu li.logout-item svg {
            color: #f43f5e !important;
        }
        .mobile-menu-btn {
            display: none;
            width: 38px;
            height: 38px;
            align-items: center;
            justify-content: center;
            color: var(--ink);
            border-radius: 8px;
        }
        .mobile-nav { display: none; }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            width: 100%;
            height: 88vh;
            min-height: 540px;
            max-height: 800px;
            overflow: hidden;
        }
        .hero-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 15%;
        }
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(100deg,
                rgba(30,16,20,0.72) 0%,
                rgba(30,16,20,0.42) 48%,
                rgba(30,16,20,0.08) 100%
            );
        }
        .hero-content {
            position: absolute;
            top: 50%;
            left: 7%;
            transform: translateY(-50%);
            z-index: 2;
            max-width: 520px;
        }
        .hero-eyebrow {
            display: inline-block;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.65);
            margin-bottom: 1.1rem;
            opacity: 0;
            transform: translateY(12px);
            animation: fadeUp 0.7s ease 0.1s forwards;
        }
        .hero-content h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3.8rem, 6.5vw, 5.5rem);
            font-weight: 400;
            font-style: italic;
            color: #fff;
            line-height: 1.08;
            margin: 0 0 1.5rem 0;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeUp 0.8s ease 0.2s forwards;
        }
        .hero-content p {
            font-size: 1.2rem;
            font-weight: 300;
            color: rgba(255,255,255,0.80);
            line-height: 1.75;
            margin: 0 0 2rem 0;
            max-width: 480px;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeUp 0.8s ease 0.35s forwards;
        }
        .btn-shop-now {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: var(--white);
            color: var(--ink);
            padding: 13px 28px;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(16px);
            animation: fadeUp 0.8s ease 0.5s forwards;
        }
        .btn-shop-now:hover {
            background: var(--blush);
            color: #fff;
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 28px rgba(201,122,140,0.35);
        }
        .btn-shop-now svg { transition: transform 0.3s ease; }
        .btn-shop-now:hover svg { transform: translateX(4px); }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== SECTIONS ===== */
        .section {
            max-width: 1320px;
            margin: 0 auto;
            padding: 5rem 2rem;
        }
        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 2.5rem;
        }
        .section-eyebrow {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--blush);
            margin-bottom: 0.5rem;
        }
        .section-header h2 {
            font-size: clamp(2.2rem, 3.5vw, 3.2rem);
            font-weight: 400;
            color: var(--ink);
        }
        .section-header p {
            font-size: 0.88rem;
            color: var(--ink-muted);
            margin-top: 0.35rem;
            font-weight: 300;
        }
        .section-header .view-all {
            color: var(--ink-muted);
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1px;
            transition: color 0.25s, border-color 0.25s;
            white-space: nowrap;
        }
        .section-header .view-all:hover {
            color: var(--blush);
            border-color: var(--blush);
        }

        /* ===== CATEGORIES ===== */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.25rem;
        }
        .category-card {
            background: var(--white);
            border-radius: var(--radius);
            overflow: hidden;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            cursor: pointer;
            color: inherit;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }
        .category-card-img {
            width: 100%;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: var(--bg-warm);
        }
        .category-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s ease;
        }
        .category-card:hover .category-card-img img { transform: scale(1.06); }
        .category-card-body {
            padding: 1rem 1.1rem 1.2rem;
        }
        .category-card-body h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 0.15rem;
        }
        .category-card-body span {
            font-size: 0.75rem;
            color: var(--ink-faint);
            letter-spacing: 0.04em;
        }

        /* ===== PRODUCTS ===== */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        .product-card {
            background: transparent;
            border-radius: var(--radius);
            overflow: hidden;
            transition: transform 0.35s ease;
            cursor: pointer;
            color: inherit;
            position: relative;
        }
        .product-card:hover { transform: translateY(-4px); }
        .product-card-img {
            width: 100%;
            aspect-ratio: 3/4;
            overflow: hidden;
            background: var(--bg-warm);
            border-radius: var(--radius);
            position: relative;
        }
        .product-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.55s ease;
        }
        .product-card:hover .product-card-img img { transform: scale(1.05); }
        .product-card-wishlist {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-muted);
            opacity: 0;
            transform: scale(0.85);
            transition: all 0.3s ease;
        }
        .product-card:hover .product-card-wishlist { opacity: 1; transform: scale(1); }
        .product-card-wishlist:hover { background: var(--blush); color: #fff; }
        .product-card-body { padding: 0.9rem 0 0; }
        .product-card-body h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.88rem;
            font-weight: 400;
            color: var(--ink);
            margin-bottom: 0.35rem;
            letter-spacing: 0.01em;
        }
        .product-card-body .price {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--ink-muted);
        }

        /* ===== TESTIMONIALS ===== */
        .testimonials-section {
            background: var(--bg-warm);
            padding: 5rem 0;
        }
        .testimonials-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .testimonials-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 3rem;
        }
        .testimonials-title {
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-weight: 400;
            font-style: italic;
            color: var(--ink);
        }
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        .testimonial-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            padding: 2.25rem 2rem;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        .testimonial-stars {
            display: flex;
            gap: 3px;
            margin-bottom: 1rem;
            color: #dba56a;
        }
        .testimonial-card p {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.08rem;
            font-weight: 400;
            font-style: italic;
            line-height: 1.7;
            color: var(--ink);
            margin: 0 0 1.5rem 0;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }
        .testimonial-author .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--blush-pale);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blush-dark);
            font-weight: 600;
            font-size: 0.78rem;
            font-family: 'DM Sans', sans-serif;
        }
        .testimonial-author .info h4 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--ink);
        }
        .testimonial-author .info span {
            font-size: 0.75rem;
            color: var(--ink-faint);
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--ink);
            color: rgba(255,255,255,0.6);
            padding: 4rem 0 0;
        }
        .footer-inner {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
        }
        .footer-brand h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 400;
            font-style: italic;
            color: var(--white);
            margin-bottom: 0.9rem;
        }
        .footer-brand p {
            font-size: 0.83rem;
            line-height: 1.8;
            max-width: 300px;
            font-weight: 300;
        }
        .footer-col h4 {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--white);
            margin-bottom: 1.25rem;
        }
        .footer-col ul {
            list-style: none;
        }
        .footer-col ul li { margin-bottom: 0.65rem; }
        .footer-col ul li a {
            font-size: 0.84rem;
            font-weight: 300;
            transition: color 0.25s;
        }
        .footer-col ul li a:hover { color: var(--white); }
        .footer-social {
            display: flex;
            gap: 0.6rem;
            margin-top: 0.25rem;
        }
        .footer-social a {
            width: 34px;
            height: 34px;
            border-radius: 6px;
            background: rgba(255,255,255,0.07);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
            transition: all 0.25s;
        }
        .footer-social a:hover {
            background: var(--blush);
            color: #fff;
        }
        .footer-bottom {
            max-width: 1320px;
            margin: 3.5rem auto 0;
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-bottom p { font-size: 0.78rem; font-weight: 300; }
        .footer-payments {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .footer-payments span {
            font-size: 0.68rem;
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            background: rgba(255,255,255,0.06);
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.10);
        }

        /* ===== SCROLL ANIMATIONS ===== */
        .reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.08s; }
        .reveal-delay-2 { transition-delay: 0.16s; }
        .reveal-delay-3 { transition-delay: 0.24s; }
        .reveal-delay-4 { transition-delay: 0.32s; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .categories-grid { grid-template-columns: repeat(2, 1fr); }
            .products-grid { grid-template-columns: repeat(2, 1fr); }
            .testimonials-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-inner { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .navbar-inner { padding: 0 1rem; height: 58px; }
            .nav-links, .nav-search { display: none; }
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .mobile-nav {
                display: block;
                position: fixed;
                inset: 0;
                z-index: 200;
                background: rgba(42,31,34,0.5);
                backdrop-filter: blur(6px);
                opacity: 0;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            .mobile-nav.open { opacity: 1; pointer-events: all; }
            .mobile-nav-content {
                position: absolute;
                top: 0; right: 0;
                width: 280px; height: 100%;
                background: var(--white);
                padding: 1.5rem;
                transform: translateX(100%);
                transition: transform 0.3s ease;
                overflow-y: auto;
            }
            .mobile-nav.open .mobile-nav-content { transform: translateX(0); }
            .mobile-nav-close {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 1.25rem;
            }
            .mobile-nav-close button {
                width: 36px; height: 36px;
                color: var(--ink);
            }
            .mobile-nav-links { list-style: none; }
            .mobile-nav-links li { }
            .mobile-nav-links a {
                display: block;
                padding: 0.9rem 0;
                font-size: 0.95rem;
                font-weight: 500;
                border-bottom: 1px solid var(--border);
                color: var(--ink-muted);
                transition: color 0.25s;
            }
            .mobile-nav-links a:hover, .mobile-nav-links a.active { color: var(--ink); }
            .mobile-nav-search {
                margin-top: 1.5rem;
                position: relative;
            }
            .mobile-nav-search input {
                width: 100%;
                border: 1px solid var(--border);
                border-radius: 8px;
                padding: 10px 14px 10px 36px;
                font-size: 0.85rem;
                background: var(--bg);
                outline: none;
                font-family: 'DM Sans', sans-serif;
                color: var(--ink);
            }
            .mobile-nav-search input:focus { border-color: var(--blush); }
            .mobile-nav-search .search-icon {
                position: absolute;
                left: 12px; top: 50%;
                transform: translateY(-50%);
                color: var(--ink-faint);
                width: 15px; height: 15px;
            }
            .hero { height: 75vh; min-height: 420px; }
            .hero-content h1 { font-size: 3rem; }
            .hero-content { max-width: 90%; }
            .section { padding: 3.5rem 1.25rem; }
            .section-header { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .categories-grid { grid-template-columns: repeat(2, 1fr); gap: 0.9rem; }
            .products-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
            .testimonials-grid { grid-template-columns: 1fr; }
            .footer-inner { grid-template-columns: 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; gap: 1rem; text-align: center; }
        }
        @media (max-width: 480px) {
            .hero { height: 70vh; min-height: 380px; }
            .hero-content h1 { font-size: 2.5rem; }
            .hero-content p { font-size: 1rem; }
            .section-header h2 { font-size: 2rem; }
        }
    </style>

</head>
<body>
    {{-- NAVBAR --}}
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="/" class="nav-logo" id="nav-logo"><img src="/images/logo.png" alt="Sweet Dream Logo"></a>

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
                @auth
                <div class="nav-user-dropdown-wrapper" id="user-dropdown-wrapper">
                    <a href="/profil" class="nav-icon-btn {{ request()->is('profil*') ? 'active' : '' }}" id="btn-user" title="Akun: {{ auth()->user()->name }}">
                        <img src="{{ auth()->user()->avatar_url }}"
                             alt="{{ auth()->user()->name }}"
                             style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid #f48da8;display:block;">
                    </a>
                    <div class="nav-user-dropdown" id="nav-user-dropdown">
                        <div class="user-dropdown-header">
                            <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-dropdown-avatar">
                            <div class="user-dropdown-info">
                                <div class="user-dropdown-name">{{ auth()->user()->name }}</div>
                                <div class="user-dropdown-email">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <ul class="user-dropdown-menu">
                            <li>
                                <a href="/profil">
                                    <i data-lucide="user"></i>
                                    <span>Profil Saya</span>
                                </a>
                            </li>
                            <li>
                                <a href="/profil?tab=pesanan">
                                    <i data-lucide="package"></i>
                                    <span>Pesanan Saya</span>
                                </a>
                            </li>
                            <li>
                                <a href="/profil?tab=alamat">
                                    <i data-lucide="map-pin"></i>
                                    <span>Daftar Alamat</span>
                                </a>
                            </li>
                            @if(auth()->user()->is_admin || auth()->user()->role === 'admin')
                            <li>
                                <a href="/admin/dashboard" style="color: #6366f1;">
                                    <i data-lucide="shield" style="color: #6366f1 !important;"></i>
                                    <span>Admin Panel</span>
                                </a>
                            </li>
                            @endif
                            <li class="user-dropdown-divider"></li>
                            <li class="logout-item">
                                <a href="/logout" onclick="try{localStorage.removeItem('sweetdreams_auth_user');}catch(e){}">
                                    <i data-lucide="log-out"></i>
                                    <span>Keluar (Logout)</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                @else
                <a href="/login" class="nav-icon-btn {{ request()->is('login*') ? 'active' : '' }}" id="btn-user" aria-label="Akun" title="Masuk ke Akun Anda">
                    <i data-lucide="user" style="width:20px;height:20px;"></i>
                </a>
                @endauth    
                <a href="/wishlist" class="nav-icon-btn" id="btn-wishlist" aria-label="Wishlist">
                    <i data-lucide="heart" style="width:20px;height:20px;"></i>
                    <span class="badge">{{ auth()->check() ? auth()->user()->wishlists()->count() : 0 }}</span>
                </a>
                <a href="/keranjang" class="nav-icon-btn" id="btn-cart" aria-label="Keranjang">
                    <i data-lucide="shopping-bag" style="width:20px;height:20px;"></i>
                    <span class="badge">{{ auth()->check() ? auth()->user()->cartItems()->sum('quantity') : 0 }}</span>
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
                <li><a href="/wishlist" class="{{ request()->is('wishlist*') ? 'active' : '' }}">Wishlist</a></li>
                @auth
                    <li><a href="/profil" class="{{ request()->is('profil*') ? 'active' : '' }}">Akun Saya ({{ auth()->user()->name }})</a></li>
                    @if(auth()->user()->is_admin || auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard" style="color: #6366f1;">Admin Panel</a></li>
                    @endif
                    <li><a href="/logout" onclick="try{localStorage.removeItem('sweetdreams_auth_user');}catch(e){}" style="color: #f43f5e; font-weight: 600;">Keluar (Logout)</a></li>
                @else
                    <li><a href="/login" class="{{ request()->is('login*') ? 'active' : '' }}">Masuk / Daftar Akun</a></li>
                @endauth
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
                <h3><em>Sweet Dreams</em></h3>
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
        // Global HTML escape helper
        window.escapeHtml = function(str) {
            if (str === null || str === undefined) return '';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };
        const escapeHtml = window.escapeHtml;

        // Initialize Lucide Icons
        lucide.createIcons();

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });

        // Nav Search on Enter
        const globalSearchInput = document.getElementById('search-input');
        const globalMobileSearch = document.querySelector('.mobile-nav-search input');
        function handleGlobalSearch(e) {
            if (e.key === 'Enter') {
                const query = e.target.value.trim();
                if (!window.location.pathname.startsWith('/katalog')) {
                    window.location.href = '/katalog?q=' + encodeURIComponent(query);
                }
            }
        }
        if (globalSearchInput) globalSearchInput.addEventListener('keydown', handleGlobalSearch);
        if (globalMobileSearch) globalMobileSearch.addEventListener('keydown', handleGlobalSearch);

        // User Dropdown Click & Touch Toggle
        const userDropdownWrapper = document.getElementById('user-dropdown-wrapper');
        const navUserDropdown = document.getElementById('nav-user-dropdown');
        const btnUser = document.getElementById('btn-user');
        if (userDropdownWrapper && navUserDropdown && btnUser) {
            btnUser.addEventListener('click', (e) => {
                if (window.innerWidth <= 1024 || e.pointerType === 'touch') {
                    e.preventDefault();
                    navUserDropdown.classList.toggle('open');
                }
            });
            document.addEventListener('click', (e) => {
                if (!userDropdownWrapper.contains(e.target)) {
                    navUserDropdown.classList.remove('open');
                }
            });
        }

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

    

        // ===== GLOBAL SWEET DREAMS AUTH HELPER =====
        window.SweetDreamsAuth = {
            USER_KEY: 'sweetdreams_auth_user',
            DB_KEY: 'sweetdreams_registered_users',

            getRegisteredUsers: function() {
                try {
                    const raw = localStorage.getItem(this.DB_KEY);
                    return raw ? JSON.parse(raw) : [];
                } catch(e) {
                    return [];
                }
            },

            saveRegisteredUsers: function(users) {
                try {
                    localStorage.setItem(this.DB_KEY, JSON.stringify(users));
                } catch(e) {
                    console.error('Error saving registered users:', e);
                }
            },

            getCurrentUser: function() {
                try {
                    const raw = localStorage.getItem(this.USER_KEY);
                    return raw ? JSON.parse(raw) : null;
                } catch(e) {
                    return null;
                }
            },

            saveCurrentUser: function(user) {
                try {
                    localStorage.setItem(this.USER_KEY, JSON.stringify(user));
                    // Also sync back to registered users list if this is a registered user
                    if (user && user.email) {
                        const users = this.getRegisteredUsers();
                        const idx = users.findIndex(u => 
                            u.email.toLowerCase() === user.email.toLowerCase() || 
                            (user.username && u.username && u.username.toLowerCase() === user.username.toLowerCase())
                        );
                        if (idx > -1) {
                            users[idx] = Object.assign({}, users[idx], user);
                            this.saveRegisteredUsers(users);
                        }
                    }
                    window.dispatchEvent(new CustomEvent('sweetdreams_user_updated', { detail: user }));
                } catch(e) {
                    console.error('Error saving current user:', e);
                }
            },

            updateUserProfile: function(newData) {
                let user = this.getCurrentUser();
                if (!user) {
                    user = {
                        name: 'Alya Putri',
                        email: 'alya.putri@email.com',
                        username: 'alya',
                        phone: '0812 3456 7890',
                        birthdate: '17 Mei 1997',
                        city: 'Jakarta Selatan',
                        role: 'customer',
                        avatar: 'images/avatars/avatar-1.svg',
                        addresses: this.getUserAddresses()
                    };
                }
                user = Object.assign({}, user, newData);
                this.saveCurrentUser(user);
                return user;
            },

            getUserAddresses: function() {
                const user = this.getCurrentUser();
                if (!user) {
                    return [
                        {
                            id: 'addr-default-1',
                            label: 'Alamat Utama',
                            name: 'Alya Putri',
                            phone: '0812 3456 7890',
                            address: 'Jl. Kemang Raya No. 45, RT.2/RW.2, Bangka, Kec. Mampang Prapatan',
                            city: 'Jakarta Selatan',
                            province: 'DKI Jakarta',
                            postal_code: '12730',
                            is_primary: true
                        },
                        {
                            id: 'addr-default-2',
                            label: 'Kantor',
                            name: 'Alya Putri',
                            phone: '0812 3456 7890',
                            address: 'Gedung Menara Sudirman Lt. 14, Jl. Jend. Sudirman Kav. 60',
                            city: 'Jakarta Selatan',
                            province: 'DKI Jakarta',
                            postal_code: '12190',
                            is_primary: false
                        }
                    ];
                }

                if (!Array.isArray(user.addresses) || user.addresses.length === 0) {
                    // Create an initial default address matching this user's profile
                    const initialAddress = {
                        id: 'addr-' + Date.now(),
                        label: 'Alamat Utama',
                        name: user.name || 'Pelanggan Sweet Dreams',
                        phone: user.phone || '081234567890',
                        address: 'Jl. Kemang Raya No. 45, RT.2/RW.2, Bangka, Kec. Mampang Prapatan',
                        city: user.city || 'Jakarta Selatan',
                        province: 'DKI Jakarta',
                        postal_code: '12730',
                        is_primary: true
                    };
                    user.addresses = [initialAddress];
                    this.saveCurrentUser(user);
                    return user.addresses;
                }

                return user.addresses;
            },

            saveUserAddress: function(addrData, addrId) {
                let user = this.getCurrentUser();
                if (!user) {
                    user = {
                        name: 'Pelanggan Sweet Dreams',
                        email: 'customer@sweetdreams.com',
                        phone: '081234567890',
                        role: 'customer',
                        avatar: 'images/alya-avatar.jpg',
                        addresses: []
                    };
                }

                let addresses = Array.isArray(user.addresses) ? [...user.addresses] : [];

                if (addrData.is_primary) {
                    addresses.forEach(a => a.is_primary = false);
                }

                if (addrId) {
                    const idx = addresses.findIndex(a => a.id === addrId);
                    if (idx > -1) {
                        addresses[idx] = Object.assign({}, addresses[idx], addrData, { id: addrId });
                    } else {
                        addresses.push(Object.assign({}, addrData, { id: addrId }));
                    }
                } else {
                    const newId = 'addr-' + Date.now();
                    const isFirst = addresses.length === 0;
                    addresses.push(Object.assign({}, addrData, { 
                        id: newId, 
                        is_primary: addrData.is_primary || isFirst 
                    }));
                }

                // Ensure at least one primary exists
                if (!addresses.some(a => a.is_primary) && addresses.length > 0) {
                    addresses[0].is_primary = true;
                }

                user.addresses = addresses;
                this.saveCurrentUser(user);
                return addresses;
            },

            deleteUserAddress: function(addrId) {
                const user = this.getCurrentUser();
                if (!user || !Array.isArray(user.addresses)) return [];

                let addresses = user.addresses.filter(a => a.id !== addrId);
                if (!addresses.some(a => a.is_primary) && addresses.length > 0) {
                    addresses[0].is_primary = true;
                }

                user.addresses = addresses;
                this.saveCurrentUser(user);
                return addresses;
            },

            setPrimaryAddress: function(addrId) {
                const user = this.getCurrentUser();
                if (!user || !Array.isArray(user.addresses)) return [];

                user.addresses.forEach(a => {
                    a.is_primary = (a.id === addrId);
                });

                this.saveCurrentUser(user);
                return user.addresses;
            },

            logout: function() {
                try {
                    localStorage.removeItem(this.USER_KEY);
                    localStorage.removeItem('sweetdreams_auth_user');
                } catch(e) {}
                window.location.href = '/logout';
            }
        };

        
    </script>

    {{-- CHATBOT WIDGET --}}
    <div id="chatbot-widget" class="chatbot-widget">
        <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Buka Chatbot">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </button>

        <div id="chatbot-window" class="chatbot-window">
            <div class="chatbot-header">
                <div class="chatbot-header-info">
                    <div class="chatbot-avatar">D</div>
                    <div>
                        <h4 class="chatbot-title">Dreamy</h4>
                        <p class="chatbot-subtitle">Asisten Virtual Sweet Dreams</p>
                    </div>
                </div>
                <button id="chatbot-close" class="chatbot-close" aria-label="Tutup Chat">&times;</button>
            </div>
            
            <div id="chatbot-messages" class="chatbot-messages">
                <div class="chatbot-message bot">
                    <div class="chatbot-bubble">Halo! Saya Dreamy, asisten virtual Sweet Dreams. Ada yang bisa saya bantu hari ini? 😊</div>
                </div>
            </div>

            <div class="chatbot-input-area">
                <form id="chatbot-form" class="chatbot-form">
                    <input type="text" id="chatbot-input" placeholder="Ketik pesan Anda..." autocomplete="off">
                    <button type="submit" id="chatbot-send" aria-label="Kirim">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .chatbot-widget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            font-family: 'Inter', sans-serif;
        }
        .chatbot-toggle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--blush);
            color: white;
            border: none;
            box-shadow: 0 4px 16px rgba(201,122,140, 0.35);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .chatbot-toggle:hover {
            transform: scale(1.1);
        }
        .chatbot-window {
            position: absolute;
            bottom: 76px;
            right: 0;
            width: 350px;
            height: 500px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: bottom right;
            border: 1px solid #f4dbe2;
        }
        .chatbot-window.open {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0) scale(1);
        }
        .chatbot-header {
            background: var(--ink);
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }
        .chatbot-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .chatbot-avatar {
            width: 36px;
            height: 36px;
            background: #d44d6e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .chatbot-title {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 600;
            font-family: 'Playfair Display', serif;
        }
        .chatbot-subtitle {
            margin: 0;
            font-size: 0.75rem;
            color: #d4b8c0;
        }
        .chatbot-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        .chatbot-close:hover {
            opacity: 1;
        }
        .chatbot-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: #fdfdfd;
        }
        .chatbot-message {
            display: flex;
            max-width: 85%;
        }
        .chatbot-message.user {
            align-self: flex-end;
        }
        .chatbot-message.bot {
            align-self: flex-start;
        }
        .chatbot-bubble {
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 0.9rem;
            line-height: 1.5;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            word-break: break-word;
        }
        .chatbot-message.user .chatbot-bubble {
            background: var(--blush);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .chatbot-message.bot .chatbot-bubble {
            background: #fdf7f8;
            color: #3a2a2e;
            border: 1px solid #f4dbe2;
            border-bottom-left-radius: 4px;
        }
        .chatbot-input-area {
            padding: 16px;
            background: white;
            border-top: 1px solid #f8eff1;
        }
        .chatbot-form {
            display: flex;
            gap: 8px;
        }
        #chatbot-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e8d0d6;
            border-radius: 24px;
            outline: none;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s;
        }
        #chatbot-input:focus {
            border-color: #d44d6e;
        }
        #chatbot-send {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #d44d6e;
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        #chatbot-send:hover {
            background: var(--blush-dark);
        }
        #chatbot-send:disabled {
            background: #e8d0d6;
            cursor: not-allowed;
        }
        .chatbot-typing {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
            align-items: center;
            height: 20px;
        }
        .chatbot-typing-dot {
            width: 6px;
            height: 6px;
            background: #c4a0aa;
            border-radius: 50%;
            animation: chatbotTyping 1.4s infinite ease-in-out both;
        }
        .chatbot-typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .chatbot-typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes chatbotTyping {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
        @media (max-width: 480px) {
            .chatbot-window {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 100%;
                border-radius: 0;
                transform: translateY(100%);
            }
            .chatbot-window.open {
                transform: translateY(0);
            }
            .chatbot-toggle {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('chatbot-toggle');
            const closeBtn = document.getElementById('chatbot-close');
            const chatWindow = document.getElementById('chatbot-window');
            const messagesContainer = document.getElementById('chatbot-messages');
            const chatForm = document.getElementById('chatbot-form');
            const chatInput = document.getElementById('chatbot-input');
            const sendBtn = document.getElementById('chatbot-send');

            let chatHistory = [];

            // Toggle chat window
            toggleBtn.addEventListener('click', () => {
                chatWindow.classList.toggle('open');
                if (chatWindow.classList.contains('open')) {
                    setTimeout(() => chatInput.focus(), 300);
                }
            });

            closeBtn.addEventListener('click', () => {
                chatWindow.classList.remove('open');
            });

            // Handle form submission
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const message = chatInput.value.trim();
                if (!message) return;

                // Add user message to UI
                appendMessage(message, 'user');
                chatInput.value = '';
                chatInput.disabled = true;
                sendBtn.disabled = true;

                // Show typing indicator
                const typingIndicator = showTypingIndicator();

                try {
                    const response = await fetch('/api/chatbot', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            message: message,
                            history: chatHistory
                        })
                    });

                    const data = await response.json();
                    
                    // Remove typing indicator
                    typingIndicator.remove();

                    if (response.ok) {
                        appendMessage(data.reply, 'bot');
                        
                        // Update history
                        chatHistory.push({ role: 'user', text: message });
                        chatHistory.push({ role: 'model', text: data.reply });
                    } else {
                        appendMessage(data.reply || 'Maaf, terjadi kesalahan.', 'bot');
                    }

                } catch (error) {
                    console.error('Chat error:', error);
                    typingIndicator.remove();
                    appendMessage('Maaf, koneksi terputus. Silakan coba lagi.', 'bot');
                } finally {
                    chatInput.disabled = false;
                    sendBtn.disabled = false;
                    chatInput.focus();
                }
            });

            function appendMessage(text, sender) {
                const messageDiv = document.createElement('div');
                messageDiv.className = `chatbot-message ${sender}`;
                
                // Convert markdown-like bold (**) and newlines to HTML for simple formatting
                const formattedText = text
                    .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                    .replace(/\n/g, '<br>');

                messageDiv.innerHTML = `<div class="chatbot-bubble">${formattedText}</div>`;
                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            function showTypingIndicator() {
                const indicatorDiv = document.createElement('div');
                indicatorDiv.className = 'chatbot-message bot';
                indicatorDiv.innerHTML = `
                    <div class="chatbot-bubble chatbot-typing">
                        <div class="chatbot-typing-dot"></div>
                        <div class="chatbot-typing-dot"></div>
                        <div class="chatbot-typing-dot"></div>
                    </div>
                `;
                messagesContainer.appendChild(indicatorDiv);
                scrollToBottom();
                return indicatorDiv;
            }

            function scrollToBottom() {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>
</body>
</html>
