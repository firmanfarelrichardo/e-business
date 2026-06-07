<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sinergi - Pusat ATK & Jasa Percetakan</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN Fallback for styling -->
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
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .glass-card-dark {
            background: rgba(90, 104, 82, 0.4);
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
    </style>
</head>
<body class="text-slate-800 antialiased flex flex-col min-h-screen relative overflow-x-hidden">

    <!-- FLOATING NAVBAR (Match Reference Image) -->
    <div class="fixed w-full z-50 top-6 px-4">
        <nav class="max-w-5xl mx-auto glass-panel rounded-full px-6 py-3 flex justify-between items-center transition-all duration-300">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-3 group z-10">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-primary to-brand-secondary flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="font-bold text-xl tracking-tight text-white group-hover:text-brand-tertiary transition">Sinergi.</span>
            </a>
            
            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-8 items-center z-10">
                <a href="#katalog" class="text-white/90 hover:text-white text-sm font-medium transition duration-200">Katalog ATK</a>
                <a href="#jasa-cetak" class="text-white/90 hover:text-white text-sm font-medium transition duration-200">Jasa Cetak</a>
                <a href="#lacak" class="text-white/90 hover:text-white text-sm font-medium transition duration-200">Lacak Pesanan</a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-3 z-10">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-white hover:text-brand-tertiary font-medium px-4">Dashboard</a>
                @else
                    <a href="{{ url('/login') }}" class="text-white hover:text-brand-tertiary text-sm font-medium transition duration-200 px-4">Masuk</a>
                    <a href="{{ url('/register') }}" class="bg-gradient-to-r from-brand-primary to-brand-secondary hover:from-brand-secondary hover:to-brand-primary text-brand-dark px-6 py-2.5 rounded-full text-sm font-bold transition duration-300 shadow-lg transform hover:-translate-y-0.5">Daftar Sekarang &rarr;</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center z-10">
                <button class="text-white hover:text-brand-tertiary focus:outline-none p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </nav>
    </div>

    <!-- HERO SECTION (Dark Background with Glass effect matching reference) -->
    <header class="relative min-h-[90vh] flex items-center pt-24 overflow-hidden bg-brand-dark">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80" alt="Office Background" class="w-full h-full object-cover opacity-30 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-dark/95 via-brand-dark/80 to-[#1a2316]/90"></div>
        </div>

        <!-- Colorful Blobs behind glass -->
        <div class="blob bg-brand-primary w-[30rem] h-[30rem] top-20 -left-20 mix-blend-screen opacity-50"></div>
        <div class="blob bg-brand-tertiary w-[20rem] h-[20rem] bottom-10 right-10 mix-blend-screen opacity-40" style="animation-delay: -5s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <!-- Text Content -->
                <div class="w-full lg:w-3/5 text-center lg:text-left mt-10 lg:mt-0">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-tight mb-6 tracking-tight">
                        Solusi <span class="text-brand-secondary">ATK</span> & <br/> Layanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-tertiary">Cetak</span> Terpadu.
                    </h1>
                    <p class="text-lg md:text-xl text-white/80 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                        Platform e-Business terintegrasi untuk penuhi kebutuhan operasional kantor Anda. Kualitas premium, diproses dengan cepat & praktis.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#katalog" class="bg-gradient-to-r from-brand-primary to-brand-secondary text-brand-dark px-8 py-3.5 rounded-full font-bold text-lg shadow-lg hover:shadow-brand-primary/50 transform hover:-translate-y-1 transition duration-300 w-full sm:w-auto text-center flex items-center justify-center gap-2">
                            Mulai Belanja
                        </a>
                        <a href="#jasa-cetak" class="glass-panel text-white hover:bg-white/20 px-8 py-3.5 rounded-full font-semibold text-lg transition duration-300 w-full sm:w-auto text-center">
                            Pesan Jasa Cetak
                        </a>
                    </div>
                </div>
                
                <!-- Glass Decorative Elements (Right Side) -->
                <div class="w-full lg:w-2/5 hidden md:block">
                    <div class="relative w-full max-w-md mx-auto">
                        <!-- Floating Glass Card 1 -->
                        <div class="glass-card-dark rounded-2xl p-6 mb-6 transform -rotate-2 hover:rotate-0 transition duration-500 relative z-20">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full bg-brand-primary/30 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-brand-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-white font-bold">100% Pembayaran Aman</h4>
                                    <p class="text-white/60 text-sm">Transaksi terenkripsi mutakhir</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Glass Card 2 -->
                        <div class="glass-card-dark rounded-2xl p-6 transform translate-x-8 rotate-3 hover:translate-x-4 hover:rotate-0 transition duration-500 relative z-10 backdrop-blur-xl">
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-white font-medium">Kualitas Premium</span>
                                    <div class="flex gap-1">
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    </div>
                                </div>
                                <div class="w-full bg-white/20 h-2 rounded-full overflow-hidden">
                                    <div class="bg-gradient-to-r from-brand-secondary to-brand-primary w-11/12 h-full rounded-full"></div>
                                </div>
                            </div>
                            <div class="flex -space-x-3">
                                <img class="w-10 h-10 rounded-full border-2 border-brand-dark" src="https://i.pravatar.cc/100?img=1" alt="User 1">
                                <img class="w-10 h-10 rounded-full border-2 border-brand-dark" src="https://i.pravatar.cc/100?img=2" alt="User 2">
                                <img class="w-10 h-10 rounded-full border-2 border-brand-dark" src="https://i.pravatar.cc/100?img=3" alt="User 3">
                                <div class="w-10 h-10 rounded-full border-2 border-brand-dark bg-brand-primary flex items-center justify-center text-xs font-bold text-white">+5k</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENT SECTION (Lighter background for services) -->
    <main class="relative bg-brand-light pb-24 pt-16">
        <!-- Blobs for lower section -->
        <div class="blob bg-brand-secondary w-[40rem] h-[40rem] -top-40 right-0 mix-blend-multiply opacity-40"></div>
        <div class="blob bg-brand-primary w-[30rem] h-[30rem] bottom-0 -left-20 mix-blend-multiply opacity-20"></div>

        <!-- ABOUT / FEATURES SECTION -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mb-24">
            <div class="text-center mb-16">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-primary/10 text-brand-dark font-semibold text-sm tracking-widest uppercase mb-4 shadow-sm border border-brand-primary/20">Standar Industri</span>
                <h2 class="text-3xl md:text-5xl font-bold text-slate-800 mb-6">Kenapa Memilih Kami?</h2>
                <p class="text-slate-600 max-w-2xl mx-auto text-lg font-light">Kami menggabungkan efisiensi digital dengan kualitas fisik, menciptakan alur kerja yang mulus bagi perusahaan Anda.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature Card 1 -->
                <div class="glass-card rounded-[2rem] p-8 flex flex-col items-start hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-primary to-brand-secondary rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Keamanan Terjamin</h3>
                    <p class="text-slate-600 leading-relaxed font-light text-sm">Setiap transaksi dan file dokumen yang Anda unggah dilindungi oleh sistem enkripsi tingkat industri.</p>
                </div>
                
                <!-- Feature Card 2 -->
                <div class="glass-card rounded-[2rem] p-8 flex flex-col items-start hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-dark to-brand-primary rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Penyelesaian Cepat</h3>
                    <p class="text-slate-600 leading-relaxed font-light text-sm">Otomatisasi pesanan membuat waktu produksi dan pengiriman menjadi jauh lebih responsif dan efisien.</p>
                </div>
                
                <!-- Feature Card 3 -->
                <div class="glass-card rounded-[2rem] p-8 flex flex-col items-start hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-secondary to-brand-primary rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-brand-dark mb-3">Hasil Premium</h3>
                    <p class="text-slate-600 leading-relaxed font-light text-sm">Pengontrolan kualitas ketat (QC) di setiap lini mematikan hasil cetakan presisi yang memuaskan.</p>
                </div>
            </div>
        </div>

        <!-- SERVICES SECTION -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" id="layanan">
            <div class="glass-card rounded-[3rem] overflow-hidden p-2 shadow-2xl backdrop-blur-2xl border border-white/60">
                <div class="bg-white/40 rounded-[2.5rem] p-8 md:p-12 lg:p-16">
                    <div class="flex flex-col lg:flex-row gap-16 items-center">
                        <div class="w-full lg:w-1/2">
                            <span class="inline-block py-1 px-3 rounded-full bg-brand-secondary/20 text-brand-dark font-semibold text-sm tracking-widest uppercase mb-4 border border-brand-secondary/30">Hub Utama</span>
                            <h2 class="text-4xl lg:text-5xl font-bold text-brand-dark mb-6 leading-tight">Layanan<br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-primary to-brand-dark">Unggulan Kami.</span></h2>
                            <p class="text-slate-600 mb-8 font-light text-lg">Platform ini direkayasa khusus supaya keperluan operasional Anda dapat terpusat pada satu pintu dengan akses gampang.</p>
                            
                            <div class="space-y-6">
                                <!-- Service item 1 -->
                                <a href="#katalog" class="group block p-6 bg-white/50 hover:bg-white/80 rounded-2xl border border-white transition-all shadow-sm hover:shadow-md">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-full bg-brand-primary/20 flex items-center justify-center group-hover:bg-brand-primary group-hover:text-white text-brand-dark transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-slate-800 mb-1">Katalog Alat Tulis Kantor</h4>
                                            <p class="text-sm text-slate-500">Persediaan pulpen, map, hingga kertas folio.</p>
                                        </div>
                                    </div>
                                </a>
                                
                                <!-- Service item 2 -->
                                <a href="#jasa-cetak" class="group block p-6 bg-white/50 hover:bg-white/80 rounded-2xl border border-white transition-all shadow-sm hover:shadow-md">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-full bg-brand-secondary/30 flex items-center justify-center group-hover:bg-brand-dark group-hover:text-white text-brand-dark transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-slate-800 mb-1">Layanan Cetak Digital</h4>
                                            <p class="text-sm text-slate-500">Banner, dokumen berbendel, kartu nama bisnis.</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="w-full lg:w-1/2 relative">
                            <!-- Composition of Abstract Glass Images -->
                            <div class="relative w-full aspect-square md:aspect-auto md:h-[500px] flex items-center justify-center">
                                <div class="absolute inset-0 bg-gradient-to-tr from-brand-primary/20 to-brand-tertiary/40 rounded-[2rem] transform rotate-3"></div>
                                <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80" alt="Workspace" class="relative z-10 w-[85%] h-[85%] object-cover rounded-[2rem] shadow-2xl border border-white/50">
                                <!-- Floating small element -->
                                <div class="absolute -bottom-6 -left-6 z-20 glass-card p-4 rounded-xl flex items-center gap-3 backdrop-blur-xl">
                                    <div class="w-3 h-3 bg-green-500 rounded-full animate-bounce"></div>
                                    <span class="font-bold text-brand-dark text-sm">Sistem Cetak Online</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER SECTION with Glass Style -->
    <footer class="relative z-10 bg-brand-dark border-t border-brand-primary/30 text-brand-tertiary overflow-hidden">
        <!-- Background elements for footer -->
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-brand-primary/10 rounded-full blur-3xl point-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Branding -->
                <div class="lg:col-span-1 border-r border-white/10 pr-6">
                    <a href="/" class="flex items-center gap-2 mb-6 group inline-flex">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center border border-white/20 group-hover:bg-brand-primary transition">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="font-bold text-2xl text-white tracking-tight">Sinergi.</span>
                    </a>
                    <p class="text-white/60 leading-relaxed font-light text-sm">
                        Distributor terdepan piranti kantor dan jasa percetakan andal untuk ekosistem kerja profesional yang efisien.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Eksplorasi</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="#katalog" class="text-white/60 hover:text-white transition">Katalog Belanja ATK</a></li>
                        <li><a href="#jasa-cetak" class="text-white/60 hover:text-white transition">Layanan Percetakan</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition">Promo Spesial</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition">Kemitraan B2B</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Bantuan</h4>
                    <ul class="space-y-3 font-light text-sm">
                        <li><a href="#lacak" class="text-white/60 hover:text-white transition">Lacak Pengiriman</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition">Petunjuk Pembayaran</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-white/60 hover:text-white transition">Privasi & Keamanan</a></li>
                    </ul>
                </div>
                
                <!-- Contact Details -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Hubungi Kami</h4>
                    <ul class="space-y-4 font-light text-white/60 text-sm">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-brand-secondary shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span>Gedung Sinergi Lt. 5, Jl. Jendral Sudirman Kav. 22 Jakarta 12190</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-brand-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:support@sinergiatk.co.id" class="hover:text-white transition">support@sinergiatk.co.id</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-brand-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 8899-7766</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-white/40 text-xs font-light text-center md:text-left">
                    &copy; {{ date('Y') }} Sinergi e-Business. Hak Cipta Dilindungi.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white/50 hover:text-white hover:bg-white/20 transition">
                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
