<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — E-Business</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --sage: #96A78D;
            --sage-light: #B8C9AF;
            --sage-dark: #6B7A63;
            --sage-deeper: #4A5645;
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
            padding: 2rem 1rem;
        }

        body::before {
            content: '';
            position: fixed;
            top: -20%;
            right: -10%;
            width: 55%;
            height: 65%;
            background: radial-gradient(ellipse, rgba(150, 167, 141, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            animation: float1 9s ease-in-out infinite;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -15%;
            left: -5%;
            width: 50%;
            height: 55%;
            background: radial-gradient(ellipse, rgba(74, 86, 69, 0.5) 0%, transparent 70%);
            border-radius: 50%;
            animation: float2 11s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-20px, 25px) scale(1.04); }
        }

        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -20px) scale(1.06); }
        }

        .orb {
            position: fixed;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        .orb-1 { width: 100px; height: 100px; top: 10%; left: 8%; animation: floatOrb 11s ease-in-out infinite; }
        .orb-2 { width: 70px; height: 70px; bottom: 25%; right: 10%; animation: floatOrb 8s ease-in-out infinite reverse; }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-18px); }
        }

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
            max-width: 460px;
            padding: 2.5rem;
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }

        .brand-logo svg {
            width: 26px;
            height: 26px;
            fill: white;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.65rem;
            color: white;
            text-align: center;
            margin-bottom: 0.3rem;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .subtitle {
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.72);
            margin-bottom: 1.75rem;
            font-weight: 300;
        }

        /* Two-column grid for some fields */
        .input-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.85rem;
        }

        .input-group {
            margin-bottom: 0.9rem;
        }

        .input-group.full { grid-column: 1 / -1; }

        .input-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 0.35rem;
            letter-spacing: 0.03em;
        }

        .input-wrapper { position: relative; }

        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            opacity: 0.55;
            pointer-events: none;
        }

        .glass-input {
            width: 100%;
            padding: 0.7rem 0.9rem 0.7rem 2.6rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 11px;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            transition: all 0.25s ease;
            outline: none;
        }

        .glass-input::placeholder { color: rgba(255, 255, 255, 0.4); }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.55);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.08);
        }

        .glass-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 100px rgba(150, 167, 141, 0.3) inset;
            -webkit-text-fill-color: white;
        }

        /* Select input */
        .glass-select {
            width: 100%;
            padding: 0.7rem 0.9rem 0.7rem 2.6rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 11px;
            color: white;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.875rem;
            transition: all 0.25s ease;
            outline: none;
            appearance: none;
            cursor: pointer;
        }

        .glass-select option {
            background: var(--sage-deeper);
            color: white;
        }

        .glass-select:focus {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.55);
        }

        /* Select arrow */
        .select-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            opacity: 0.6;
        }

        /* Password strength indicator */
        .password-strength {
            display: flex;
            gap: 4px;
            margin-top: 6px;
        }

        .strength-bar {
            height: 3px;
            flex: 1;
            border-radius: 2px;
            background: rgba(255,255,255,0.15);
            transition: background 0.3s;
        }

        .strength-bar.weak   { background: rgba(220, 100, 100, 0.7); }
        .strength-bar.medium { background: rgba(220, 180, 80, 0.7); }
        .strength-bar.strong { background: rgba(120, 200, 120, 0.7); }

        .strength-text {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.55);
            margin-top: 3px;
        }

        /* Error state */
        .glass-input.error {
            border-color: rgba(255, 120, 120, 0.6);
        }

        .field-error {
            font-size: 0.72rem;
            color: rgba(255, 180, 180, 0.9);
            margin-top: 4px;
        }

        /* Alert */
        .alert-error {
            background: rgba(220, 80, 80, 0.2);
            border: 1px solid rgba(255, 120, 120, 0.4);
            border-radius: 10px;
            padding: 0.7rem 0.9rem;
            margin-bottom: 1rem;
            font-size: 0.82rem;
            color: rgba(255, 210, 210, 0.95);
        }

        /* Terms */
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin: 1rem 0 1.25rem;
        }

        .terms-row input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            min-width: 16px;
            border: 1px solid rgba(255,255,255,0.4);
            border-radius: 4px;
            background: rgba(255,255,255,0.1);
            margin-top: 1px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
        }

        .terms-row input[type="checkbox"]:checked {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.65);
        }

        .terms-row input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: -1px;
            left: 2px;
            font-size: 11px;
            color: white;
            font-weight: 600;
        }

        .terms-text {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.72);
            line-height: 1.5;
        }

        .terms-text a {
            color: white;
            text-decoration: underline;
        }

        /* Submit button */
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
        }

        .btn-primary:hover {
            background: white;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(74, 86, 69, 0.3);
        }

        .btn-primary:active { transform: translateY(0); box-shadow: none; }

        .card-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.82rem;
            color: rgba(255, 255, 255, 0.68);
        }

        .card-footer a {
            color: white;
            font-weight: 500;
            text-decoration: none;
        }

        .card-footer a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="glass-card">

        <div class="brand-logo">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                <path d="M12 2L8 6h8l-4-4zm0 20l4-4H8l4 4z" opacity=".5"/>
            </svg>
        </div>

        <h1>Buat Akun Baru</h1>
        <p class="subtitle">Bergabunglah dengan E-Business kami</p>

        {{-- Validation errors --}}
        @if ($errors->any())
        <div class="alert-error">
            <ul style="list-style: none; margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="input-grid">

                <!-- Nama Depan -->
                <div class="input-group">
                    <label class="input-label" for="first_name">Nama Depan</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="glass-input @error('first_name') error @enderror"
                            placeholder="Nama"
                            value="{{ old('first_name') }}"
                            required
                        >
                    </div>
                    @error('first_name')
                    <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Belakang -->
                <div class="input-group">
                    <label class="input-label" for="last_name">Nama Belakang</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="glass-input"
                            placeholder="Belakang"
                            value="{{ old('last_name') }}"
                        >
                    </div>
                </div>

                <!-- Email (full width) -->
                <div class="input-group full">
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
                            class="glass-input @error('email') error @enderror"
                            placeholder="nama@email.com"
                            value="{{ old('email') }}"
                            required
                        >
                    </div>
                    @error('email')
                    <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No. Telepon -->
                <div class="input-group">
                    <label class="input-label" for="phone">No. Telepon</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 8.81 19.79 19.79 0 01.21 2.18 2 2 0 012.18 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 7.09a16 16 0 006 6l.56-.56a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92v2z"/>
                        </svg>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="glass-input @error('phone') error @enderror"
                            placeholder="08xx-xxxx-xxxx"
                            value="{{ old('phone') }}"
                        >
                    </div>
                </div>

                <!-- Role -->
                <div class="input-group">
                    <label class="input-label" for="role">Role</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <select id="role" name="role" class="glass-select @error('role') error @enderror">
                            <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                            <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Karyawan</option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Pemilik</option>
                        </select>
                        <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                </div>

                <!-- Password (full width) -->
                <div class="input-group full">
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
                            class="glass-input @error('password') error @enderror"
                            placeholder="Min. 8 karakter"
                            required
                            oninput="checkStrength(this.value)"
                        >
                    </div>
                    <div class="password-strength" id="strengthBars">
                        <div class="strength-bar" id="b1"></div>
                        <div class="strength-bar" id="b2"></div>
                        <div class="strength-bar" id="b3"></div>
                        <div class="strength-bar" id="b4"></div>
                    </div>
                    <p class="strength-text" id="strengthText"></p>
                    @error('password')
                    <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password (full width) -->
                <div class="input-group full">
                    <label class="input-label" for="password_confirmation">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="glass-input"
                            placeholder="Ulangi password"
                            required
                        >
                    </div>
                </div>

            </div>{{-- end input-grid --}}

            <!-- Terms -->
            <div class="terms-row">
                <input type="checkbox" id="terms" name="terms" required>
                <label class="terms-text" for="terms">
                    Saya menyetujui <a href="#">Syarat & Ketentuan</a> serta <a href="#">Kebijakan Privasi</a> yang berlaku
                </label>
            </div>

            <button type="submit" class="btn-primary">
                Buat Akun
            </button>
        </form>

        <div class="card-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>

    </div>

    <script>
        function checkStrength(val) {
            const bars = [
                document.getElementById('b1'),
                document.getElementById('b2'),
                document.getElementById('b3'),
                document.getElementById('b4'),
            ];
            const text = document.getElementById('strengthText');

            bars.forEach(b => { b.className = 'strength-bar'; });

            if (!val) { text.textContent = ''; return; }

            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = ['weak','weak','medium','strong','strong'];
            const labels = ['','Lemah','Sedang','Kuat','Sangat Kuat'];

            for (let i = 0; i < score; i++) {
                bars[i].classList.add(levels[score]);
            }
            text.textContent = labels[score];
        }
    </script>

</body>
</html>