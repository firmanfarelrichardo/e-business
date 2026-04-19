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

    <!-- Tailwind CSS (Gunakan Vite di produksi) -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">

    <!-- NAVBAR SECTION -->
    <nav class="bg-slate-900 text-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center gap-2 hover:opacity-90 transition">
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span class="font-bold text-xl tracking-tight">Sinergi<span class="text-amber-500">.</span></span>
                </a>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="#katalog" class="text-slate-300 hover:text-white transition duration-200">Katalog ATK</a>
                    <a href="#jasa-cetak" class="text-slate-300 hover:text-white transition duration-200">Jasa Cetak</a>
                    <a href="#lacak" class="text-slate-300 hover:text-white transition duration-200">Lacak Pesanan</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-slate-300 hover:text-white font-medium">Dashboard</a>
                    @else
                        <a href="{{ url('/login') }}" class="text-slate-300 hover:text-white font-medium transition duration-200">Masuk</a>
                        <a href="{{ url('/register') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2 rounded-lg font-medium transition duration-300 shadow-sm hover:shadow-md">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="text-slate-300 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="relative bg-slate-900 border-t border-slate-800 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 opacity-90"></div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32 flex flex-col lg:flex-row items-center">
            <!-- Hero Content -->
            <div class="w-full lg:w-1/2 text-center lg:text-left">
                <span class="text-amber-500 font-semibold tracking-wider uppercase text-sm mb-4 block">B2B & B2C e-Business Platform</span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    Pusat Solusi <span class="text-amber-500">ATK</span> & <br/> Layanan <span class="text-amber-500">Cetak</span> Anda.
                </h1>
                <p class="text-lg text-slate-300 mb-8 max-w-2xl mx-auto lg:mx-0">
                    Satu platform terintegrasi untuk penuhi semua kebutuhan spesifik kantor, sekolah, dan percetakan dokumen dengan proses cepat, standar kualitas tinggi, dan pembayaran mudah.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#katalog" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3.5 rounded-lg font-semibold text-lg transition duration-300 shadow-lg hover:shadow-amber-500/30 w-full sm:w-auto text-center">
                        Mulai Belanja
                    </a>
                    <a href="#jasa-cetak" class="bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 hover:border-slate-600 px-8 py-3.5 rounded-lg font-semibold text-lg transition duration-300 w-full sm:w-auto text-center">
                        Pesan Jasa Cetak
                    </a>
                </div>
            </div>

            <!-- Hero Image / Illustration -->
            <div class="w-full lg:w-1/2 mt-16 lg:mt-0 relative hidden md:flex justify-end">
                <!-- Abstract visual representation for premium feel -->
                <div class="relative w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl bg-gradient-to-tr from-slate-800 to-slate-700 aspect-video flex flex-col items-center justify-center border border-slate-600 p-8 group">
                    <div class="absolute inset-0 bg-slate-900/20 group-hover:bg-transparent transition duration-500"></div>
                    <div class="flex gap-6 z-10">
                        <svg class="w-16 h-16 text-slate-400 group-hover:text-white transition duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <svg class="w-16 h-16 text-amber-500 group-hover:-translate-y-2 transition transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Wave shape divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="fill-slate-50 w-full xl:h-16" viewBox="0 0 1440 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1440V0c-264.4 34.6-545.4 34.6-809.8 0C401.7 13.5 178.6-8.9 0 3.2V48z"></path>
            </svg>
        </div>
    </header>

    <!-- VALUE PROPOSITION SECTION -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Kenapa Memilih Kami?</h2>
                <p class="text-slate-600 max-w-2xl mx-auto text-lg">Kami mendesain pengalaman belanja Anda bebas dari kerepotan, dengan mengedepankan keamanan, kecepatan, dan kualitas super.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Point 1 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Pembayaran Aman</h3>
                    <p class="text-slate-600 leading-relaxed">Berbagai pilihan metode pembayaran instan dan aman. Kami menggunakan sistem Payment Gateway dengan enkripsi terbaru.</p>
                </div>
                
                <!-- Point 2 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Proses Ekstra Cepat</h3>
                    <p class="text-slate-600 leading-relaxed">Nikmati sistem otomatisasi pemrosesan pesanan dari checkout hingga pengiriman, serta pelayanan operasional tanpa jeda.</p>
                </div>
                
                <!-- Point 3 -->
                <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-3">Kualitas Terjamin</h3>
                    <p class="text-slate-600 leading-relaxed">Dari material kertas, tinta, hingga ragam binder kantor, semua produk kami wajib lulus Quality Control standar tinggi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN SERVICES SECTION -->
    <section class="py-24 bg-white" id="layanan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mb-4">Layanan Unggulan Kami</h2>
                <p class="text-slate-600 max-w-2xl mx-auto text-lg">Platform kami difokuskan pada dua sektor esensial yang berjalan beriringan untuk kelancaran bisnis Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- Katalog ATK Division -->
                <div id="katalog" class="flex flex-col rounded-3xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-2xl hover:border-slate-300 transition-all duration-300">
                    <!-- Media Header -->
                    <div class="h-72 bg-slate-50 relative pt-10 px-8 flex justify-center items-end overflow-hidden">
                        <div class="w-full max-w-sm bg-white rounded-t-xl shadow-lg border-t-4 border-amber-500 h-56 flex items-center justify-center p-6 relative z-10 translate-y-6 group-hover:translate-y-2 transition-transform duration-500">
                            <div class="w-full text-center">
                                <svg class="w-16 h-16 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                <span class="font-bold text-lg text-slate-800">E-Katalog B2B & B2C</span>
                            </div>
                        </div>
                    </div>
                    <!-- Body Content -->
                    <div class="p-8 bg-white flex-grow flex flex-col justify-between">
                        <div>
                            <span class="inline-block bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold tracking-wide mb-4 uppercase">Produk Fisik</span>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">Alat Tulis Kantor (ATK)</h3>
                            <p class="text-slate-600 mb-8 leading-relaxed">Dapatkan semua perlengkapan krusial untuk produktivitas operasional. Kami menyediakan HVS, tinta printer, amplop, map folder, hingga pulpen berkualitas prima dalam harga grosir maupun satuan untuk konsumen individu maupun korporat.</p>
                        </div>
                        <a href="#" class="inline-flex items-center text-amber-600 font-bold hover:text-amber-700 transition">
                            Lihat Katalog Lengkap
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Jasa Percetakan Division -->
                <div id="jasa-cetak" class="flex flex-col rounded-3xl overflow-hidden shadow-sm border border-slate-200 group hover:shadow-2xl hover:border-slate-300 transition-all duration-300">
                    <!-- Media Header -->
                    <div class="h-72 bg-slate-900 relative pt-10 px-8 flex justify-center items-end overflow-hidden">
                        <div class="w-full max-w-sm bg-slate-800 rounded-t-xl shadow-lg border-t-4 border-amber-500 h-56 flex items-center justify-center p-6 relative z-10 translate-y-6 group-hover:translate-y-2 transition-transform duration-500">
                             <div class="w-full text-center">
                                <svg class="w-16 h-16 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span class="font-bold text-lg text-white">Cloud Printing</span>
                            </div>
                        </div>
                    </div>
                    <!-- Body Content -->
                    <div class="p-8 bg-white flex-grow flex flex-col justify-between">
                        <div>
                            <span class="inline-block bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold tracking-wide mb-4 uppercase">Custom Order</span>
                            <h3 class="text-2xl font-bold text-slate-800 mb-4">Layanan Digital Printing</h3>
                            <p class="text-slate-600 mb-8 leading-relaxed">Cetak dokumen tender, laporan tahunan, id card, banner, atau nota pembelian khusus untuk bisnis Anda. Unggah file dokumen Anda darimana saja, pilih konfigurasi cetak dan bahan secara real-time, kami akan proses dan antarkan.</p>
                        </div>
                        <a href="#" class="inline-flex items-center text-amber-600 font-bold hover:text-amber-700 transition">
                            Mulai Pesanan Cetak
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER SECTION -->
    <footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <!-- Branding -->
                <div>
                    <a href="/" class="flex items-center gap-2 mb-6 hover:opacity-90 transition inline-block">
                        <svg class="w-8 h-8 text-amber-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span class="font-bold text-2xl text-white tracking-tight ml-1">Sinergi<span class="text-amber-500">.</span></span>
                    </a>
                    <p class="text-slate-400 leading-relaxed">
                        Mitra terpercaya perusahaan Anda untuk distribusi produk alat tulis kantor dan pemrosesan order percetakan dokumen terbaik se-Indonesia.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Eksplorasi</h4>
                    <ul class="space-y-4">
                        <li><a href="#katalog" class="text-slate-400 hover:text-amber-500 font-medium transition">Katalog Belanja ATK</a></li>
                        <li><a href="#jasa-cetak" class="text-slate-400 hover:text-amber-500 font-medium transition">Layanan Percetakan</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-amber-500 font-medium transition">Promo Spesial</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-amber-500 font-medium transition">Program Affiliate B2B</a></li>
                    </ul>
                </div>
                
                <!-- Support -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Bantuan</h4>
                    <ul class="space-y-4">
                        <li><a href="#lacak" class="text-slate-400 hover:text-amber-500 font-medium transition">Lacak Status Pesanan</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-amber-500 font-medium transition">Panduan Pembayaran</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-amber-500 font-medium transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-amber-500 font-medium transition">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                
                <!-- Contact Details -->
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Hubungi Kami</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-slate-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Gedung Sinergi Lt. 5, Jl. Jendral Sudirman Kav. 22<br/>Jakarta Selatan 12190</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <a href="mailto:support@sinergiatk.co.id" class="hover:text-amber-500 transition">support@sinergiatk.co.id</a>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 8899-7766</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm font-medium text-center md:text-left">
                    &copy; {{ date('Y') }} Sinergi e-Business. All Hak Cipta Dilindungi Undang-Undang.
                </p>
                <!-- Social Icons -->
                <div class="flex space-x-6">
                    <a href="#" class="text-slate-500 hover:text-amber-500 transition">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="text-slate-500 hover:text-amber-500 transition">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
