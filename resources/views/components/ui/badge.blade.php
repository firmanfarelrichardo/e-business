@props(['variant' => 'neutral', 'size' => 'sm'])

@php
    $base = 'inline-flex items-center font-semibold tracking-wide uppercase rounded-full whitespace-nowrap';
    $variants = [
        'info'     => 'bg-[var(--accent-cyan)]/15 text-[var(--accent-cyan)] border border-[var(--accent-cyan)]/20',
        'success'  => 'bg-[var(--accent-teal)]/15 text-[var(--accent-teal)] border border-[var(--accent-teal)]/20',
        'warning'  => 'bg-[var(--accent-amber)]/15 text-[var(--accent-amber)] border border-[var(--accent-amber)]/20',
        'danger'   => 'bg-[var(--accent-rose)]/15 text-[var(--accent-rose)] border border-[var(--accent-rose)]/20',
        'premium'  => 'bg-[var(--accent-violet)]/15 text-[var(--accent-violet)] border border-[var(--accent-violet)]/20',
        'sale'     => 'bg-[var(--accent-coral)]/15 text-[var(--accent-coral)] border border-[var(--accent-coral)]/20',
        'neutral'  => 'bg-[var(--color-bg-sunken)] text-[var(--color-text-muted)] border border-[var(--color-border-subtle)]',
        'sold-out' => 'bg-[var(--color-bg-sunken)] text-[var(--color-text-muted)] border border-[var(--color-border-subtle)] line-through',
    ];
    $sizes = [
        'xs' => 'text-[9px] px-2 py-0.5 tracking-[0.08em]',
        'sm' => 'text-[10px] px-2.5 py-1 tracking-[0.06em]',
        'md' => 'text-[11px] px-3 py-1 tracking-[0.05em]',
    ];
    $classes = "$base " . ($variants[$variant] ?? $variants['neutral']) . ' ' . ($sizes[$size] ?? $sizes['sm']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }} data-testid="badge-{{ $variant }}">
    {{ $slot }}
</span>
