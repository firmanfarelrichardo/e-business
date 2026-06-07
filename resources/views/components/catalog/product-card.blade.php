@props(['product', 'brand', 'price', 'id', 'originalPrice' => null, 'badge' => null, 'image' => null])

@php
    $discount = ($originalPrice && $originalPrice > $price)
        ? round((1 - $price / $originalPrice) * 100)
        : null;
@endphp

<x-ui.glass-card variant="default"
    class="group flex flex-col h-full p-4 cursor-pointer relative overflow-hidden"
    data-testid="catalog-product-card-{{ $id }}">

    {{-- Wishlist button (visual-only, future feature) --}}
    <button type="button"
        class="absolute top-4 right-4 z-20 w-8 h-8 rounded-full bg-[var(--color-bg-elevated)] border border-[var(--color-border)] flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-200 hover:scale-110 hover:text-[var(--accent-rose)]"
        aria-label="Tambah ke wishlist"
        data-testid="catalog-product-card-wishlist-{{ $id }}"
        x-data="{ liked: false }" @click.prevent="liked = !liked"
        :class="liked ? 'text-[var(--accent-rose)]' : 'text-[var(--color-text-muted)]'">
        <svg class="w-4 h-4" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
        </svg>
    </button>

    {{-- Badge --}}
    @if($badge)
        <div class="absolute top-4 left-4 z-10">
            @if($badge == 'Sold Out')
                <x-ui.badge variant="sold-out">{{ $badge }}</x-ui.badge>
            @elseif(str_contains($badge, '%'))
                <x-ui.badge variant="sale">{{ $badge }}</x-ui.badge>
            @else
                <x-ui.badge variant="premium">{{ $badge }}</x-ui.badge>
            @endif
        </div>
    @endif

    {{-- Image --}}
    <div class="aspect-square mb-4 flex items-center justify-center p-4 rounded-[var(--radius-md)] border border-[var(--color-border-subtle)] bg-[var(--color-bg-sunken)] group-hover:border-[var(--color-primary)]/30 transition-colors duration-300">
        @if($image)
            <img src="{{ $image }}" alt="{{ $product }}"
                class="max-w-full max-h-full object-contain group-hover:scale-110 transition-transform duration-500">
        @else
            {{-- Placeholder if no image --}}
            <div class="w-20 h-20 rounded-full bg-[var(--color-bg-elevated)] flex items-center justify-center">
                <svg class="w-10 h-10 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="flex-grow flex flex-col">
        <div class="text-[10px] uppercase font-bold text-[var(--color-primary)] tracking-[0.08em] mb-1 font-display">{{ $brand }}</div>
        <h3 class="text-sm font-semibold text-[var(--color-text)] leading-tight mb-2 line-clamp-2 font-display">{{ $product }}</h3>

        <div class="mt-auto">
            @if($originalPrice && $originalPrice > $price)
                <div class="flex items-center gap-2 mb-0.5">
                    <span class="text-xs text-[var(--color-text-muted)] line-through font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                    <x-ui.badge variant="sale" size="xs">Hemat {{ $discount }}%</x-ui.badge>
                </div>
            @endif
            <div class="text-[var(--color-text)] font-bold font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($price, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Add to cart overlay (appears on hover) --}}
    <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-[var(--ease-out)]">
        <div class="glass-overlay rounded-[var(--radius-md)] p-3">
            <form action="{{ url('/cart/add') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="product">
                <input type="hidden" name="id" value="{{ $id }}">
                <input type="hidden" name="quantity" value="1">
                <x-ui.button variant="aurora" size="sm" type="submit" class="w-full" data-testid="catalog-product-card-add-button-{{ $id }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    Tambah ke Keranjang
                </x-ui.button>
            </form>
        </div>
    </div>
</x-ui.glass-card>