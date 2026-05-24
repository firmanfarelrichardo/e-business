@props(['variant' => 'default', 'padding' => 'md', 'glow' => false, 'class' => ''])

@php
    $base = 'relative rounded-[var(--radius-lg)] border backdrop-blur-[16px] transition-[transform,box-shadow,background-color] duration-300';
    $variants = [
        'default'  => 'bg-[var(--color-bg-elevated)] border-[var(--color-border)] shadow-[var(--shadow-md)] hover:shadow-[var(--shadow-lg)] hover:-translate-y-0.5',
        'overlay'  => 'bg-[var(--color-bg-overlay)] border-[var(--color-border)] shadow-[var(--shadow-xl)] backdrop-blur-[24px]',
        'frosted'  => 'glass-frosted-deep',
        'aurora'   => 'bg-[var(--color-bg-elevated)] border-[var(--color-border)] shadow-[var(--shadow-md)] overflow-hidden',
        'sky'      => 'border-[var(--color-border)]',
        'light'    => 'bg-[var(--color-bg-elevated)] border-[var(--color-border)] shadow-[var(--shadow-md)] hover:shadow-[var(--shadow-lg)]',
        'dark'     => 'glass-card-dark',
        'primary'  => 'bg-[var(--color-primary)]/40 border-[var(--color-primary)]/20 shadow-lg hover:shadow-xl',
    ];
    $paddings = ['none' => '', 'sm' => 'p-4', 'md' => 'p-6', 'lg' => 'p-8', 'xl' => 'p-10'];
    $glowClass = $glow ? 'shadow-[var(--shadow-glow)]' : '';
    $classes = "$base " . ($variants[$variant] ?? $variants['default']) . ' ' . ($paddings[$padding] ?? '') . ' ' . $glowClass . ' ' . $class;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} data-testid="glass-card-{{ $variant }}">
    @if($variant === 'aurora')
        <div class="absolute inset-0 -z-10 bg-[image:var(--gradient-aurora)] opacity-20 blur-2xl rounded-[inherit]"></div>
    @endif
    @if($variant === 'sky')
        <div class="absolute inset-0 -z-10 rounded-[inherit]" style="background: linear-gradient(135deg, rgba(79,163,255,0.08) 0%, rgba(184,220,255,0.12) 100%);"></div>
    @endif
    {{ $slot }}
</div>
