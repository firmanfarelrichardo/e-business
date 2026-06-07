@props(['title', 'icon' => null, 'subcategories' => []])

<x-ui.glass-card variant="default" padding="none" class="w-full mb-6 overflow-hidden" data-testid="sub-category-bar">
    {{-- Category Title --}}
    <div class="px-6 py-4 flex items-center gap-3 border-b border-[var(--color-border-subtle)]">
        @if($icon)
            <div class="text-[var(--color-primary)]">
                {!! $icon !!}
            </div>
        @else
            <div class="w-1.5 h-6 rounded-full" style="background: var(--gradient-gold-day);"></div>
        @endif
        <h2 class="text-xl font-bold text-[var(--color-text)] uppercase tracking-wide font-display">{{ $title }}</h2>
    </div>

    {{-- Subcategories Chips --}}
    <div class="px-3 py-2">
        <div class="flex overflow-x-auto hide-scrollbar gap-1.5">
            @foreach($subcategories as $sub)
                @if(is_array($sub))
                    <a href="{{ $sub['url'] ?? '#' }}"
                        class="whitespace-nowrap px-5 py-2 text-xs font-semibold rounded-full transition-all duration-200 my-0.5
                        {{ request('brand') == ($sub['id'] ?? '') 
                            ? 'text-white shadow-md' 
                            : 'text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-bg-sunken)] bg-transparent' }}"
                        @if(request('brand') == ($sub['id'] ?? ''))
                            style="background: var(--gradient-gold-day);"
                        @endif
                        data-testid="sub-category-chip-{{ Str::slug($sub['name']) }}">
                        {{ $sub['name'] }}
                    </a>
                @else
                    <a href="{{ url('/jasa') }}"
                        class="whitespace-nowrap px-5 py-2 text-xs font-semibold rounded-full text-[var(--color-text-secondary)] hover:text-[var(--color-text)] hover:bg-[var(--color-bg-sunken)] transition-all duration-200 my-0.5"
                        data-testid="sub-category-chip">
                        {{ $sub }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</x-ui.glass-card>