@props(['variant' => 'light', 'class' => ''])

@php
    $baseClass = 'rounded-[2rem] overflow-hidden backdrop-blur-xl border transition-all duration-300';
    $variants = [
        'light' => 'bg-white/60 border-white/60 shadow-[0_8px_32px_0_rgba(31,38,135,0.05)] hover:shadow-[0_12px_40px_0_rgba(31,38,135,0.1)]',
        'dark' => 'bg-brand-dark/60 border-white/10 shadow-[0_8px_32px_0_rgba(0,0,0,0.2)] hover:shadow-[0_12px_40px_0_rgba(0,0,0,0.3)]',
        'primary' => 'bg-brand-primary/40 border-brand-primary/20 shadow-lg hover:shadow-xl',
    ];
    
    $classes = $baseClass . ' ' . ($variants[$variant] ?? $variants['light']) . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
