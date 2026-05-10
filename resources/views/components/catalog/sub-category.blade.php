@props(['title', 'icon' => null, 'subcategories' => []])

<div class="w-full mb-6">
    <!-- Category Title -->
    <div class="bg-white rounded-t-xl px-6 py-4 border border-b-0 border-slate-200 flex items-center gap-3">
        @if($icon)
            <div class="text-brand-primary">
                {!! $icon !!}
            </div>
        @else
            <div class="w-2 h-6 bg-brand-primary rounded-full"></div>
        @endif
        <h2 class="text-xl font-bold text-brand-dark uppercase tracking-wide">{{ $title }}</h2>
    </div>

    <!-- Subcategories Bar -->
    <div class="bg-[#2C3437] rounded-b-xl px-2 shadow-sm border border-slate-200">
        <div class="flex overflow-x-auto hide-scrollbar">
            @foreach($subcategories as $sub)
                @if(is_array($sub))
                    <a href="{{ $sub['url'] ?? '#' }}"
                        class="whitespace-nowrap px-6 py-2.5 text-xs font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors my-1 mx-1 {{ request('brand') == ($sub['id'] ?? '') ? 'bg-white/20 text-white' : '' }}">
                        {{ $sub['name'] }}
                    </a>
                @else
                    <a href="{{ url('/jasa') }}"
                        class="whitespace-nowrap px-6 py-2.5 text-xs font-semibold text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition-colors my-1 mx-1">
                        {{ $sub }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>