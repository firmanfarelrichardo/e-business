@props(['product', 'brand', 'price', 'originalPrice' => null, 'badge' => null, 'image' => null])

<x-ui.glass-card variant="light" class="group flex flex-col h-full bg-white/80 hover:bg-white p-4 cursor-pointer relative overflow-hidden">
    <!-- Badge -->
    @if($badge)
        <div class="absolute top-4 left-4 z-10">
            @if($badge == 'Sold Out')
                <span class="bg-slate-800 text-white text-xs font-bold px-2 py-1 rounded">{{ $badge }}</span>
            @elseif(str_contains($badge, '%'))
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">{{ $badge }}</span>
            @else
                <span class="bg-brand-primary text-white text-xs font-bold px-2 py-1 rounded">{{ $badge }}</span>
            @endif
        </div>
    @endif

    <!-- Image -->
    <div class="aspect-square mb-4 flex items-center justify-center p-4 bg-white rounded-2xl border border-slate-100 group-hover:border-brand-primary/30 transition-colors">
        @if($image)
            <img src="{{ $image }}" alt="{{ $product }}" class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
        @else
            <!-- Placeholder if no image -->
            <div class="w-20 h-20 rounded-full bg-brand-light flex items-center justify-center">
                <svg class="w-10 h-10 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="flex-grow flex flex-col">
        <div class="text-[10px] uppercase font-bold text-brand-primary tracking-wider mb-1">{{ $brand }}</div>
        <h3 class="text-sm font-semibold text-slate-800 leading-tight mb-2 line-clamp-2">{{ $product }}</h3>
        
        <div class="mt-auto">
            @if($originalPrice)
                <div class="text-xs text-slate-400 line-through mb-0.5">Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
            @endif
            <div class="text-brand-dark font-bold">Rp {{ number_format($price, 0, ',', '.') }}</div>
        </div>
    </div>
    
    <!-- Add to cart overlay (appears on hover) -->
    <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out bg-gradient-to-t from-white via-white to-transparent">
        <button class="w-full bg-brand-dark hover:bg-brand-primary text-white text-sm font-semibold py-2 rounded-xl shadow-md transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Tambah
        </button>
    </div>
</x-ui.glass-card>
