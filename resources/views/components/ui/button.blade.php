@props(['variant' => 'primary', 'size' => 'md', 'icon' => null, 'as' => 'button', 'href' => null])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold font-display tracking-tight transition-all duration-200 active:scale-[0.98] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--color-primary)] cursor-pointer';
    $variants = [
        'primary'  => 'bg-[image:var(--gradient-gold-day)] text-white shadow-[var(--shadow-md)] hover:shadow-[var(--shadow-lg)] hover:brightness-110',
        'aurora'   => 'bg-[image:var(--gradient-aurora)] text-white shadow-[var(--shadow-glow)] hover:shadow-[var(--shadow-xl)] hover:brightness-105',
        'ghost'    => 'bg-transparent text-[var(--color-text)] hover:bg-[var(--color-bg-elevated)]',
        'glass'    => 'glass-panel text-[var(--color-text)] hover:brightness-110',
        'outline'  => 'border border-[var(--color-primary)] text-[var(--color-primary)] hover:bg-[var(--color-primary)] hover:text-white',
        'danger'   => 'bg-[var(--accent-rose)] text-white hover:brightness-110 shadow-[var(--shadow-md)]',
        'success'  => 'bg-[var(--accent-teal)] text-white hover:brightness-110 shadow-[var(--shadow-md)]',
    ];
    $sizes = [
        'xs'  => 'h-8 px-3 rounded-[var(--radius-xs)] text-xs',
        'sm'  => 'h-9 px-4 rounded-[var(--radius-sm)] text-sm',
        'md'  => 'h-11 px-6 rounded-[var(--radius-md)] text-[0.9375rem]',
        'lg'  => 'h-14 px-8 rounded-[var(--radius-lg)] text-base',
        'pill'=> 'h-12 px-7 rounded-full text-[0.9375rem]',
    ];
    $classes = "$base " . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($as === 'a')
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} data-testid="btn-{{ $variant }}">
        @isset($icon){!! $icon !!}@endisset
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'type' => 'button']) }} data-testid="btn-{{ $variant }}">
        @isset($icon){!! $icon !!}@endisset
        {{ $slot }}
    </button>
@endif
