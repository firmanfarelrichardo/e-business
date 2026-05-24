<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sinergi Dashboard' }}</title>
    <meta name="description" content="Dashboard pengelolaan Sinergi e-Business — Kelola produk, layanan, dan pesanan dengan mudah.">

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

    {{-- Alpine.js for UI interactivity --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Vite compiled CSS + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="antialiased text-[var(--color-text)]" style="background: var(--color-bg);">

    <div class="dashboard-layout">
        {{-- Mobile Overlay --}}
        <div id="mobile-overlay" class="mobile-overlay" onclick="toggleSidebar()"></div>

        {{-- ════════════════════════════════════════════════════════════
             SIDEBAR — Always dark themed for hierarchy
        ════════════════════════════════════════════════════════════ --}}
        <aside id="dashboard-sidebar" class="sidebar">
            {{-- Brand Block --}}
            <a href="{{ url('/') }}" class="sidebar-brand no-underline group" data-testid="sidebar-brand-link">
                <div class="sidebar-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-[15px] tracking-tight text-white group-hover:text-white/80 transition font-display">
                        Sinergi.</h2>
                    <p class="text-[11px] text-white/45 font-medium tracking-wide">e-Business Platform</p>
                </div>
            </a>

            {{-- Navigation Menu --}}
            <nav class="sidebar-menu">
                @if(auth()->check() && auth()->user()->role !== 'member')
                    <a href="/dashboard" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}" data-testid="sidebar-dashboard-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @endif

                {{-- Member-only: profile link --}}
                @if(auth()->check() && auth()->user()->role === 'member')
                    <a href="{{ route('profile') }}" class="menu-item {{ request()->is('profile') ? 'active' : '' }}" data-testid="sidebar-profile-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                @endif

                {{-- RBAC: Only Owners can see Member Listings --}}
                @if(auth()->check() && auth()->user()->role === 'owner')
                    <a href="{{ url('/dashboard/users') }}"
                        class="menu-item {{ request()->is('dashboard/users') ? 'active' : '' }}" data-testid="sidebar-users-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <span>List User</span>
                    </a>
                @endif

                @if(auth()->check() && auth()->user()->role !== 'member')
                    <a href="{{ url('/dashboard/products') }}"
                        class="menu-item {{ request()->is('dashboard/products') ? 'active' : '' }}" data-testid="sidebar-products-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span>Produk & Inventori</span>
                    </a>

                    <a href="{{ url('/dashboard/services') }}"
                        class="menu-item {{ request()->is('dashboard/services') ? 'active' : '' }}" data-testid="sidebar-services-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Layanan Jasa</span>
                    </a>

                    @if(auth()->check() && auth()->user()->role === 'owner')
                        <a href="{{ url('/dashboard/expenses') }}"
                            class="menu-item {{ request()->is('dashboard/expenses') ? 'active' : '' }}" data-testid="sidebar-expenses-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                                </path>
                            </svg>
                            <span>Pengeluaran</span>
                        </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['owner', 'employee']))
                        <a href="{{ url('/dashboard/batches') }}"
                            class="menu-item {{ request()->is('dashboard/batches') ? 'active' : '' }}" data-testid="sidebar-batches-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                </path>
                            </svg>
                            <span>Batch Stok</span>
                        </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['owner', 'employee']))
                        <a href="{{ url('/dashboard/queues') }}"
                            class="menu-item {{ request()->is('dashboard/queues') ? 'active' : '' }}" data-testid="sidebar-queues-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            <span>Antrian Order</span>
                        </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['owner', 'employee']))
                        <a href="{{ url('/dashboard/reports/stock-card') }}"
                            class="menu-item {{ request()->is('dashboard/reports/stock-card') ? 'active' : '' }}" data-testid="sidebar-stockcard-link">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span>Kartu Stok</span>
                        </a>
                    @endif
                @else
                    <a href="{{ url('/katalog') }}" class="menu-item" data-testid="sidebar-katalog-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Katalog & Pesanan</span>
                    </a>
                @endif

                {{-- Bottom: Profile + Logout widget --}}
                <div class="absolute bottom-0 left-0 right-0 px-3 pb-4 pt-4 border-t border-white/10 bg-black/20">
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-[var(--radius-md)] hover:bg-white/10 transition cursor-pointer no-underline group mb-1"
                        data-testid="sidebar-profile-widget">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white shrink-0 group-hover:ring-2 group-hover:ring-white/30 transition shadow-sm"
                             style="background: var(--gradient-gold-day);">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-white text-xs font-semibold truncate font-display">{{ auth()->user()->name ?? 'User' }}
                            </div>
                            <div class="text-[var(--night-200)] text-[10px] uppercase tracking-wider font-semibold">
                                {{ auth()->user()->role ?? 'Employee' }}
                            </div>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="menu-item text-[var(--accent-rose)] hover:text-white hover:bg-[var(--accent-rose)]/20 w-full text-left bg-transparent border-none focus:outline-none cursor-pointer text-sm"
                            data-testid="sidebar-logout-button">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                            <span>Log Out</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        {{-- ════════════════════════════════════════════════════════════
             MAIN CONTENT WRAPPER
        ════════════════════════════════════════════════════════════ --}}
        <main class="w-full flex flex-col min-h-screen relative" style="background: var(--color-bg);">
            {{-- Background decorative elements --}}
            <div class="absolute top-0 right-0 w-[30rem] h-[30rem] rounded-full opacity-10 pointer-events-none -z-0"
                 style="background: var(--accent-violet); filter: blur(120px);"></div>
            <div class="absolute bottom-0 left-0 w-[20rem] h-[20rem] rounded-full opacity-10 pointer-events-none -z-0"
                 style="background: var(--accent-cyan); filter: blur(100px);"></div>

            {{-- Top Header --}}
            <header class="top-header" data-testid="dashboard-header">
                <div class="flex items-center gap-4">
                    {{-- Mobile toggle --}}
                    <button onclick="toggleSidebar()"
                        class="lg:hidden p-2 -ml-2 text-[var(--color-text-muted)] hover:text-[var(--color-text)] focus:outline-none rounded-[var(--radius-sm)] hover:bg-[var(--color-bg-elevated)] transition"
                        data-testid="dashboard-mobile-toggle"
                        aria-label="Toggle sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    {{-- Page title / breadcrumb --}}
                    <div>
                        @hasSection('header')
                            @yield('header')
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    {{-- Theme Toggle --}}
                    <x-ui.theme-toggle />

                    {{-- Profile quick access --}}
                    <a href="{{ route('profile') }}"
                       class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full hover:bg-[var(--color-bg-elevated)] transition text-sm"
                       data-testid="header-profile-link">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white"
                             style="background: linear-gradient(135deg, var(--accent-violet), var(--accent-cyan));">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <span class="text-[var(--color-text)] font-medium font-display hidden md:inline">{{ auth()->user()->name ?? 'User' }}</span>
                    </a>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="workspace relative z-10">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('dashboard-sidebar');
            const overlay = document.getElementById('mobile-overlay');
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
    </script>
    @stack('scripts')
</body>

</html>