<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Business') — Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --sage:        #96A78D;
            --sage-light:  #B8C9AF;
            --sage-pale:   #E8EDE6;
            --sage-dark:   #6B7A63;
            --sage-deeper: #4A5645;
            --sidebar-w:   256px;
            --topbar-h:    64px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #F4F6F2;
            color: #2C3328;
            min-height: 100vh;
            display: flex;
        }

        /* ── SIDEBAR ─────────────────────────────────────── */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sage-deeper);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(0.16,1,0.3,1);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: var(--sage);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg { width: 20px; height: 20px; fill: white; }

        .brand-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.15rem;
            color: white;
            line-height: 1.2;
        }

        .brand-tagline {
            font-size: 0.7rem;
            color: var(--sage-light);
            font-weight: 300;
        }

        /* Nav sections */
        .nav-section {
            padding: 1rem 0 0.5rem;
        }

        .nav-section-title {
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            padding: 0 1.25rem 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1.25rem;
            margin: 0 0.75rem 2px;
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255,255,255,0.65);
            font-size: 0.875rem;
            font-weight: 400;
            transition: all 0.2s;
            position: relative;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .nav-item.active {
            background: var(--sage);
            color: white;
            font-weight: 500;
        }

        .nav-item.active .nav-icon { opacity: 1; }

        .nav-icon {
            width: 18px; height: 18px;
            opacity: 0.7;
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.8);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 20px;
        }

        .nav-item.active .nav-badge {
            background: rgba(255,255,255,0.25);
        }

        /* Sidebar footer */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.5rem;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-card:hover { background: rgba(255,255,255,0.07); }

        .user-avatar {
            width: 34px; height: 34px;
            background: var(--sage);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 0.82rem;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role {
            font-size: 0.7rem;
            color: var(--sage-light);
            text-transform: capitalize;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.55rem 0.75rem;
            margin-top: 0.35rem;
            background: rgba(220, 80, 80, 0.15);
            border: 1px solid rgba(220, 80, 80, 0.2);
            border-radius: 9px;
            color: rgba(255, 160, 160, 0.9);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }

        .logout-btn:hover {
            background: rgba(220, 80, 80, 0.25);
            color: rgba(255, 180, 180, 1);
        }

        /* ── MAIN CONTENT ─────────────────────────────────── */
        #main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar */
        #topbar {
            height: var(--topbar-h);
            background: white;
            border-bottom: 1px solid #E8EDE6;
            display: flex;
            align-items: center;
            padding: 0 1.75rem;
            gap: 1rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-toggle {
            display: none;
            width: 36px; height: 36px;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .topbar-toggle:hover { background: var(--sage-pale); }

        .page-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--sage-deeper);
        }

        .page-breadcrumb {
            font-size: 0.78rem;
            color: #9CA89A;
            margin-left: 0.5rem;
        }

        .topbar-right {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .topbar-btn {
            width: 36px; height: 36px;
            background: var(--sage-pale);
            border: none;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            position: relative;
        }

        .topbar-btn:hover { background: #D8E3D5; }

        .notif-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 7px; height: 7px;
            background: #E05252;
            border-radius: 50%;
            border: 1.5px solid white;
        }

        /* Content area */
        #content {
            padding: 1.75rem;
            flex: 1;
        }

        /* ── GLOBAL COMPONENTS ───────────────────────────── */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--sage-deeper);
        }

        .page-header p {
            font-size: 0.82rem;
            color: #7A8A78;
            margin-top: 2px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.855rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
        }

        .btn-sage {
            background: var(--sage);
            color: white;
        }

        .btn-sage:hover { background: var(--sage-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(107,122,99,0.3); }

        .btn-outline {
            background: white;
            border: 1px solid #D0DAC8;
            color: var(--sage-deeper);
        }

        .btn-outline:hover { background: var(--sage-pale); }

        .btn-danger {
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #DC2626;
        }

        .btn-danger:hover { background: #FEE2E2; }

        .btn-sm { padding: 0.4rem 0.8rem; font-size: 0.8rem; border-radius: 8px; }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            border: 1px solid #EAF0E7;
            padding: 1.5rem;
        }

        .card-sm { padding: 1.1rem 1.25rem; }

        /* Tables */
        .table-wrap {
            overflow-x: auto;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.875rem;
        }

        table.data-table thead th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #7A8A78;
            border-bottom: 1px solid #EAF0E7;
            white-space: nowrap;
        }

        table.data-table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #F4F6F2;
            color: #2C3328;
            vertical-align: middle;
        }

        table.data-table tbody tr:last-child td { border-bottom: none; }
        table.data-table tbody tr:hover td { background: #FAFBF9; }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
        }

        .badge-green  { background: #E8F5E9; color: #2E7D32; }
        .badge-red    { background: #FFEBEE; color: #C62828; }
        .badge-amber  { background: #FFF8E1; color: #F57F17; }
        .badge-sage   { background: var(--sage-pale); color: var(--sage-deeper); }
        .badge-blue   { background: #E3F2FD; color: #1565C0; }
        .badge-gray   { background: #F5F5F5; color: #616161; }

        /* Flash messages */
        .flash {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .flash-success { background: #E8F5E9; border: 1px solid #A5D6A7; color: #2E7D32; }
        .flash-error   { background: #FFEBEE; border: 1px solid #EF9A9A; color: #C62828; }

        /* Form elements */
        .form-group { margin-bottom: 1rem; }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--sage-deeper);
            margin-bottom: 0.35rem;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 0.65rem 0.9rem;
            background: white;
            border: 1px solid #D0DAC8;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            color: #2C3328;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--sage);
            box-shadow: 0 0 0 3px rgba(150, 167, 141, 0.15);
        }

        .form-input.error { border-color: #EF9A9A; }
        .form-error { font-size: 0.75rem; color: #C62828; margin-top: 4px; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

        /* Stat cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #EAF0E7;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: #7A8A78;
            letter-spacing: 0.03em;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--sage-deeper);
            line-height: 1;
            margin-bottom: 0.3rem;
        }

        .stat-sub {
            font-size: 0.75rem;
            color: #9CA89A;
        }

        .stat-icon {
            position: absolute;
            top: 1rem; right: 1rem;
            width: 36px; height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            #main { margin-left: 0; }
            .topbar-toggle { display: flex; }
            .form-grid { grid-template-columns: 1fr; }
        }

        /* Overlay for mobile sidebar */
        #overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
        }

        #overlay.show { display: block; }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div id="overlay" onclick="closeSidebar()"></div>

<!-- ══ SIDEBAR ══════════════════════════════════════════ -->
<aside id="sidebar">

    <div class="sidebar-brand">
        <div class="brand-icon">
            <svg viewBox="0 0 24 24"><path d="M3 3h18v3H3zm0 5h18v13H3zm2 3v7h14v-7z" opacity=".4"/><path d="M5 11h4v2H5zm6 0h4v2h-4zm-6 4h4v2H5zm6 0h4v2h-4z"/></svg>
        </div>
        <div>
            <div class="brand-name">E-Business</div>
            <div class="brand-tagline">Management System</div>
        </div>
    </div>

    <nav style="flex:1; overflow-y:auto; padding-bottom:1rem;">

        <div class="nav-section">
            <div class="nav-section-title">Utama</div>
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                Dashboard
            </a>
            <a href="{{ route('cashier.index') }}" class="nav-item {{ request()->routeIs('cashier.*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
                Kasir
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Manajemen</div>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Pengguna
            </a>
            <a href="{{ route('expenses.index') }}" class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                Pengeluaran
            </a>
        </div>

        <div class="nav-section">
            <div class="nav-section-title">Laporan</div>
            <a href="{{ route('history.index') }}" class="nav-item {{ request()->routeIs('history.*') ? 'active' : '' }}">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="12 8 12 12 14 14"/><path d="M3.05 11a9 9 0 1 0 .5-4.5"/><polyline points="3 3 3 8 8 8"/></svg>
                Histori Customer
            </a>
            <a href="#" class="nav-item">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Laporan
            </a>
        </div>

    </nav>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</div>
            <div>
                <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
                <div class="user-role">{{ Auth::user()->role ?? 'member' }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Keluar
            </button>
        </form>
    </div>

</aside>

<!-- ══ MAIN ══════════════════════════════════════════════ -->
<div id="main">

    <!-- Topbar -->
    <header id="topbar">
        <button class="topbar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4A5645" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
        <span class="page-title">@yield('page-title', 'Dashboard')</span>
        @hasSection('breadcrumb')
            <span class="page-breadcrumb">/ @yield('breadcrumb')</span>
        @endif
        <div class="topbar-right">
            <button class="topbar-btn" title="Notifikasi">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4A5645" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <span class="notif-dot"></span>
            </button>
            <div class="user-avatar" style="width:34px;height:34px;background:var(--sage);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.78rem;font-weight:600;color:white;cursor:pointer;">
                {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main id="content">
        {{-- Flash messages --}}
        @if(session('success'))
        <div class="flash flash-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="flash flash-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
function toggleSidebar() {
    const s = document.getElementById('sidebar');
    const o = document.getElementById('overlay');
    s.classList.toggle('open');
    o.classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
}
</script>

@stack('scripts')
</body>
</html>