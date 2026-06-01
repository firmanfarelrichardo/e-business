<x-layouts.app title="Katalog Produk ATK — Sinergi">

    <x-catalog.category-nav :categories="$categories" :active="$activeCategoryName" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        {{-- Decorative blobs --}}
        <div class="blob bg-[var(--accent-amber)] w-96 h-96 -top-20 right-0 -z-10 opacity-20 pointer-events-none"></div>
        <div class="blob bg-[var(--accent-cyan)] w-72 h-72 top-40 -left-10 -z-10 opacity-15 pointer-events-none" style="animation-delay:-6s;"></div>

        {{-- Page heading --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest mb-3 font-display"
                     style="background: rgba(217,119,6,0.12); border: 1px solid rgba(217,119,6,0.25); color: var(--color-primary);">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Koleksi Lengkap
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold font-display leading-tight text-[var(--color-text)]">
                    {{ $activeCategoryName == 'Semua Produk' ? 'Semua' : $activeCategoryName }}
                    <span style="background: var(--gradient-aurora); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        {{ $activeCategoryName == 'Semua Produk' ? 'Produk ATK' : 'Pilihan' }}
                    </span>
                </h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-1 font-light">
                    Kualitas terjamin, harga transparan, pengiriman cepat
                </p>
            </div>

            {{-- Search --}}
            <form method="GET" action="{{ url('/katalog') }}" class="relative flex items-center w-full sm:w-80">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <svg class="absolute left-3.5 w-4 h-4 pointer-events-none" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk…"
                    class="w-full pl-10 pr-24 py-2.5 rounded-full text-sm font-medium outline-none transition-all duration-200"
                    style="background: var(--color-bg-elevated); border: 1.5px solid var(--color-border); color: var(--color-text);"
                    onfocus="this.style.borderColor='var(--color-primary)';this.style.boxShadow='0 0 0 3px rgba(217,119,6,0.12)'"
                    onblur="this.style.borderColor='var(--color-border)';this.style.boxShadow='none'">
                <button type="submit"
                    class="absolute right-1 px-4 py-1.5 rounded-full text-xs font-bold text-white transition-all duration-200"
                    style="background: var(--gradient-aurora);">
                    Cari
                </button>
            </form>
        </div>

        {{-- Subcategory (Merek) — komponen Firman --}}
        <x-catalog.sub-category
            :title="$activeCategoryName"
            :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z\'></path></svg>'"
            :subcategories="$subcategories"
        />

        <div class="flex flex-col lg:flex-row gap-6 mt-6">

            {{-- ── LEFT BANNER ── --}}
            <div class="w-full lg:w-72 xl:w-64 flex-shrink-0">

                {{-- Promo card --}}
                <x-ui.glass-card variant="aurora" padding="none"
                    class="relative overflow-hidden rounded-2xl h-72 lg:h-[480px] flex items-end">

                    {{-- Background image --}}
                    <img src="https://png.pngtree.com/background/20211215/original/pngtree-blue-cartoon-stationery-background-picture-image_1461736.jpg"
                        class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-multiply"
                        alt="Banner ATK">

                    {{-- Gold overlay gradient --}}
                    <div class="absolute inset-0 opacity-50"
                         style="background: linear-gradient(135deg, rgba(217,119,6,0.5) 0%, rgba(251,191,36,0.2) 100%);"></div>

                    {{-- Bottom content --}}
                    <div class="relative z-10 p-5 w-full"
                         style="background: linear-gradient(to top, rgba(120,53,15,0.95) 0%, rgba(120,53,15,0.7) 60%, transparent 100%);">
                        <div class="backdrop-blur-sm rounded-xl p-4 text-center"
                             style="background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);">
                            <div class="text-xs font-bold uppercase tracking-widest mb-1 font-display"
                                 style="color: var(--accent-amber);">Promo Hari Ini</div>
                            <h3 class="text-white font-extrabold text-lg leading-tight mb-1 font-display">
                                {{ $activeCategoryName }}<br>Terlengkap
                            </h3>
                            <p class="text-white/70 text-xs font-light">Gratis ongkir min. Rp 150.000</p>
                        </div>
                    </div>
                </x-ui.glass-card>

                {{-- Info card di bawah banner --}}
                <x-ui.glass-card variant="default" padding="sm" class="mt-4 hidden lg:block">
                    <div class="flex items-center gap-3 py-1">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: rgba(217,119,6,0.12);">
                            <svg class="w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[var(--color-text)] font-display">Stok Selalu Ada</div>
                            <div class="text-xs text-[var(--color-text-muted)] font-light">Update setiap hari</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 py-1 mt-1">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: rgba(217,119,6,0.12);">
                            <svg class="w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[var(--color-text)] font-display">Produk Original</div>
                            <div class="text-xs text-[var(--color-text-muted)] font-light">Garansi keaslian</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 py-1 mt-1">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background: rgba(217,119,6,0.12);">
                            <svg class="w-4 h-4" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[var(--color-text)] font-display">Proses Cepat</div>
                            <div class="text-xs text-[var(--color-text-muted)] font-light">Siap kirim hari ini</div>
                        </div>
                    </div>
                </x-ui.glass-card>
            </div>

            {{-- ── RIGHT PRODUCT GRID ── --}}
            <div class="flex-1 min-w-0">

                {{-- Sort + hasil --}}
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <p class="text-sm text-[var(--color-text-muted)] font-light">
                        @if($dbProductBrands->total() > 0)
                            <span class="font-semibold text-[var(--color-text)]">{{ $dbProductBrands->total() }}</span> produk ditemukan
                            @if(request('search'))
                                untuk "<span class="text-[var(--color-primary)] font-semibold">{{ request('search') }}</span>"
                            @endif
                        @else
                            Tidak ada produk ditemukan
                        @endif
                    </p>

                    <form method="GET" action="{{ url('/katalog') }}" id="sortForm">
                        @foreach(request()->except('sort') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <select name="sort" onchange="document.getElementById('sortForm').submit()"
                            class="text-sm font-medium rounded-xl px-4 py-2 outline-none cursor-pointer"
                            style="background: var(--color-bg-elevated); border: 1px solid var(--color-border); color: var(--color-text);">
                            <option value="terbaru"  {{ request('sort','terbaru')=='terbaru'  ? 'selected':'' }}>Terbaru</option>
                            <option value="termurah" {{ request('sort')=='termurah' ? 'selected':'' }}>Harga Termurah</option>
                            <option value="termahal" {{ request('sort')=='termahal' ? 'selected':'' }}>Harga Termahal</option>
                        </select>
                    </form>
                </div>

                {{-- Product grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-5">
                    @forelse($products as $product)
                        <x-catalog.product-card
                            :product="$product['name']"
                            :brand="$product['brand']"
                            :price="$product['price']"
                            :id="$product['id']"
                            :originalPrice="$product['original_price']"
                            :badge="$product['badge']"
                            :image="$product['image']"
                        />
                    @empty
                        <div class="col-span-full py-16 text-center">
                            <x-ui.glass-card variant="frosted" padding="lg" class="max-w-sm mx-auto">
                                <div class="w-14 h-14 rounded-2xl mx-auto mb-4 flex items-center justify-center"
                                     style="background: rgba(217,119,6,0.1); border: 1px solid rgba(217,119,6,0.2);">
                                    <svg class="w-7 h-7" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-[var(--color-text)] mb-2 font-display">Produk tidak ditemukan</h3>
                                <p class="text-sm text-[var(--color-text-muted)] font-light mb-4">
                                    Belum ada produk untuk kategori atau merek ini.
                                </p>
                                <a href="{{ url('/katalog') }}"
                                   class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-sm font-bold text-white"
                                   style="background: var(--gradient-aurora);">
                                    Lihat Semua Produk
                                </a>
                            </x-ui.glass-card>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($dbProductBrands->hasPages())
                <div class="mt-10 flex flex-col items-center gap-3">
                    <p class="text-xs text-[var(--color-text-muted)] font-light">
                        Halaman {{ $dbProductBrands->currentPage() }} dari {{ $dbProductBrands->lastPage() }}
                    </p>
                    <div class="flex items-center gap-2 flex-wrap justify-center">
                        {{-- Prev --}}
                        @if($dbProductBrands->onFirstPage())
                            <span class="px-4 py-2 rounded-xl text-sm font-medium opacity-40 cursor-not-allowed"
                                  style="background: var(--color-bg-elevated); border: 1px solid var(--color-border); color: var(--color-text-muted);">
                                ← Prev
                            </span>
                        @else
                            <a href="{{ $dbProductBrands->previousPageUrl() }}"
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                               style="background: var(--color-bg-elevated); border: 1px solid var(--color-border); color: var(--color-text);"
                               onmouseover="this.style.borderColor='var(--color-primary)'"
                               onmouseout="this.style.borderColor='var(--color-border)'">
                                ← Prev
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($dbProductBrands->getUrlRange(max(1,$dbProductBrands->currentPage()-2), min($dbProductBrands->lastPage(),$dbProductBrands->currentPage()+2)) as $page => $url)
                            @if($page == $dbProductBrands->currentPage())
                                <span class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-bold text-white"
                                      style="background: var(--gradient-aurora); box-shadow: 0 4px 12px rgba(217,119,6,0.3);">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="w-9 h-9 flex items-center justify-center rounded-xl text-sm font-medium transition-all duration-200"
                                   style="background: var(--color-bg-elevated); border: 1px solid var(--color-border); color: var(--color-text);"
                                   onmouseover="this.style.borderColor='var(--color-primary)'"
                                   onmouseout="this.style.borderColor='var(--color-border)'">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($dbProductBrands->hasMorePages())
                            <a href="{{ $dbProductBrands->nextPageUrl() }}"
                               class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-200"
                               style="background: var(--color-bg-elevated); border: 1px solid var(--color-border); color: var(--color-text);"
                               onmouseover="this.style.borderColor='var(--color-primary)'"
                               onmouseout="this.style.borderColor='var(--color-border)'">
                                Next →
                            </a>
                        @else
                            <span class="px-4 py-2 rounded-xl text-sm font-medium opacity-40 cursor-not-allowed"
                                  style="background: var(--color-bg-elevated); border: 1px solid var(--color-border); color: var(--color-text-muted);">
                                Next →
                            </span>
                        @endif
                    </div>
                </div>
                @endif

            </div>{{-- end right --}}
        </div>{{-- end flex --}}
    </div>{{-- end max-w --}}

</x-layouts.app>