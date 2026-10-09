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
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@300;400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Nunito+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @vite('resources/css/layouts/app.css')
    @stack('page-styles')
    @vite('resources/css/layouts/app-chatbot.css')

    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

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
                    <li><a href="{{ route('tentang') }}">Visi &amp; Misi</a></li>
                    <li><a href="{{ route('kontak') }}">Karier &amp; Kerja Sama</a></li>
                    <li><a href="{{ route('katalog') }}">Koleksi Produk</a></li>
                    <li><a href="{{ route('tentang') }}">Bahan &amp; Cerita Kami</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Layanan Pelanggan</h4>
                <ul>
                    <li><a href="{{ route('kontak') }}">Hubungi Kami</a></li>
                    <li><a href="{{ route('kontak') }}#faq-pengiriman">FAQ &amp; Pengiriman</a></li>
                    <li><a href="{{ route('kontak') }}#faq-panduan-ukuran">Panduan Ukuran</a></li>
                    <li><a href="{{ route('kontak') }}#faq-penukaran">Kebijakan Penukaran</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Terhubung dengan Kami</h4>
                <div class="footer-social">
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" aria-label="Hubungi Sweet Dreams lewat WhatsApp"><i data-lucide="message-circle" style="width:18px;height:18px;"></i></a>
                    <a href="mailto:halo@sweetdream.id" aria-label="Kirim email ke Sweet Dreams"><i data-lucide="mail" style="width:18px;height:18px;"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Sweet Dreams Lingerie. Hak Cipta Dilindungi Undang-Undang.</p>
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
            <img src="{{ asset('images/avatars/cht-bot.png') }}" alt="Dreamy AI">
        </button>

        <div id="chatbot-window" class="chatbot-window">
            <div class="chatbot-header">
                <div class="chatbot-header-info">
                  <div class="chatbot-avatar"><img src="{{ asset('images/avatars/cht-bot.png') }}" alt="Dreamy"></div>
                    <div>
                        <h4 class="chatbot-title">Dreamy</h4>
                        <p class="chatbot-subtitle">Asisten Virtual Sweet Dreams</p>
                    </div>
                </div>
                <button id="chatbot-close" class="chatbot-close" aria-label="Tutup Chat">&times;</button>
            </div>

            <div id="chatbot-messages" class="chatbot-messages">
                                <div class="chatbot-message bot">
                    <div class="chatbot-msg-avatar"><img src="{{ asset('images/avatars/cht-bot.png') }}" alt="Dreamy"></div>
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
            const CHATBOT_DEBUG = @json(config('app.debug'));

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

                    if (response.ok && !data.error) {
                        appendMessage(data.reply, 'bot');

                        // Update history
                        chatHistory.push({ role: 'user', text: message });
                        chatHistory.push({ role: 'model', text: data.reply });
                    } else {
                        appendErrorMessage(data.reply || 'Maaf, terjadi kesalahan.', data);
                    }

                } catch (error) {
                    console.error('Chat error:', error);
                    typingIndicator.remove();
                    appendErrorMessage('Maaf, koneksi terputus. Silakan coba lagi.', { code: 'NETWORK', debug: CHATBOT_DEBUG ? { message: String(error && error.message ? error.message : error) } : null });
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

                const avatar = sender === 'bot'
    ? `<div class="chatbot-msg-avatar"><img src="/images/avatars/cht-bot.png" alt="Dreamy"></div>`
    : '';
messageDiv.innerHTML = `${avatar}<div class="chatbot-bubble">${formattedText}</div>`;
                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            function appendErrorMessage(text, meta) {
                const messageDiv = document.createElement('div');
                messageDiv.className = 'chatbot-message bot chatbot-message-error';

                const safeText = window.escapeHtml(text).replace(/\n/g, '<br>');
                const code = meta && meta.code ? window.escapeHtml(String(meta.code)) : 'ERROR';
                let debugHtml = '';
                if (CHATBOT_DEBUG && meta && meta.debug) {
                    const raw = typeof meta.debug === 'string' ? meta.debug : JSON.stringify(meta.debug, null, 2);
                    debugHtml = `<pre class="chatbot-error-debug">${window.escapeHtml(raw)}</pre>`;
                }
                messageDiv.innerHTML = `
                    <div class="chatbot-msg-avatar"><img src="/images/avatars/cht-bot.png" alt="Dreamy"></div>
                    <div class="chatbot-bubble chatbot-bubble-error">
                        <div class="chatbot-error-title">⚠️ Sistem error — bukan jawaban Dreamy</div>
                        <div>${safeText}</div>
                        <div class="chatbot-error-code">${code}${CHATBOT_DEBUG ? ' · mode dev' : ''}</div>
                        ${debugHtml}
                    </div>`;
                messagesContainer.appendChild(messageDiv);
                scrollToBottom();
            }

            function showTypingIndicator() {
                const indicatorDiv = document.createElement('div');
                indicatorDiv.className = 'chatbot-message bot';
                indicatorDiv.innerHTML = `
                    <div class="chatbot-msg-avatar"><img src="/images/avatars/cht-bot.png" alt="Dreamy"></div>
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
