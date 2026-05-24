@props(['categories', 'active' => ''])

<div class="w-full glass-panel sticky top-[72px] sm:top-[88px] z-20 border-x-0 border-t-0" data-testid="category-nav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto hide-scrollbar py-1">
            @foreach($categories as $category)
                <a href="{{ $category['url'] ?? '#' }}"
                   class="whitespace-nowrap px-5 py-3 text-sm font-semibold transition-all duration-200 border-b-[3px] relative
                   @if($active == $category['name'])
                       text-[var(--color-primary)] border-transparent
                   @else
                       text-[var(--color-text-muted)] border-transparent hover:text-[var(--color-text)] hover:border-[var(--color-border-subtle)]
                   @endif"
                   data-testid="category-nav-item-{{ Str::slug($category['name']) }}">
                    {{ $category['name'] }}
                    @if($active == $category['name'])
                        {{-- Active gradient bar --}}
                        <span class="absolute bottom-0 left-2 right-2 h-[3px] rounded-full" style="background: var(--gradient-aurora); box-shadow: 0 0 8px rgba(124,92,255,0.3);"></span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
