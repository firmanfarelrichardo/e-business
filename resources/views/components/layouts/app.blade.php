<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sinergi - Pusat ATK & Jasa Percetakan' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN Fallback for styling (Since we are using CDN in welcome.blade.php too) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            primary: '#96A78D',
                            secondary: '#B6CEB4',
                            tertiary: '#D9E9CF',
                            light: '#F0F0F0',
                            dark: '#5A6852',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Base Styles */
        body { font-family: 'Inter', sans-serif; background-color: #F0F0F0; }
        
        /* Custom Glass utilities */
        .glass-panel {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .glass-card-dark {
            background: rgba(90, 104, 82, 0.6);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }

        /* Abstract Blob Animations */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.6;
            animation: move 10s infinite alternate;
        }

        @keyframes move {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(20px, -30px) scale(1.1); }
            100% { transform: translate(-20px, 20px) scale(0.9); }
        }

        /* Hide scrollbar for category navs */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="text-slate-800 antialiased flex flex-col min-h-screen relative overflow-x-hidden">

    <!-- Navbar -->
    <div class="fixed w-full z-50 top-0 px-0 sm:top-4 sm:px-4">
        <nav class="max-w-7xl mx-auto glass-panel sm:rounded-full px-6 py-3 flex justify-between items-center transition-all duration-300">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group z-10">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-primary to-brand-secondary flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="font-bold text-xl tracking-tight text-brand-dark group-hover:text-brand-primary transition">Sinergi.</span>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-8 items-center z-10">
                <a href="{{ url('/katalog') }}" class="text-slate-700 hover:text-brand-primary text-sm font-semibold transition duration-200">Katalog ATK</a>
                <a href="{{ url('/jasa') }}" class="text-slate-700 hover:text-brand-primary text-sm font-semibold transition duration-200">Jasa Cetak</a>
                <a href="{{ url('/not-configured') }}" class="text-slate-700 hover:text-brand-primary text-sm font-semibold transition duration-200">Lacak Pesanan</a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-4 z-10">
                <!-- Cart Icon -->
                <a href="{{ url('/keranjang') }}" class="relative p-2 text-slate-700 hover:text-brand-primary transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <!-- Cart Badge -->
                    <span class="absolute top-0 right-0 bg-brand-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white">0</span>
                </a>

                @auth
                    <a href="{{ url('/not-configured') }}" class="hidden md:block text-slate-700 hover:text-brand-primary font-medium px-2 text-sm">Dashboard</a>
                @else
                    <a href="{{ url('/not-configured') }}" class="hidden md:block text-slate-700 hover:text-brand-primary text-sm font-medium transition duration-200 px-2">Masuk</a>
                @endauth

                <!-- Mobile menu button -->
                <button class="md:hidden text-slate-700 hover:text-brand-primary focus:outline-none p-1">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow pt-20 sm:pt-28 pb-12 relative z-10">
        <!-- Background Blobs for all pages -->
        <div class="blob bg-brand-tertiary w-[30rem] h-[30rem] top-20 -left-20 mix-blend-multiply opacity-50 -z-10"></div>
        <div class="blob bg-brand-secondary w-[20rem] h-[20rem] bottom-10 right-10 mix-blend-multiply opacity-40 -z-10" style="animation-delay: -5s;"></div>
        
        {{ $slot }}
    </main>

    <!-- FOOTER SECTION with Glass Style -->
    <footer class="relative z-10 bg-brand-dark border-t border-brand-primary/30 text-brand-tertiary overflow-hidden mt-auto">
        <!-- Background elements for footer -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-primary/10 rounded-full blur-3xl point-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-8">
                <!-- Branding -->
                <div class="lg:col-span-1 border-r border-white/10 pr-6">
                    <a href="/" class="flex items-center gap-2 mb-4 group inline-flex">
                        <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center border border-white/20 group-hover:bg-brand-primary transition">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="font-bold text-xl text-white tracking-tight">Sinergi.</span>
                    </a>
                    <p class="text-white/60 leading-relaxed font-light text-sm">
                        Distributor terdepan piranti kantor dan jasa percetakan andal untuk ekosistem kerja profesional yang efisien.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Eksplorasi</h4>
                    <ul class="space-y-2 font-light text-sm">
                        <li><a href="{{ url('/katalog') }}" class="text-white/60 hover:text-white transition">Katalog Belanja ATK</a></li>
                        <li><a href="{{ url('/jasa') }}" class="text-white/60 hover:text-white transition">Layanan Percetakan</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Bantuan</h4>
                    <ul class="space-y-2 font-light text-sm">
                        <li><a href="{{ url('/not-configured') }}" class="text-white/60 hover:text-white transition">Lacak Pengiriman</a></li>
                        <li><a href="{{ url('/not-configured') }}" class="text-white/60 hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                
                <!-- Contact Details -->
                <div>
                    <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Hubungi Kami</h4>
                    <ul class="space-y-3 font-light text-white/60 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span>Gedung Sinergi Lt. 5, Jakarta</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-white/40 text-xs font-light text-center md:text-left">
                    &copy; {{ date('Y') }} Sinergi e-Business. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
