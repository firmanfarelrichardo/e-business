<x-layouts.app title="Sinergi — Pusat ATK & Jasa Percetakan Terpadu">

    {{-- ════════════════════════════════════════════════════════
         HERO SECTION
    ════════════════════════════════════════════════════════ --}}
    <section class="relative min-h-[90vh] flex items-center pt-8 pb-16 overflow-hidden -mt-20 sm:-mt-28"
             data-testid="hero-section">
        <style>
            .hero-bg-dark { opacity: 0; transition: opacity 0.5s; }
            [data-theme="dark"] .hero-bg-dark { opacity: 1; }
            [data-theme="dark"] .hero-bg-light { opacity: 0; }
        </style>
        {{-- Sky gradient background --}}
        <div class="absolute inset-0 -z-20 gradient-glacier hero-bg-light transition-opacity duration-500"></div>
        <div class="absolute inset-0 -z-20 gradient-deep-space hero-bg-dark"></div>

        {{-- Star field (dark mode only) --}}
        <div class="star-field -z-10"></div>

        {{-- Aurora ribbon (dark mode, subtle) --}}
        <div class="aurora-ribbon -z-10"></div>

        {{-- Drifting blobs --}}
        <div class="blob bg-[var(--accent-cyan)] w-[30rem] h-[30rem] top-10 -left-20 -z-10 opacity-40" style="animation-duration: 18s;"></div>
        <div class="blob bg-[var(--accent-violet)] w-[24rem] h-[24rem] bottom-10 right-0 -z-10 opacity-30" style="animation-delay: -5s; animation-duration: 16s;"></div>
        <div class="blob bg-[var(--gold-300)] w-[18rem] h-[18rem] top-1/3 right-1/4 -z-10 opacity-25" style="animation-delay: -8s; animation-duration: 20s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full pt-20 sm:pt-28">
            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                {{-- Text Content --}}
                <div class="w-full lg:w-3/5 text-center lg:text-left mt-6 lg:mt-0">
                    <h1 class="font-display font-extrabold text-[var(--color-text)] leading-[1.05] mb-6 tracking-tight"
                        style="font-size: clamp(2.75rem, 4vw + 1rem, 4.5rem);">
                        <span data-scramble>Solusi </span><span data-scramble class="text-[var(--color-primary)]">ATK</span><span data-scramble> & </span><br />
                        <span data-scramble>Layanan </span><span data-scramble class="text-transparent bg-clip-text" style="background-image: var(--gradient-aurora); -webkit-background-clip: text;">Cetak</span> <span data-scramble>Terpadu.</span>
                    </h1>
                    <p class="text-[var(--color-text-muted)] mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light"
                       style="font-size: clamp(1rem, 1.5vw, 1.125rem);">
                        <span data-scramble>Platform e-Business terintegrasi untuk penuhi kebutuhan operasional kantor Anda. Kualitas premium, diproses dengan cepat & praktis.</span>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <x-ui.button variant="aurora" size="pill" as="a" href="{{ url('/katalog') }}" data-testid="hero-cta-belanja">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Mulai Belanja
                        </x-ui.button>
                        <x-ui.button variant="glass" size="pill" as="a" href="{{ url('/jasa') }}" data-testid="hero-cta-jasa">
                            Pesan Jasa Cetak
                        </x-ui.button>
                    </div>
                </div>

                {{-- Glass Decorative Elements (Right Side) --}}
                <div class="w-full lg:w-2/5 hidden md:block">
                    <div class="relative w-full max-w-md mx-auto">
                        {{-- Floating Glass Card 1 — Security --}}
                        <div class="glass-card rounded-[var(--radius-xl)] p-6 mb-6 transform -rotate-2 hover:rotate-0 transition duration-500 relative z-20"
                             data-testid="hero-card-security">
                            <div class="flex items-center gap-4 mb-2">
                                <div class="w-12 h-12 rounded-[var(--radius-md)] flex items-center justify-center shadow-lg"
                                     style="background: linear-gradient(135deg, var(--accent-teal), var(--accent-mint));">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-[var(--color-text)] font-bold font-display">100% Pembayaran Aman</h4>
                                    <p class="text-[var(--color-text-muted)] text-sm">Transaksi terenkripsi mutakhir</p>
                                </div>
                            </div>
                        </div>

                        {{-- Floating Glass Card 2 — Rating --}}
                        <div class="glass-card rounded-[var(--radius-xl)] p-6 transform translate-x-8 rotate-3 hover:translate-x-4 hover:rotate-0 transition duration-500 relative z-10 mb-6"
                             data-testid="hero-card-rating">
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-[var(--color-text)] font-medium font-display">Kualitas Premium</span>
                                    <div class="flex gap-1">
                                        @for($i = 0; $i < 5; $i++)
                                        <svg class="w-4 h-4 text-[var(--accent-amber)]" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        @endfor
                                    </div>
                                </div>
                                <div class="w-full h-2 rounded-full overflow-hidden" style="background: var(--color-bg-sunken);">
                                    <div class="w-11/12 h-full rounded-full" style="background: var(--gradient-aurora);"></div>
                                </div>
                            </div>
                            <div class="flex -space-x-3">
                                <img class="w-10 h-10 rounded-full border-2 border-[var(--color-bg)]"
                                    src="https://i.pravatar.cc/100?img=1" alt="User 1">
                                <img class="w-10 h-10 rounded-full border-2 border-[var(--color-bg)]"
                                    src="https://i.pravatar.cc/100?img=2" alt="User 2">
                                <img class="w-10 h-10 rounded-full border-2 border-[var(--color-bg)]"
                                    src="https://i.pravatar.cc/100?img=3" alt="User 3">
                                <div class="w-10 h-10 rounded-full border-2 border-[var(--color-bg)] flex items-center justify-center text-xs font-bold text-white"
                                     style="background: var(--gradient-gold-day);">
                                    +5k</div>
                            </div>
                        </div>

                        {{-- Floating Glass Card 3 — Pesan Express (NEW) --}}
                        <div class="glass-frosted-deep rounded-[var(--radius-xl)] p-5 transform -translate-x-4 -rotate-1 hover:translate-x-0 hover:rotate-0 transition duration-500 relative z-5"
                             data-testid="hero-card-express">
                            <div class="flex items-center gap-2 mb-3">
                                <svg class="w-4 h-4 text-[var(--accent-cyan)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="text-[var(--color-text)] text-sm font-bold font-display">Pesan Express</span>
                                <x-ui.badge variant="info" size="xs">BARU</x-ui.badge>
                            </div>
                            <div class="flex gap-2">
                                <div class="flex-1 rounded-[var(--radius-sm)] p-2 text-center" style="background: var(--color-bg-sunken);">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg flex items-center justify-center" style="background: var(--accent-cyan); opacity: 0.15;">
                                        <svg class="w-4 h-4 text-[var(--accent-cyan)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="text-[10px] text-[var(--color-text-muted)] font-medium">Kertas</span>
                                </div>
                                <div class="flex-1 rounded-[var(--radius-sm)] p-2 text-center" style="background: var(--color-bg-sunken);">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg flex items-center justify-center" style="background: var(--accent-violet); opacity: 0.15;">
                                        <svg class="w-4 h-4 text-[var(--accent-violet)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </div>
                                    <span class="text-[10px] text-[var(--color-text-muted)] font-medium">Alat Tulis</span>
                                </div>
                                <div class="flex-1 rounded-[var(--radius-sm)] p-2 text-center" style="background: var(--color-bg-sunken);">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-lg flex items-center justify-center" style="background: var(--accent-amber); opacity: 0.15;">
                                        <svg class="w-4 h-4 text-[var(--accent-amber)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    </div>
                                    <span class="text-[10px] text-[var(--color-text-muted)] font-medium">Cetak</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════
         "KENAPA MEMILIH KAMI" SECTION
    ════════════════════════════════════════════════════════ --}}
    <section class="relative py-16 md:py-24" data-testid="features-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <x-ui.badge variant="premium" size="md" class="mb-4">Standar Industri</x-ui.badge>
                <h2 class="font-display font-bold text-[var(--color-text)] mb-6"
                    style="font-size: clamp(2rem, 3vw + 1rem, 3.25rem);">Kenapa Memilih Kami?</h2>
                <p class="text-[var(--color-text-muted)] max-w-2xl mx-auto text-lg font-light">Kami menggabungkan efisiensi digital
                    dengan kualitas fisik, menciptakan alur kerja yang mulus bagi perusahaan Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature Card 1 — Cyan accent --}}
                <x-ui.glass-card variant="default" padding="lg" class="flex flex-col items-start motion-card" data-testid="feature-card-keamanan">
                    <div class="w-14 h-14 rounded-[var(--radius-md)] flex items-center justify-center mb-6 shadow-lg"
                         style="background: linear-gradient(135deg, var(--accent-cyan), var(--gold-400));">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-text)] mb-3 font-display">Keamanan Terjamin</h3>
                    <p class="text-[var(--color-text-muted)] leading-relaxed font-light text-sm">Setiap transaksi dan file dokumen yang
                        Anda unggah dilindungi oleh sistem enkripsi tingkat industri.</p>
                </x-ui.glass-card>

                {{-- Feature Card 2 — Violet accent --}}
                <x-ui.glass-card variant="default" padding="lg" class="flex flex-col items-start motion-card" data-testid="feature-card-cepat">
                    <div class="w-14 h-14 rounded-[var(--radius-md)] flex items-center justify-center mb-6 shadow-lg"
                         style="background: linear-gradient(135deg, var(--accent-violet), var(--accent-magenta));">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-text)] mb-3 font-display">Penyelesaian Cepat</h3>
                    <p class="text-[var(--color-text-muted)] leading-relaxed font-light text-sm">Otomatisasi pesanan membuat waktu
                        produksi dan pengiriman menjadi jauh lebih responsif dan efisien.</p>
                    {{-- Animated chip --}}
                    <div class="mt-3">
                        <x-ui.badge variant="success" size="xs" class="motion-card">+12% lebih cepat</x-ui.badge>
                    </div>
                </x-ui.glass-card>

                {{-- Feature Card 3 — Teal accent --}}
                <x-ui.glass-card variant="default" padding="lg" class="flex flex-col items-start motion-card" data-testid="feature-card-premium">
                    <div class="w-14 h-14 rounded-[var(--radius-md)] flex items-center justify-center mb-6 shadow-lg"
                         style="background: linear-gradient(135deg, var(--accent-teal), var(--accent-mint));">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-text)] mb-3 font-display">Hasil Premium</h3>
                    <p class="text-[var(--color-text-muted)] leading-relaxed font-light text-sm">Pengontrolan kualitas ketat (QC) di
                        setiap lini memastikan hasil cetakan presisi yang memuaskan.</p>
                </x-ui.glass-card>
            </div>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════
         "LAYANAN UNGGULAN" SECTION
    ════════════════════════════════════════════════════════ --}}
    <section class="relative py-16 md:py-24" id="layanan" data-testid="services-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <x-ui.glass-card variant="frosted" padding="none" class="overflow-hidden">
                <div class="p-8 md:p-12 lg:p-16">
                    <div class="flex flex-col lg:flex-row gap-16 items-center">
                        <div class="w-full lg:w-1/2">
                            <x-ui.badge variant="info" size="md" class="mb-4">Hub Utama</x-ui.badge>
                            <h2 class="font-display font-bold text-[var(--color-text)] mb-6 leading-tight"
                                style="font-size: clamp(2rem, 3vw + 1rem, 3.25rem);">
                                Layanan<br /><span class="text-transparent bg-clip-text" style="background-image: var(--gradient-gold-day); -webkit-background-clip: text;">Unggulan Kami.</span></h2>
                            <p class="text-[var(--color-text-muted)] mb-8 font-light text-lg">Platform ini direkayasa khusus supaya
                                keperluan operasional Anda dapat terpusat pada satu pintu dengan akses gampang.</p>

                            <div class="space-y-4">
                                {{-- Service item 1 --}}
                                <a href="{{ url('/katalog') }}"
                                    class="group block p-5 rounded-[var(--radius-lg)] border border-[var(--color-border)] transition-all duration-300 hover:shadow-[var(--shadow-md)] hover:-translate-y-0.5"
                                    style="background: var(--color-bg-elevated);"
                                    data-testid="service-link-katalog">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-[var(--radius-md)] flex items-center justify-center transition-all duration-300 shrink-0"
                                             style="background: linear-gradient(135deg, var(--accent-cyan), var(--gold-400));">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-[var(--color-text)] mb-0.5 font-display">Katalog Alat Tulis Kantor</h4>
                                            <p class="text-sm text-[var(--color-text-muted)]">Persediaan pulpen, map, hingga kertas folio.</p>
                                        </div>
                                        <svg class="w-5 h-5 text-[var(--color-text-muted)] group-hover:text-[var(--color-primary)] group-hover:translate-x-1 transition-all ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </a>

                                {{-- Service item 2 --}}
                                <a href="{{ url('/jasa') }}"
                                    class="group block p-5 rounded-[var(--radius-lg)] border border-[var(--color-border)] transition-all duration-300 hover:shadow-[var(--shadow-md)] hover:-translate-y-0.5"
                                    style="background: var(--color-bg-elevated);"
                                    data-testid="service-link-jasa">
                                    <div class="flex items-center gap-5">
                                        <div class="w-12 h-12 rounded-[var(--radius-md)] flex items-center justify-center transition-all duration-300 shrink-0"
                                             style="background: linear-gradient(135deg, var(--accent-violet), var(--accent-magenta));">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-lg font-bold text-[var(--color-text)] mb-0.5 font-display">Layanan Cetak Digital</h4>
                                            <p class="text-sm text-[var(--color-text-muted)]">Banner, dokumen berbendel, kartu nama bisnis.</p>
                                        </div>
                                        <svg class="w-5 h-5 text-[var(--color-text-muted)] group-hover:text-[var(--color-primary)] group-hover:translate-x-1 transition-all ml-auto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        </div>

                        {{-- Right: Interactive product mosaic --}}
                        <div class="w-full lg:w-1/2 relative">
                            <div class="relative w-full aspect-square md:aspect-auto md:h-[500px] flex items-center justify-center">
                                {{-- Background gradient mesh --}}
                                <div class="absolute inset-0 rounded-[var(--radius-2xl)] transform rotate-3 opacity-60"
                                     style="background: linear-gradient(135deg, rgba(79,163,255,0.2), rgba(124,92,255,0.15), rgba(34,211,238,0.1));"></div>

                                {{-- Tilted product tiles grid --}}
                                <div class="relative z-10 grid grid-cols-2 gap-4 p-8 transform -rotate-3 hover:rotate-0 transition-transform duration-700">
                                    <div class="glass-card rounded-[var(--radius-md)] p-4 flex flex-col items-center gap-2 hover:-translate-y-1 transition-transform">
                                        <div class="w-16 h-16 rounded-[var(--radius-sm)] flex items-center justify-center" style="background: var(--color-bg-sunken);">
                                            <svg class="w-8 h-8 text-[var(--accent-cyan)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-[var(--color-text)] font-display">Pulpen</span>
                                        <span class="text-[10px] text-[var(--color-text-muted)] font-mono">Rp 12.000</span>
                                    </div>
                                    <div class="glass-card rounded-[var(--radius-md)] p-4 flex flex-col items-center gap-2 hover:-translate-y-1 transition-transform">
                                        <div class="w-16 h-16 rounded-[var(--radius-sm)] flex items-center justify-center" style="background: var(--color-bg-sunken);">
                                            <svg class="w-8 h-8 text-[var(--accent-violet)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-[var(--color-text)] font-display">Kertas A4</span>
                                        <span class="text-[10px] text-[var(--color-text-muted)] font-mono">Rp 55.000</span>
                                    </div>
                                    <div class="glass-card rounded-[var(--radius-md)] p-4 flex flex-col items-center gap-2 hover:-translate-y-1 transition-transform">
                                        <div class="w-16 h-16 rounded-[var(--radius-sm)] flex items-center justify-center" style="background: var(--color-bg-sunken);">
                                            <svg class="w-8 h-8 text-[var(--accent-amber)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-[var(--color-text)] font-display">Map Folio</span>
                                        <span class="text-[10px] text-[var(--color-text-muted)] font-mono">Rp 8.500</span>
                                    </div>
                                    <div class="glass-card rounded-[var(--radius-md)] p-4 flex flex-col items-center gap-2 hover:-translate-y-1 transition-transform">
                                        <div class="w-16 h-16 rounded-[var(--radius-sm)] flex items-center justify-center" style="background: var(--color-bg-sunken);">
                                            <svg class="w-8 h-8 text-[var(--accent-teal)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-[var(--color-text)] font-display">Cetak</span>
                                        <span class="text-[10px] text-[var(--color-text-muted)] font-mono">Rp 500/lbr</span>
                                    </div>
                                </div>

                                {{-- Hovering promo pill --}}
                                <div class="absolute top-4 right-4 z-20">
                                    <div class="px-4 py-2 rounded-full shadow-lg text-xs font-bold text-white" style="background: var(--gradient-aurora);">
                                        PROMO 🔥
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-ui.glass-card>
        </div>
    </section>

    {{-- ════════════════════════════════════════════════════════
         "STATISTIK & KEPERCAYAAN" SECTION (NEW)
    ════════════════════════════════════════════════════════ --}}
    <section class="relative py-16 md:py-24" data-testid="stats-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-12">
                <h2 class="font-display font-bold text-[var(--color-text)] mb-4"
                    style="font-size: clamp(1.75rem, 2vw + 1rem, 2.25rem);">Dipercaya Ribuan Pelanggan</h2>
                <p class="text-[var(--color-text-muted)] max-w-xl mx-auto">Angka-angka yang berbicara tentang kualitas layanan kami.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <x-ui.stat-card label="Produk ATK" value="+5K" accent="cyan">
                    <x-slot:icon>
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card label="Total Pesanan" value="10K+" accent="violet">
                    <x-slot:icon>
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card label="Kepuasan" value="98%" accent="teal">
                    <x-slot:icon>
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>

                <x-ui.stat-card label="Support" value="24/7" accent="amber">
                    <x-slot:icon>
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </x-slot:icon>
                </x-ui.stat-card>
            </div>
        </div>
    </section>

    @push('scripts')
    <script type="module">
        import { animate, inView, stagger } from "https://esm.sh/motion";

        // Scramble text animation
        const scrambleTargets = document.querySelectorAll('[data-scramble]');
        const scrambleChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const randomChar = () => scrambleChars[Math.floor(Math.random() * scrambleChars.length)];
        const centerDelay = (index, total, step = 0.03) => {
            const center = (total - 1) / 2;
            return Math.abs(index - center) * step;
        };

        scrambleTargets.forEach((element) => {
            const text = element.textContent ?? '';
            const chars = Array.from(text);
            element.textContent = '';

            chars.forEach((char, index) => {
                const span = document.createElement('span');
                span.dataset.final = char;
                span.style.display = 'inline-block';
                span.style.opacity = '0';
                span.textContent = char === ' ' ? '\u00A0' : randomChar();
                element.appendChild(span);

                const delay = centerDelay(index, chars.length);
                animate(span, { opacity: [0, 1] }, { duration: 0.2, delay });
                window.setTimeout(() => {
                    span.textContent = char === ' ' ? '\u00A0' : char;
                }, (delay + 0.2) * 1000);
            });
        });

        // Hero entry animations
        animate("section[data-testid='hero-section'] h1", { y: [40, 0], opacity: [0, 1] }, { duration: 0.8, easing: [0.22, 1, 0.36, 1], delay: 0.1 });
        animate("section[data-testid='hero-section'] p", { y: [20, 0], opacity: [0, 1] }, { duration: 0.8, easing: [0.22, 1, 0.36, 1], delay: 0.3 });
        animate("section[data-testid='hero-section'] [data-testid^='hero-cta']", { y: [20, 0], opacity: [0, 1] }, { duration: 0.6, delay: stagger(0.15, { start: 0.5 }) });
        animate("section[data-testid='hero-section'] .glass-card, section[data-testid='hero-section'] .glass-frosted-deep", { scale: [0.9, 1], opacity: [0, 1] }, { duration: 0.8, delay: stagger(0.2, { start: 0.4 }) });

        // Feature cards scroll animation
        inView("[data-testid='features-section'] .motion-card", (info) => {
            animate(info.target, { y: [50, 0], opacity: [0, 1] }, { duration: 0.7, easing: [0.17, 0.55, 0.55, 1] });
        });

        // Services section scroll animation
        inView("#layanan", (info) => {
            animate("#layanan a[data-testid^='service-link']", { x: [-30, 0], opacity: [0, 1] }, { duration: 0.6, delay: stagger(0.15, { start: 0.3 }) });
        });

        // Stats section scroll animation
        inView("[data-testid='stats-section']", () => {
            animate("[data-testid='stats-section'] [data-testid^='stat-card']", { y: [30, 0], opacity: [0, 1] }, { duration: 0.6, delay: stagger(0.1) });
        });
    </script>
    @endpush

</x-layouts.app>
