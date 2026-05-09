<x-layouts.app title="Layanan Jasa Percetakan - Sinergi">
    <!-- Top Category Nav -->
    <x-catalog.category-nav :categories="$categories" active="Pencetakan Dokumen" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        
        <!-- Breadcrumb & Subcategory (for Jasa) -->
        <x-catalog.sub-category 
            title="Layanan Percetakan Digital" 
            :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z\'></path></svg>'" 
            :subcategories="['Print Hitam Putih', 'Print Warna', 'Print Kertas Khusus', 'Laminasi']" 
        />

        <div class="mt-8 mb-16 text-center lg:text-left">
            <h1 class="text-3xl font-bold text-slate-800 mb-4">Percetakan Dokumen Cepat & Presisi</h1>
            <p class="text-slate-600 max-w-3xl">Pilih spesifikasi pencetakan yang Anda butuhkan. Kami menggunakan mesin beresolusi tinggi untuk menjamin hasil teks yang tajam dan warna yang akurat sesuai standar perusahaan.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <!-- Jasa Card 1 -->
            <x-ui.glass-card class="p-6 flex flex-col items-start hover:border-brand-primary/50 group">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-4 text-brand-dark group-hover:bg-brand-primary group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Print A4 Hitam Putih</h3>
                <p class="text-sm text-slate-600 mb-6 flex-grow">Pencetakan dokumen teks standar dengan kertas HVS 75gsm. Cocok untuk laporan harian dan draf.</p>
                <div class="w-full flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                    <div>
                        <span class="text-xs text-slate-500 block">Harga mulai</span>
                        <span class="text-brand-dark font-bold text-lg">Rp 500 <span class="text-xs font-normal text-slate-500">/ lembar</span></span>
                    </div>
                    <button class="bg-brand-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-primary transition">Pesan</button>
                </div>
            </x-ui.glass-card>

            <!-- Jasa Card 2 -->
            <x-ui.glass-card class="p-6 flex flex-col items-start hover:border-brand-primary/50 group">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-4 text-brand-dark group-hover:bg-brand-primary group-hover:text-white transition-colors relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-400 via-yellow-400 to-blue-500 opacity-20 group-hover:opacity-40"></div>
                    <svg class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Print A4 Berwarna (Full Color)</h3>
                <p class="text-sm text-slate-600 mb-6 flex-grow">Pencetakan grafik dan presentasi berwarna dengan tinta anti luntur pada kertas HVS 80gsm kualitas premium.</p>
                <div class="w-full flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                    <div>
                        <span class="text-xs text-slate-500 block">Harga mulai</span>
                        <span class="text-brand-dark font-bold text-lg">Rp 1.500 <span class="text-xs font-normal text-slate-500">/ lembar</span></span>
                    </div>
                    <button class="bg-brand-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-primary transition">Pesan</button>
                </div>
            </x-ui.glass-card>

            <!-- Jasa Card 3 -->
            <x-ui.glass-card class="p-6 flex flex-col items-start hover:border-brand-primary/50 group">
                <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-4 text-brand-dark group-hover:bg-brand-primary group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Print Kertas Art Carton / Foto</h3>
                <p class="text-sm text-slate-600 mb-6 flex-grow">Kertas tebal dan mengkilap (210gsm - 260gsm) untuk piagam, sertifikat, atau pamflet promosi.</p>
                <div class="w-full flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                    <div>
                        <span class="text-xs text-slate-500 block">Harga mulai</span>
                        <span class="text-brand-dark font-bold text-lg">Rp 4.000 <span class="text-xs font-normal text-slate-500">/ lembar</span></span>
                    </div>
                    <button class="bg-brand-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-primary transition">Pesan</button>
                </div>
            </x-ui.glass-card>
        </div>
    </div>
</x-layouts.app>
