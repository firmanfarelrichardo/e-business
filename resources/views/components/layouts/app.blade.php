<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sinergi - Pusat ATK & Jasa Percetakan' }}</title>
    <meta name="description" content="Sinergi e-Business — Platform toko ATK & jasa percetakan terpadu. Kualitas premium, diproses cepat & praktis.">

    {{-- No-flash theme script (must run BEFORE CSS) --}}
    <script>
        (function() {
            var t = localStorage.getItem('sinergi_theme') ||
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Manrope:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Alpine.js for UI Interactivity --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Vite compiled CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased flex flex-col min-h-screen relative overflow-x-hidden text-[var(--color-text)]" x-data="{ mobileMenuOpen: false }">

    {{-- Navbar --}}
    <div class="fixed w-full z-50 top-0 px-0 sm:top-4 sm:px-4 print:hidden">
        <nav class="max-w-7xl mx-auto glass-panel sm:rounded-full px-6 py-3 flex justify-between items-center transition-all duration-300"
             data-testid="main-navbar">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 group z-10" data-testid="nav-logo-link">
                <div class="w-10 h-10 rounded-full gradient-gold-day flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <span class="font-bold text-xl tracking-tight font-display text-[var(--color-text)] group-hover:text-[var(--color-primary)] transition">Sinergi.</span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex space-x-8 items-center z-10">
                <a href="{{ url('/katalog') }}"
                    class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] text-sm font-semibold transition duration-200 relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-[var(--color-primary)] after:transition-all after:duration-300 hover:after:w-full"
                    data-testid="nav-katalog-link">Katalog ATK</a>
                <a href="{{ url('/jasa') }}"
                    class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] text-sm font-semibold transition duration-200 relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-[var(--color-primary)] after:transition-all after:duration-300 hover:after:w-full"
                    data-testid="nav-jasa-link">Jasa Cetak</a>
                <a href="{{ url('/history') }}"
                    class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] text-sm font-semibold transition duration-200 relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-[var(--color-primary)] after:transition-all after:duration-300 hover:after:w-full"
                    data-testid="nav-lacak-link">Lacak Pesanan</a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center space-x-3 z-10">
                {{-- Theme Toggle --}}
                <x-ui.theme-toggle />

                {{-- Cart Icon --}}
                <a href="{{ url('/keranjang') }}"
                    class="relative p-2 text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] transition duration-200"
                    data-testid="nav-cart-link">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    {{-- Cart Badge --}}
                    @php
                        $cartCount = collect(session('cart', []))->sum('quantity');
                    @endphp
                    @if($cartCount > 0)
                        <span
                            class="absolute top-0 right-0 gradient-aurora text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white/30 shadow-[var(--shadow-glow)]"
                            data-testid="cart-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                    @endif
                </a>

                @auth
                    <div class="hidden md:flex items-center space-x-3">
                        @if(auth()->user()->role === 'member')
                            <a href="{{ route('profile') }}"
                                class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] font-medium px-2 text-sm"
                                data-testid="nav-profile-link">Profil Saya</a>
                        @else
                            <a href="{{ url('/dashboard') }}"
                                class="text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] font-medium px-2 text-sm"
                                data-testid="nav-dashboard-link">Dashboard</a>
                        @endif

                        {{-- Header Log Out Form --}}
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit"
                                class="text-[var(--accent-rose)] hover:brightness-110 font-medium px-2 text-sm bg-transparent border-none focus:outline-none cursor-pointer"
                                data-testid="nav-logout-button">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ url('/login') }}"
                        class="hidden md:block text-[var(--color-text-secondary)] hover:text-[var(--color-primary)] text-sm font-medium transition duration-200 px-2"
                        data-testid="nav-login-link">Masuk</a>
                @endauth

                {{-- Mobile menu button --}}
                <button class="md:hidden text-[var(--color-text)] hover:text-[var(--color-primary)] focus:outline-none p-1"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        data-testid="nav-mobile-toggle"
                        aria-label="Toggle mobile menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!mobileMenuOpen">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="mobileMenuOpen" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Mobile Menu Overlay --}}
        <div x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="md:hidden mt-2 mx-4 glass-overlay overflow-hidden"
             x-cloak
             data-testid="mobile-menu">
            <div class="flex flex-col p-4 space-y-1">
                <a href="{{ url('/katalog') }}" class="px-4 py-3 text-[var(--color-text)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] rounded-[var(--radius-md)] font-semibold transition">Katalog ATK</a>
                <a href="{{ url('/jasa') }}" class="px-4 py-3 text-[var(--color-text)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] rounded-[var(--radius-md)] font-semibold transition">Jasa Cetak</a>
                <a href="{{ url('/history') }}" class="px-4 py-3 text-[var(--color-text)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] rounded-[var(--radius-md)] font-semibold transition">Lacak Pesanan</a>

                <div class="border-t border-[var(--color-border-subtle)] my-2 pt-2">
                    @auth
                        @if(auth()->user()->role === 'member')
                            <a href="{{ route('profile') }}" class="px-4 py-3 text-[var(--color-text)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] rounded-[var(--radius-md)] font-semibold transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Profil Saya
                            </a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="px-4 py-3 text-[var(--color-text)] hover:bg-[var(--color-primary)]/10 hover:text-[var(--color-primary)] rounded-[var(--radius-md)] font-semibold transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Dashboard
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="px-4 py-3">
                            @csrf
                            <button type="submit" class="w-full text-left text-[var(--accent-rose)] font-semibold flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ url('/login') }}" class="px-4 py-3 text-[var(--color-primary)] font-bold transition flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Masuk Ke Akun
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <main class="flex-grow pt-20 sm:pt-28 pb-12 relative z-10">
        {{-- Background Blobs --}}
        <div class="blob bg-[var(--accent-cyan)] w-[30rem] h-[30rem] top-20 -left-20 -z-10 opacity-30"></div>
        <div class="blob bg-[var(--accent-violet)] w-[20rem] h-[20rem] bottom-10 right-10 -z-10 opacity-20"
            style="animation-delay: -5s;"></div>
        {{-- Star field (dark mode only) --}}
        <div class="star-field -z-10"></div>

        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <footer class="relative z-10 overflow-hidden mt-auto print:hidden border-t border-[var(--color-border-subtle)]"
            style="background: var(--color-bg-overlay);"
            data-testid="main-footer">
        {{-- Dark mode aurora ribbon --}}
        <div class="aurora-ribbon"></div>

        {{-- Background accent blob --}}
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-[var(--color-primary)]/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-8">
                {{-- Branding --}}
                <div class="lg:col-span-1 lg:border-r border-[var(--color-border-subtle)] pr-6">
                    <a href="/" class="flex items-center gap-2 mb-4 group inline-flex" data-testid="footer-logo-link">
                        <div class="w-8 h-8 rounded-full gradient-gold-day flex items-center justify-center shadow-md">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-[var(--color-text)] tracking-tight font-display">Sinergi.</span>
                    </a>
                    <p class="text-[var(--color-text-muted)] leading-relaxed font-light text-sm">
                        Distributor terdepan piranti kantor dan jasa percetakan andal untuk ekosistem kerja profesional
                        yang efisien.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="text-[var(--color-text)] font-bold mb-4 uppercase tracking-widest text-xs font-display">Eksplorasi</h4>
                    <ul class="space-y-2 font-light text-sm">
                        <li><a href="{{ url('/katalog') }}" class="text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition">Katalog Belanja ATK</a></li>
                        <li><a href="{{ url('/jasa') }}" class="text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition">Layanan Percetakan</a></li>
                    </ul>
                </div>

                {{-- Support --}}
                <div>
                    <h4 class="text-[var(--color-text)] font-bold mb-4 uppercase tracking-widest text-xs font-display">Bantuan</h4>
                    <ul class="space-y-2 font-light text-sm">
                        <li><a href="{{ url('/history') }}" class="text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition">Lacak Pengiriman</a></li>
                        <li><a href="{{ url('/not-configured') }}" class="text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

                {{-- Contact Details --}}
                <div>
                    <h4 class="text-[var(--color-text)] font-bold mb-4 uppercase tracking-widest text-xs font-display">Hubungi Kami</h4>
                    <ul class="space-y-3 font-light text-[var(--color-text-muted)] text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-[var(--color-primary)] shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                            </svg>
                            <span>Gedung Sinergi Lt. 5, Jakarta</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-[var(--color-border-subtle)] pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[var(--color-text-muted)] text-xs font-light text-center md:text-left">
                    &copy; {{ date('Y') }} Sinergi e-Business. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>

    {{-- Global Scripts/Animations --}}
    <script type="module">
        import { animate, inView, stagger } from "https://esm.sh/motion";

        // Generic entrance for cards and categories if present
        inView(".glass-card, .grid > div, .motion-card", (element) => {
            animate(
                element,
                { y: [30, 0], opacity: [0, 1] },
                { duration: 0.6, easing: [0.17, 0.55, 0.55, 1] }
            );
        });

        // Specific entrance for generic catalog wrappers
        inView(".max-w-7xl", (element) => {
            const children = element.querySelectorAll("h1, h2, h3, h4");
            if (children.length > 0) {
                animate(
                    children,
                    { y: [20, 0], opacity: [0, 1] },
                    { duration: 0.5, delay: stagger(0.1) }
                );
            }
        });

    </script>
    @stack('scripts')
</body>

</html>