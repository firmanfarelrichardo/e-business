<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sinergi Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;0,14..32,800&display=swap"
        rel="stylesheet">

    <!-- Tailwind CDN for development fallback -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            background-color: #f3f6f4;
            -webkit-font-smoothing: antialiased;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Layout Grid */
        .dashboard-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background: linear-gradient(180deg, #5A6852 0%, #3d4a38 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 40;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sidebar-brand-icon {
            width: 2.5rem;
            height: 2.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-profile {
            padding: 0 1.5rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            margin-bottom: 1.5rem;
        }

        .profile-img {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            background: #B6CEB4;
            border: 3px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            color: #fff;
        }

        .sidebar-menu {
            flex: 1;
            padding: 0 1rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1rem;
            color: rgba(255, 255, 255, 0.7);
            border-radius: 0.75rem;
            transition: all 0.2s;
            margin-bottom: 0.25rem;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .menu-item svg {
            width: 1.25rem;
            height: 1.25rem;
        }

        /* Top Header */
        .top-header {
            background: #fff;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            position: sticky;
            top: 0;
            z-index: 30;
        }

        /* Main Workspace */
        .workspace {
            padding: 2rem;
            width: 100%;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .dashboard-layout {
                grid-template-columns: 1fr;
            }

            .sidebar {
                display: none;
            }

            /* On a real app, we'd add mobile toggle */
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="{{ url('/') }}" class="sidebar-brand no-underline group">
                <div class="sidebar-brand-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-[15px] tracking-tight text-white group-hover:text-white/80 transition">
                        Sinergi.</h2>
                    <p class="text-[11px] text-white/45 font-medium tracking-wide">e-Business Platform</p>
                </div>
            </a>



            <nav class="sidebar-menu">
                @if(auth()->check() && auth()->user()->role !== 'member')
                    <a href="/dashboard" class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @endif

                <!-- Special edge case: members are heavily dependent on this menu for dashboard -->
                @if(auth()->check() && auth()->user()->role === 'member')
                    <a href="{{ route('profile') }}" class="menu-item {{ request()->is('profile') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                            </path>
                        </svg>
                        <span>Profil Saya</span>
                    </a>
                @endif

                <!-- RBAC: Only Owners can see Member Listings -->
                @if(auth()->check() && auth()->user()->role === 'owner')
                    <a href="{{ url('/dashboard/users') }}"
                        class="menu-item {{ request()->is('dashboard/users') ? 'active' : '' }}">
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
                        class="menu-item {{ request()->is('dashboard/products') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span>Produk & Inventori</span>
                    </a>

                    <a href="{{ url('/dashboard/services') }}"
                        class="menu-item {{ request()->is('dashboard/services') ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Jasa Perusahaan</span>
                    </a>

                    @if(auth()->check() && auth()->user()->role === 'owner')
                        <a href="{{ url('/dashboard/expenses') }}"
                            class="menu-item {{ request()->is('dashboard/expenses') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z">
                                </path>
                            </svg>
                            <span>Pengeluaran</span>
                        </a>
                    @endif

                    @if(in_array(auth()->user()->role, ['owner', 'employee']))
                        <a href="{{ url('/dashboard/queues') }}"
                            class="menu-item {{ request()->is('dashboard/queues') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            <span>Antrian Order</span>
                        </a>
                    @endif
                @else
                    <a href="{{ url('/katalog') }}" class="menu-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        <span>Katalog & Pesanan</span>
                    </a>
                @endif

                <!-- Bottom: Profile + Logout widget (pinned absolutely to sidebar bottom) -->
                <div class="absolute bottom-0 left-0 right-0 px-3 pb-4 pt-4 border-t border-white/10 bg-[#3d4f38]">
                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/10 transition cursor-pointer no-underline group mb-1">
                        <div
                            class="w-8 h-8 rounded-full bg-[#B6CEB4] flex items-center justify-center text-sm font-bold text-white shrink-0 group-hover:ring-2 group-hover:ring-white/30 transition">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <div class="text-white text-xs font-semibold truncate">{{ auth()->user()->name ?? 'User' }}
                            </div>
                            <div class="text-white/50 text-[10px] uppercase tracking-wider">
                                {{ auth()->user()->role ?? 'Employee' }}
                            </div>
                        </div>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="menu-item text-red-300 hover:text-red-100 hover:bg-red-500/20 w-full text-left bg-transparent border-none focus:outline-none cursor-pointer text-sm">
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

        <!-- Main Content Wrapper -->
        <main class="w-full flex flex-col min-h-screen">
            <!-- Header -->
            <header class="top-header">
                <div>
                    <!-- Mobile toggle placeholder -->
                </div>
                <div class="flex items-center gap-5">
                    <!-- Header icons removed per design update -->
                </div>
            </header>

            <!-- Page Content -->
            <div class="workspace">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Need to include motion & chart.js, handling from index view -->
    @stack('scripts')
</body>

</html>