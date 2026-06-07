<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($initialMode) && $initialMode === 'register' ? 'Daftar — ' : 'Masuk — ' }}Sinergi e-Business</title>

    {{-- No-flash theme script --}}
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

    {{-- Vite compiled CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══════════════════════════════════════════
           AUTH-SPECIFIC STYLES (scoped to this page)
        ══════════════════════════════════════════ */
        :root {
            --dur: 750ms;
            --ease: cubic-bezier(0.77, 0, 0.175, 1);
        }

        html, body { height: 100%; overflow: hidden; }

        .auth-shell {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--night-800, #06091c);
            overflow: hidden;
        }

        .shell-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 60% at 20% 80%, rgba(30,133,251,.18) 0%, transparent 65%),
                radial-gradient(ellipse 40% 80% at 90% 15%, rgba(124,92,255,.12) 0%, transparent 60%);
        }

        /* ── AUTH CARD ── */
        .auth-card {
            position: relative;
            z-index: 10;
            width: min(1040px, 96vw);
            height: min(640px, 92vh);
            border-radius: var(--radius-2xl, 2rem);
            overflow: hidden;
            box-shadow:
                0 40px 100px rgba(0, 0, 0, 0.55),
                0 0 0 1px rgba(255, 255, 255, 0.06) inset;
            display: flex;
        }

        /* ── BRANDING PANEL ── */
        .brand-panel {
            position: absolute;
            top: 0; bottom: 0; left: 0;
            width: 46%;
            overflow: hidden;
            z-index: 20;
            transition: left var(--dur) var(--ease), border-radius var(--dur) var(--ease);
        }

        .auth-card.show-register .brand-panel { left: 54%; }

        .brand-img {
            position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--night-700, #190900), var(--night-600, #451A03), var(--accent-violet, #F59E0B), var(--accent-cyan, #FBBF24));
            background-size: 400% 400%;
            animation: gradientAnim 15s ease infinite;
            opacity: 0.9;
            transition: transform var(--dur) var(--ease);
            transform: scale(1.04);
        }

        @keyframes gradientAnim {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .auth-card.show-register .brand-panel .brand-img { transform: scale(1); }

        .brand-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(145deg, var(--night-800, #06091c) 0%, var(--night-700, #0b1530) 55%, rgba(11,21,48,0.9) 100%);
            opacity: 0.82;
        }

        .brand-topo {
            position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='640'%3E%3Cg fill='none' stroke='rgba(255,255,255,0.05)' stroke-width='1.2'%3E%3Cellipse cx='300' cy='320' rx='260' ry='200'/%3E%3Cellipse cx='300' cy='320' rx='200' ry='150'/%3E%3Cellipse cx='300' cy='320' rx='140' ry='100'/%3E%3Cellipse cx='300' cy='320' rx='80' ry='55'/%3E%3Cellipse cx='160' cy='150' rx='110' ry='80'/%3E%3Cellipse cx='160' cy='150' rx='70' ry='50'/%3E%3C/g%3E%3C/svg%3E");
            background-size: cover;
        }

        .brand-blob { position: absolute; border-radius: 50%; filter: blur(80px); }

        .brand-blob-1 {
            width: 260px; height: 260px;
            background: var(--accent-violet, #F59E0B);
            top: -80px; left: -80px;
            opacity: 0.3;
            animation: blobDrift 14s ease-in-out infinite alternate;
        }

        .brand-blob-2 {
            width: 200px; height: 200px;
            background: var(--accent-cyan, #22d3ee);
            bottom: -60px; right: -40px;
            opacity: 0.2;
            animation: blobDrift2 10s ease-in-out infinite alternate;
        }

        @keyframes blobDrift  { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(20px, -30px) scale(1.1); } }
        @keyframes blobDrift2 { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-15px, 20px) scale(1.08); } }

        .brand-content {
            position: relative; z-index: 5;
            height: 100%;
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 2.25rem;
            color: #fff;
        }

        .brand-logo { display: flex; align-items: center; gap: 0.625rem; }

        .brand-logo-badge {
            width: 2.5rem; height: 2.5rem;
            border-radius: var(--radius-md, 0.875rem);
            background: linear-gradient(135deg, var(--accent-violet, #F59E0B), var(--accent-cyan, #22d3ee));
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 16px rgba(124,92,255,0.35);
        }

        .brand-logo-text { font-size: 1.2rem; font-weight: 800; letter-spacing: -0.02em; color: #fff; font-family: var(--font-display); }
        .brand-logo-text span { background: linear-gradient(135deg, var(--accent-cyan), var(--accent-mint)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

        .brand-headline { padding-bottom: 0.5rem; }
        .brand-headline h2 {
            font-size: clamp(1.6rem, 2.4vw, 2.1rem);
            font-weight: 800; letter-spacing: -0.03em;
            line-height: 1.18; margin-bottom: 0.85rem;
            font-family: var(--font-display);
        }
        .brand-headline h2 span {
            display: block;
            background: linear-gradient(90deg, var(--accent-cyan), var(--accent-mint));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .brand-headline p { font-size: 0.875rem; color: rgba(255,255,255,0.55); font-weight: 300; line-height: 1.65; max-width: 26ch; }

        .brand-msg {
            transition: opacity var(--dur) var(--ease), transform var(--dur) var(--ease);
        }
        .brand-msg.for-login { opacity: 1; transform: translateY(0); }
        .brand-msg.for-register { position: absolute; opacity: 0; transform: translateY(20px); pointer-events: none; }
        .auth-card.show-register .brand-msg.for-login { opacity: 0; transform: translateY(-16px); }
        .auth-card.show-register .brand-msg.for-register { opacity: 1; transform: translateY(0); pointer-events: auto; }

        .brand-footer { font-size: 0.72rem; color: rgba(255,255,255,0.28); }
        .brand-deco { position: absolute; color: rgba(255,255,255,0.1); font-size: 1.1rem; letter-spacing: 4px; user-select: none; }
        .brand-deco.top-right { top: 2rem; right: 1.75rem; }
        .brand-deco.mid-left { top: 45%; left: 1.75rem; }

        /* ── FORM PANEL ── */
        .form-side {
            position: absolute;
            top: 0; bottom: 0; left: 46%;
            width: 54%; height: 100%;
            background: var(--color-bg, #f8faff);
            display: flex; flex-direction: column;
            justify-content: center;
            overflow: hidden;
            transition: left var(--dur) var(--ease);
        }

        .auth-card.show-register .form-side { left: 0; }

        .form-track {
            display: flex; width: 200%; height: 100%;
            transform: translateX(0);
            transition: transform var(--dur) var(--ease);
            will-change: transform;
        }

        .auth-card.show-register .form-track { transform: translateX(-50%); }

        .form-panel {
            width: 50%; height: 100%;
            padding: 2.5rem 3rem;
            display: flex; flex-direction: column;
            justify-content: center;
            overflow-y: auto; flex-shrink: 0;
            transition: opacity var(--dur) var(--ease), transform var(--dur) var(--ease);
        }

        .form-panel.fp-login { opacity: 1; transform: translateX(0); }
        .auth-card.show-register .form-panel.fp-login { opacity: 0; transform: translateX(5%); }
        .form-panel.fp-register { opacity: 0; transform: translateX(-5%); }
        .auth-card.show-register .form-panel.fp-register { opacity: 1; transform: translateX(0); }

        .fp-heading { margin-bottom: 1.6rem; }
        .fp-heading h1 {
            font-size: clamp(1.4rem, 2vw, 1.75rem);
            font-weight: 800; letter-spacing: -0.03em;
            color: var(--color-text);
            line-height: 1.18; margin-bottom: 0.4rem;
            font-family: var(--font-display);
        }
        .fp-heading p { font-size: 0.82rem; color: var(--color-text-muted); font-weight: 400; }
        .fp-heading p a, .fp-heading p button {
            color: var(--color-primary, #D97706);
            font-weight: 600; cursor: pointer;
            background: none; border: none; font-family: inherit; font-size: inherit;
            text-decoration: underline; text-underline-offset: 2px; padding: 0;
            transition: color 0.2s;
        }
        .fp-heading p a:hover, .fp-heading p button:hover { color: var(--accent-violet); }

        /* ── FORM FIELDS ── */
        .form-fields { display: flex; flex-direction: column; gap: 0.9rem; }

        .field-group { display: flex; flex-direction: column; gap: 0.3rem; }
        .field-label {
            font-size: 0.72rem; font-weight: 600;
            color: var(--color-text-muted);
            letter-spacing: 0.06em; text-transform: uppercase;
            font-family: var(--font-display);
        }
        .field-wrap { position: relative; display: flex; align-items: center; }

        .field-icon {
            position: absolute; left: 0.875rem;
            color: var(--color-text-muted);
            pointer-events: none; transition: color 0.25s; flex-shrink: 0;
        }
        .field-icon svg { width: 0.95rem; height: 0.95rem; display: block; }

        .field-input {
            width: 100%;
            padding: 0.7rem 0.875rem 0.7rem 2.5rem;
            background: var(--color-bg-sunken);
            border: 1.5px solid var(--color-border);
            border-radius: var(--radius-sm, 0.75rem);
            color: var(--color-text);
            font-size: 0.875rem; font-family: inherit; font-weight: 400;
            outline: none;
            transition: border-color 0.25s, background 0.25s, box-shadow 0.25s;
        }
        .field-input::placeholder { color: var(--color-text-muted); opacity: 0.6; }
        .field-input:focus {
            background: var(--color-bg-elevated);
            border-color: var(--color-primary, #D97706);
            box-shadow: 0 0 0 3px rgba(30,133,251,0.18);
        }
        .field-wrap:focus-within .field-icon { color: var(--color-primary); }

        .toggle-pw {
            position: absolute; right: 0.875rem;
            background: none; border: none; cursor: pointer;
            color: var(--color-text-muted); padding: 0;
            display: flex; align-items: center; transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--color-primary); }
        .toggle-pw svg { width: 0.95rem; height: 0.95rem; }
        .field-input.has-toggle { padding-right: 2.5rem; }

        /* ── Error / Alert ── */
        .field-error {
            font-size: 0.7rem; color: var(--accent-rose, #f43f5e);
            font-weight: 500; display: none; animation: shakeErr 0.4s ease;
        }
        .field-error.visible { display: block; }

        @keyframes shakeErr { 0%,100%{transform:translateX(0)} 20%,60%{transform:translateX(-4px)} 40%,80%{transform:translateX(4px)} }

        .alert-banner {
            padding: 0.6rem 0.875rem;
            border-radius: var(--radius-sm); font-size: 0.78rem; font-weight: 500;
            margin-bottom: 0.4rem; display: none; animation: fadeSlide 0.3s ease;
        }
        @keyframes fadeSlide { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }

        .alert-error {
            background: rgba(244,63,94,0.1); border: 1px solid rgba(244,63,94,0.3); color: var(--accent-rose);
            display: flex; align-items: center; gap: 0.45rem;
        }
        .alert-success {
            background: rgba(34,211,238,0.1); border: 1px solid rgba(34,211,238,0.3); color: var(--accent-teal);
            display: flex; align-items: center; gap: 0.45rem;
        }

        /* ── Submit button ── */
        .btn-submit {
            position: relative; width: 100%;
            padding: 0.82rem 1.5rem;
            background: linear-gradient(135deg, var(--accent-violet, #F59E0B), var(--gold-600, #D97706));
            border: none; border-radius: var(--radius-sm);
            color: #fff; font-size: 0.9rem; font-weight: 700;
            font-family: var(--font-display); cursor: pointer; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
            box-shadow: 0 4px 18px rgba(124,92,255,0.35);
            margin-top: 0.35rem;
        }
        .btn-submit:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(124,92,255,0.45); }
        .btn-submit:active:not(:disabled) { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

        .btn-submit .ripple {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: scale(0); animation: rippleAnim 0.55s linear; pointer-events: none;
        }
        @keyframes rippleAnim { to { transform: scale(4); opacity: 0; } }

        .btn-spinner {
            display: none; width: 1rem; height: 1rem;
            border: 2px solid rgba(255,255,255,0.35);
            border-top-color: #fff; border-radius: 50%;
            animation: spin 0.7s linear infinite; margin: 0 auto;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .btn-submit.loading .btn-text { display: none; }
        .btn-submit.loading .btn-spinner { display: block; }

        .switch-line { text-align: center; margin-top: 1.1rem; font-size: 0.8rem; color: var(--color-text-muted); }
        .switch-link {
            color: var(--color-primary); font-weight: 600; cursor: pointer;
            background: none; border: none; font-family: inherit; font-size: inherit;
            text-decoration: underline; text-underline-offset: 2px; transition: color 0.2s; padding: 0;
        }
        .switch-link:hover { color: var(--accent-violet); }

        .progress-bar {
            position: absolute; top: 0; left: 0;
            height: 3px; width: 0;
            background: linear-gradient(90deg, var(--accent-violet), var(--accent-cyan));
            border-radius: 0 0 3px 0;
            transition: width 0.4s ease; z-index: 100;
        }

        .back-home {
            position: fixed; top: 1.5rem; left: 1.75rem; z-index: 50;
            display: flex; align-items: center; gap: 0.4rem;
            color: rgba(255,255,255,.45); font-size: 0.78rem; font-weight: 500;
            text-decoration: none; transition: color 0.2s, gap 0.2s;
            font-family: var(--font-display);
        }
        .back-home:hover { color: rgba(255,255,255,.9); gap: 0.6rem; }
        .back-home svg { width: 1rem; height: 1rem; }

        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-card { width: 100vw; height: 100vh; border-radius: 0; }
            .brand-panel { display: none; }
            .form-side { width: 100%; right: 0; left: 0; }
            .form-panel { padding: 2rem 1.75rem; }
        }

        @media (max-width: 900px) and (min-width: 769px) {
            .brand-panel { width: 40%; }
            .form-side { width: 60%; }
            .auth-card.show-register .brand-panel { transform: translateX(150%); }
        }

        .auth-card.show-register .panel-divider { left: 46%; }
    </style>
</head>

<body>
    {{-- Star field ambient particles --}}
    <div class="auth-shell">
        <div class="shell-bg"></div>

        {{-- Back home --}}
        <a href="{{ url('/') }}" class="back-home" data-testid="auth-back-home">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Beranda
        </a>

        {{-- ══ AUTH CARD ══ --}}
        <div class="auth-card {{ isset($initialMode) && $initialMode === 'register' ? 'show-register' : '' }}"
            id="authCard">

            {{-- Top progress bar --}}
            <div class="progress-bar" id="progressBar"></div>

            {{-- ╔══════════════════════════════════════╗
                 ║     BRANDING PANEL (LEFT)            ║
                 ╚══════════════════════════════════════╝ --}}
            <div class="brand-panel">
                <div class="brand-img"></div>
                <div class="brand-overlay"></div>
                <div class="brand-topo"></div>
                <div class="brand-blob brand-blob-1"></div>
                <div class="brand-blob brand-blob-2"></div>

                <div class="brand-deco top-right">+ + +</div>
                <div class="brand-deco mid-left">· · ·</div>

                <div class="brand-content">
                    <div class="brand-logo">
                        <div class="brand-logo-badge">
                            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="brand-logo-text">Sinergi<span>.</span></span>
                    </div>

                    <div class="brand-headline" style="position:relative;">
                        <div class="brand-msg for-login">
                            <h2>Selamat<br>Datang 👋<span></span></h2>
                            <p>Masuk untuk melanjutkan ke akun Anda dan kelola bisnis dengan lebih efisien.</p>
                        </div>
                        <div class="brand-msg for-register">
                            <h2>Bergabung<br>Bersama Kami ✨<span></span></h2>
                            <p>Daftar sekarang dan mulai perjalanan bisnis digital Anda bersama Sinergi.</p>
                        </div>
                    </div>

                    <div class="brand-footer">
                        &copy; {{ date('Y') }} Sinergi e-Business. All rights reserved.
                    </div>
                </div>
            </div>

            <div class="panel-divider"></div>

            {{-- ╔══════════════════════════════════════╗
                 ║     FORM SIDE (RIGHT)                ║
                 ╚══════════════════════════════════════╝ --}}
            <div class="form-side">
                <div class="form-track" id="formTrack">

                    {{-- ── LOGIN FORM PANEL ── --}}
                    <div class="form-panel fp-login">
                        <div class="fp-heading">
                            <h1>Masuk</h1>
                            <p>Belum punya akun? <button type="button" onclick="switchTo('register')">Daftar
                                    sekarang</button></p>
                        </div>

                        <div class="alert-banner" id="loginAlert"></div>

                        <form class="form-fields" id="loginForm" novalidate>
                            <div class="field-group">
                                <label class="field-label" for="login_username">Username</label>
                                <div class="field-wrap">
                                    <span class="field-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </span>
                                    <input id="login_username" name="username" type="text" class="field-input"
                                        placeholder="contoh: budi_santoso" autocomplete="username" spellcheck="false">
                                </div>
                                <span class="field-error" id="err_login_username"></span>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="login_password">Password</label>
                                <div class="field-wrap">
                                    <span class="field-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </span>
                                    <input id="login_password" name="password" type="password"
                                        class="field-input has-toggle" placeholder="••••••••"
                                        autocomplete="current-password">
                                    <button type="button" class="toggle-pw" onclick="togglePw('login_password', this)"
                                        aria-label="Tampilkan password">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <span class="field-error" id="err_login_password"></span>
                            </div>

                            <button type="submit" class="btn-submit" id="loginBtn">
                                <span class="btn-text">Masuk &rarr;</span>
                                <div class="btn-spinner"></div>
                            </button>
                        </form>
                    </div>

                    {{-- ── REGISTER FORM PANEL ── --}}
                    <div class="form-panel fp-register">
                        <div class="fp-heading">
                            <h1>Buat Akun</h1>
                            <p>Sudah punya akun? <button type="button" onclick="switchTo('login')">Masuk di
                                    sini</button></p>
                        </div>

                        <div class="alert-banner" id="registerAlert"></div>

                        <form class="form-fields" id="registerForm" novalidate>
                            <div class="field-row">
                                <div class="field-group">
                                    <label class="field-label" for="reg_name">Nama Lengkap</label>
                                    <div class="field-wrap">
                                        <span class="field-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </span>
                                        <input id="reg_name" name="name" type="text" class="field-input"
                                            placeholder="Nama Lengkap" autocomplete="name">
                                    </div>
                                    <span class="field-error" id="err_reg_name"></span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="reg_username">Username</label>
                                    <div class="field-wrap">
                                        <span class="field-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </span>
                                        <input id="reg_username" name="username" type="text" class="field-input"
                                            placeholder="budi_santoso" autocomplete="username" spellcheck="false">
                                    </div>
                                    <span class="field-error" id="err_reg_username"></span>
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="reg_email">Email</label>
                                <div class="field-wrap">
                                    <span class="field-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </span>
                                    <input id="reg_email" name="email" type="email" class="field-input"
                                        placeholder="email@contoh.com" autocomplete="email">
                                </div>
                                <span class="field-error" id="err_reg_email"></span>
                            </div>

                            <div class="field-row">
                                <div class="field-group">
                                    <label class="field-label" for="reg_password">Password</label>
                                    <div class="field-wrap">
                                        <span class="field-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </span>
                                        <input id="reg_password" name="password" type="password"
                                            class="field-input has-toggle" placeholder="Min. 8 karakter"
                                            autocomplete="new-password">
                                        <button type="button" class="toggle-pw" onclick="togglePw('reg_password', this)"
                                            aria-label="Tampilkan password">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="field-error" id="err_reg_password"></span>
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="reg_password_confirmation">Konfirmasi</label>
                                    <div class="field-wrap">
                                        <span class="field-icon">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </span>
                                        <input id="reg_password_confirmation" name="password_confirmation"
                                            type="password" class="field-input has-toggle" placeholder="Ulangi password"
                                            autocomplete="new-password">
                                        <button type="button" class="toggle-pw"
                                            onclick="togglePw('reg_password_confirmation', this)"
                                            aria-label="Tampilkan konfirmasi password">
                                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="field-error" id="err_reg_password_confirmation"></span>
                                </div>
                            </div>

                            <div class="field-group">
                                <label class="field-label" for="reg_address">
                                    Alamat <span style="color:var(--color-text-muted);font-weight:400;text-transform:none;letter-spacing:0;">(opsional)</span>
                                </label>
                                <div class="field-wrap">
                                    <span class="field-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </span>
                                    <input id="reg_address" name="address" type="text" class="field-input"
                                        placeholder="Jl. Merdeka No. 10, Jakarta" autocomplete="street-address">
                                </div>
                                <span class="field-error" id="err_reg_address"></span>
                            </div>

                            <button type="submit" class="btn-submit" id="registerBtn">
                                <span class="btn-text">Daftar Sekarang &rarr;</span>
                                <div class="btn-spinner"></div>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>


    <script>
        /* ══════════════════════════════════════════════
           AUTH.JS — Split Panel + Mixed Slider
        ══════════════════════════════════════════════ */

        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content;
        const API = '/web-auth';

        let currentMode = '{{ isset($initialMode) ? $initialMode : "login" }}';
        const card = document.getElementById('authCard');

        function switchTo(mode) {
            if (mode === currentMode) return;
            currentMode = mode;

            if (mode === 'register') {
                card.classList.add('show-register');
                document.title = 'Daftar — Sinergi e-Business';
                history.replaceState(null, '', '/register');
            } else {
                card.classList.remove('show-register');
                document.title = 'Masuk — Sinergi e-Business';
                history.replaceState(null, '', '/login');
            }

            clearAllErrors();
            hideAllAlerts();
        }

        // ── Password Toggle ──────────────────────────
        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            const eyeOpen = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
            const eyeClosed = `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
            btn.innerHTML = isHidden ? eyeClosed : eyeOpen;
        }

        // ── Error Helpers ────────────────────────────
        function showError(id, msg) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = msg;
            el.classList.add('visible');
            el.style.animation = 'none';
            el.offsetHeight;
            el.style.animation = '';
        }
        function clearError(id) {
            const el = document.getElementById(id);
            if (el) { el.textContent = ''; el.classList.remove('visible'); }
        }
        function clearAllErrors() {
            document.querySelectorAll('.field-error').forEach(el => {
                el.textContent = ''; el.classList.remove('visible');
            });
        }

        // ── Alert Helpers ─────────────────────────────
        function showAlert(id, msg, type = 'error') {
            const el = document.getElementById(id);
            if (!el) return;
            el.className = 'alert-banner ' + (type === 'success' ? 'alert-success' : 'alert-error');
            el.innerHTML = (type === 'success'
                ? `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`
                : `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>`)
                + `<span>${msg}</span>`;
            el.style.display = 'flex';
        }
        function hideAllAlerts() {
            document.querySelectorAll('.alert-banner').forEach(el => el.style.display = 'none');
        }

        // ── Progress Bar ─────────────────────────────
        function startProgress() {
            const bar = document.getElementById('progressBar');
            bar.style.width = '0'; bar.style.transition = 'none';
            bar.offsetHeight;
            bar.style.transition = 'width 2.5s ease'; bar.style.width = '75%';
        }
        function finishProgress() {
            const bar = document.getElementById('progressBar');
            bar.style.transition = 'width 0.3s ease'; bar.style.width = '100%';
            setTimeout(() => { bar.style.width = '0'; bar.style.transition = 'none'; }, 500);
        }

        // ── Button Loading State ─────────────────────
        function setLoading(btn, loading) {
            btn.disabled = loading;
            btn.classList.toggle('loading', loading);
        }

        // ── Ripple Effect ────────────────────────────
        document.querySelectorAll('.btn-submit').forEach(btn => {
            btn.addEventListener('click', function (e) {
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                ripple.style.cssText = `width:${size}px;height:${size}px;top:${y}px;left:${x}px`;
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });

        // ── API Request Helper ───────────────────────
        async function apiPost(endpoint, body) {
            const res = await fetch(`${API}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                },
                body: JSON.stringify(body),
            });
            const data = await res.json();
            return { ok: res.ok, status: res.status, data };
        }

        // ── Save Token & Redirect ────────────────────
        function handleAuthSuccess(data) {
            if (data.data?.token) {
                localStorage.setItem('sinergi_token', data.data.token);
                localStorage.setItem('sinergi_user', JSON.stringify(data.data.user));
            }
            window.location.href = '/dashboard';
        }

        // ══════════════════════════════════════════════
        //   LOGIN FORM SUBMIT
        // ══════════════════════════════════════════════
        document.getElementById('loginForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            clearAllErrors(); hideAllAlerts();
            const username = document.getElementById('login_username').value.trim();
            const password = document.getElementById('login_password').value;
            let valid = true;
            if (!username) { showError('err_login_username', 'Username wajib diisi.'); valid = false; }
            if (!password) { showError('err_login_password', 'Password wajib diisi.'); valid = false; }
            if (!valid) return;

            const btn = document.getElementById('loginBtn');
            setLoading(btn, true); startProgress();
            try {
                const { ok, data } = await apiPost('/login', { username, password });
                if (ok) {
                    showAlert('loginAlert', data.message || 'Login berhasil!', 'success');
                    finishProgress();
                    setTimeout(() => handleAuthSuccess(data), 700);
                } else {
                    finishProgress();
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, msgs]) => showError(`err_login_${field}`, msgs[0]));
                    } else {
                        showAlert('loginAlert', data.message || 'Username atau password salah.', 'error');
                    }
                }
            } catch (err) {
                finishProgress();
                showAlert('loginAlert', 'Terjadi kesalahan. Periksa koneksi internet Anda.', 'error');
            } finally {
                setLoading(btn, false);
            }
        });

        // ══════════════════════════════════════════════
        //   REGISTER FORM SUBMIT
        // ══════════════════════════════════════════════
        document.getElementById('registerForm').addEventListener('submit', async function (e) {
            e.preventDefault();
            clearAllErrors(); hideAllAlerts();
            const name = document.getElementById('reg_name').value.trim();
            const username = document.getElementById('reg_username').value.trim();
            const email = document.getElementById('reg_email').value.trim();
            const password = document.getElementById('reg_password').value;
            const password_confirmation = document.getElementById('reg_password_confirmation').value;
            const address = document.getElementById('reg_address').value.trim();

            let valid = true;
            if (!name) { showError('err_reg_name', 'Nama lengkap wajib diisi.'); valid = false; }
            if (!username) { showError('err_reg_username', 'Username wajib diisi.'); valid = false; }
            if (!email) { showError('err_reg_email', 'Email wajib diisi.'); valid = false; }
            else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError('err_reg_email', 'Format email tidak valid.'); valid = false;
            }
            if (!password) { showError('err_reg_password', 'Password wajib diisi.'); valid = false; }
            else if (password.length < 8) { showError('err_reg_password', 'Password minimal 8 karakter.'); valid = false; }
            if (password !== password_confirmation) {
                showError('err_reg_password_confirmation', 'Konfirmasi password tidak cocok.'); valid = false;
            }
            if (!valid) return;

            const btn = document.getElementById('registerBtn');
            setLoading(btn, true); startProgress();
            try {
                const { ok, data } = await apiPost('/register', {
                    name, username, email, password, password_confirmation,
                    address: address || null,
                });
                if (ok) {
                    showAlert('registerAlert', data.message || 'Registrasi berhasil!', 'success');
                    finishProgress();
                    setTimeout(() => handleAuthSuccess(data), 700);
                } else {
                    finishProgress();
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, msgs]) => showError(`err_reg_${field}`, msgs[0]));
                    } else {
                        showAlert('registerAlert', data.message || 'Terjadi kesalahan saat mendaftar.', 'error');
                    }
                }
            } catch (err) {
                finishProgress();
                showAlert('registerAlert', 'Terjadi kesalahan. Periksa koneksi internet Anda.', 'error');
            } finally {
                setLoading(btn, false);
            }
        });
    </script>

</body>

</html>