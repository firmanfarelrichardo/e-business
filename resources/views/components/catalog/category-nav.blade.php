@props(['categories', 'active' => ''])

<div class="bg-gradient-to-r from-brand-dark to-brand-primary w-full shadow-md relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto hide-scrollbar">
            @foreach($categories as $category)
                <a href="{{ $category['url'] ?? '#' }}" 
                   class="whitespace-nowrap px-5 py-3 text-sm font-semibold transition-colors duration-200 border-b-2 
                   @if($active == $category['name']) 
                       text-white border-brand-tertiary 
                   @else 
                       text-white/70 border-transparent hover:text-white hover:border-white/50 
                   @endif">
                    {{ $category['name'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>
