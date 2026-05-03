<x-layouts.app title="Fitur Belum Tersedia - Sinergi">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 mb-24 text-center flex flex-col items-center justify-center min-h-[50vh]">
        
        <x-ui.glass-card class="p-12 w-full max-w-2xl relative overflow-hidden">
            <!-- Decorative blobs -->
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand-primary rounded-full mix-blend-multiply filter blur-2xl opacity-20"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-brand-secondary rounded-full mix-blend-multiply filter blur-2xl opacity-20"></div>

            <div class="relative z-10 flex flex-col items-center">
                <div class="w-24 h-24 bg-brand-light rounded-full flex items-center justify-center mb-6 text-brand-primary">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                
                <h1 class="text-3xl font-bold text-slate-800 mb-4">Fitur Sedang Dalam Pengembangan</h1>
                
                <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-2 rounded-lg text-sm font-mono mb-6">
                    Error Code: ERR_NOT_IMPLEMENTED
                </div>
                
                <p class="text-slate-600 mb-8 max-w-md">
                    Maaf, halaman atau endpoint API yang Anda tuju belum terkonfigurasi atau masih dalam tahap pengembangan (Work in Progress). Silakan kembali nanti.
                </p>

                <a href="{{ url('/') }}" class="bg-brand-primary hover:bg-brand-dark text-white font-semibold py-3 px-8 rounded-xl shadow-md transition-colors duration-300">
                    Kembali ke Beranda
                </a>
            </div>
        </x-ui.glass-card>

    </div>
</x-layouts.app>
