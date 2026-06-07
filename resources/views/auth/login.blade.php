<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Photocopy & ATK</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --sage: #96A78D;
            --sage-light: #B8C9AF;
            --sage-dark: #6B7A63;
            --sage-deeper: #4A5645;
            --cream: #F5F2EC;
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4A5645 0%, #6B7A63 30%, #96A78D 65%, #B8C9AF 100%);
            overflow: hidden;
            position: relative;
        }

        /* Organic blobs background */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            left: -10%;
            width: 60%;
            height: 70%;
            background: radial-gradient(ellipse, rgba(150, 167, 141, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            animation: float1 8s ease-in-out infinite;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -15%;
            right: -5%;
            width: 50%;
            height: 60%;
            background: radial-gradient(ellipse, rgba(74, 86, 69, 0.5) 0%, transparent 70%);
            border-radius: 50%;
            animation: float2 10s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, 30px) scale(1.05); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-25px, -20px) scale(1.08); }
        }

        /* Extra floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(2px);
            pointer-events: none;
        }

        .orb-1 { width: 120px; height: 120px; top: 15%; right: 20%; animation: floatOrb 12s ease-in-out infinite; }
        .orb-2 { width: 60px;  height: 60px;  top: 65%; left: 12%; animation: floatOrb 9s ease-in-out infinite reverse; }
        .orb-3 { width: 90px;  height: 90px;  bottom: 20%; right: 35%; animation: floatOrb 11s ease-in-out infinite 2s; }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Card glassmorphism */
        .glass-card {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 24px;
            box-shadow:
                0 8px 32px rgba(74, 86, 69, 0.25),
                0 2px 8px rgba(0, 0, 0, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo / brand */
        .brand-logo {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            backdrop-filter: blur(10px);
        }

        .brand-logo svg {
            width: 28px;
            height: 28px;
            fill: white;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.75rem;
            color: white;
            text-align: center;
            margin-bottom: 0.35rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .subtitle {
            text-align: center;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.75);
            margin-bottom: 2rem;
            font-weight: 300;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.25);
        }

        .divider span {
            font-size: 0.75rem;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.05em;
        }

        /* Form inputs */
        .input-group {
            margin-bottom: 1rem;
            position: relative;
        }

        .input-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 0.4rem;
            letter-spacing: 0.03em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            opacity: 0.6;
            pointer-events: none;
        }

        .glass-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 400;
            transition: all 0.25s ease;
            outline: none;
            backdrop-filter: blur(4px);
        }

        .glass-input::placeholder { color: rgba(255, 255, 255, 0.45); }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.55);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }

        .glass-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px rgba(150, 167, 141, 0.3) inset;
            -webkit-text-fill-color: white;
        }

        /* Checkbox */
        .checkbox-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            margin-top: 0.25rem;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
        }

        .checkbox-label input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid rgba(255,255,255,0.45);
            border-radius: 4px;
            background: rgba(255,255,255,0.1);
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .checkbox-label input[type="checkbox"]:checked {
            background: rgba(255,255,255,0.35);
            border-color: rgba(255,255,255,0.7);
        }

        .checkbox-label input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: -1px;
            left: 2px;
            font-size: 11px;
            color: white;
            font-weight: 600;
        }

        .forgot-link {
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover { color: white; text-decoration: underline; }

        /* Primary button */
        .btn-primary {
            width: 100%;
            padding: 0.85rem;
            background: rgba(255, 255, 255, 0.9);
            color: var(--sage-deeper);
            border: none;
            border-radius: 12px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            letter-spacing: 0.02em;
            position: relative;
            overflow: hidden;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0);
            transition: background 0.2s;
        }

        .btn-primary:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(74, 86, 69, 0.3);
        }

        .btn-primary:active { transform: translateY(0); box-shadow: none; }

        /* Footer link */
        .card-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.83rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .card-footer a {
            color: white;
            font-weight: 500;
            text-decoration: none;
        }

        .card-footer a:hover { text-decoration: underline; }

        /* Error/Alert */
        .alert-error {
            background: rgba(220, 80, 80, 0.2);
            border: 1px solid rgba(255, 120, 120, 0.4);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 0.83rem;
            color: rgba(255, 220, 220, 0.95);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Role badge at top */
        .role-tabs {
            display: flex;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            padding: 3px;
            margin-bottom: 1.5rem;
            gap: 3px;
        }

        .role-tab {
            flex: 1;
            padding: 0.45rem;
            border-radius: 7px;
            border: none;
            background: transparent;
            color: rgba(255,255,255,0.65);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.78rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .role-tab.active {
            background: rgba(255,255,255,0.22);
            color: white;
        }
    </style>
</head>
<body>

    <!-- Floating orbs -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="glass-card">

        <!-- Logo -->
        <div class="brand-logo">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                <path d="M12 2L8 6h8l-4-4zm0 20l4-4H8l4 4z" opacity=".5"/>
            </svg>
        </div>

        <h1>Selamat Datang</h1>
        <p class="subtitle">Masuk ke sistem E-Business Anda</p>

        {{-- Role tabs (opsional, bisa dihapus jika tidak perlu) --}}
        {{-- <div class="role-tabs">
            <button class="role-tab active">Member</button>
            <button class="role-tab">Karyawan</button>
            <button class="role-tab">Pemilik</button>
        </div> --}}

        {{-- Session errors --}}
        @if ($errors->any())
        <div class="alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        @if (session('error'))
        <div class="alert-error">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="input-group">
                <label class="input-label" for="email">Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="glass-input"
                        placeholder="nama@email.com"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>
            </div>

            <!-- Password -->
            <div class="input-group">
                <label class="input-label" for="password">Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="glass-input"
                        placeholder="••••••••"
                        required
                    >
                </div>
            </div>

            <!-- Remember me + Forgot -->
            <div class="checkbox-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                {{-- <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a> --}}
                <a href="#" class="forgot-link">Lupa password?</a>
            </div>

            <!-- Submit -->
            <button type="submit" class="btn-primary">
                Masuk
            </button>
        </form>

        <div class="card-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>

    </div>

</body>
</html>