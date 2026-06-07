@props([
    'label' => '',
    'value' => '0',
    'delta' => null,
    'accent' => 'cyan',
    'icon' => null,
])

@php
    $accentColors = [
        'violet'  => ['from' => '#0284C7', 'to' => '#0EA5E9', 'shadow' => 'rgba(14,165,233,0.25)'],
        'cyan'    => ['from' => '#22D3EE', 'to' => '#4FA3FF', 'shadow' => 'rgba(34,211,238,0.25)'],
        'teal'    => ['from' => '#14B8A6', 'to' => '#34E2A6', 'shadow' => 'rgba(20,184,166,0.25)'],
        'amber'   => ['from' => '#F59E0B', 'to' => '#FB7185', 'shadow' => 'rgba(245,158,11,0.25)'],
        'rose'    => ['from' => '#F43F5E', 'to' => '#FB7185', 'shadow' => 'rgba(244,63,94,0.25)'],
    ];
    $color = $accentColors[$accent] ?? $accentColors['cyan'];
    $deltaPositive = $delta && (float)$delta > 0;
    $deltaNegative = $delta && (float)$delta < 0;
@endphp

<div class="relative rounded-[var(--radius-lg)] border border-[var(--color-border)] bg-[var(--color-bg-elevated)] backdrop-blur-[16px] p-6 overflow-hidden transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[var(--shadow-lg)] group"
     style="box-shadow: var(--shadow-md), 0 0 24px {{ $color['shadow'] }};"
     data-testid="stat-card-{{ $accent }}">
    
    {{-- Accent glow ring --}}
    <div class="absolute -top-12 -right-12 w-32 h-32 rounded-full opacity-20 blur-2xl transition-transform duration-500 group-hover:scale-125"
         style="background: linear-gradient(135deg, {{ $color['from'] }}, {{ $color['to'] }});"></div>

    <div class="relative z-10">
        <div class="flex items-center justify-between mb-3">
            <span class="text-[0.6875rem] font-semibold tracking-[0.08em] uppercase text-[var(--color-text-muted)] font-display">
                {{ $label }}
            </span>
            @if($icon)
                <div class="w-9 h-9 rounded-[var(--radius-sm)] flex items-center justify-center"
                     style="background: linear-gradient(135deg, {{ $color['from'] }}, {{ $color['to'] }});">
                    {!! $icon !!}
                </div>
            @endif
        </div>
        
        <div class="text-2xl font-bold font-mono tracking-tight text-[var(--color-text)]" style="font-variant-numeric: tabular-nums;">
            {{ $value }}
        </div>

        @if($delta)
            <div class="flex items-center gap-1 mt-2">
                @if($deltaPositive)
                    <svg class="w-3.5 h-3.5 text-[var(--accent-teal)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    <span class="text-xs font-semibold text-[var(--accent-teal)]">+{{ $delta }}%</span>
                @elseif($deltaNegative)
                    <svg class="w-3.5 h-3.5 text-[var(--accent-rose)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    <span class="text-xs font-semibold text-[var(--accent-rose)]">{{ $delta }}%</span>
                @endif
                <span class="text-[10px] text-[var(--color-text-muted)]">vs bulan lalu</span>
            </div>
        @endif
    </div>
</div>
