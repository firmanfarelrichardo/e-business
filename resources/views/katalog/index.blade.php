<x-layouts.app title="Katalog Produk ATK - Sinergi">
    <!-- Top Category Nav -->
    <x-catalog.category-nav :categories="$categories" active="Alat Tulis" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        <!-- Breadcrumb & Subcategory -->
        <x-catalog.sub-category title="Alat Tulis" :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z\'></path></svg>'" :subcategories="$subcategories" />

        <!-- Main Content Area -->
        <div class="flex flex-col lg:flex-row gap-6 mt-6">

            <!-- Left Banner (Responsive: Hidden on small screens or full width) -->
            <div class="w-full lg:w-1/3 xl:w-1/4">
                <x-ui.glass-card variant="dark"
                    class="h-[400px] lg:h-[600px] flex items-end relative border-none rounded-2xl group">
                    <div
                        class="absolute inset-0 bg-gradient-to-tr from-brand-primary/40 to-brand-secondary/20 group-hover:scale-105 transition-transform duration-700 z-10">
                    </div>
                    <!-- Banner image from reference -->
                    <img src="https://png.pngtree.com/background/20211215/original/pngtree-blue-cartoon-stationery-background-picture-image_1461736.jpg"
                        class="absolute inset-0 w-full h-full object-cover mix-blend-multiply opacity-80"
                        alt="Alat Tulis Banner">

                    <div
                        class="relative z-20 p-8 w-full bg-gradient-to-t from-brand-dark via-brand-dark/80 to-transparent">
                        <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30 text-center">
                            <h3 class="text-white font-bold text-2xl mb-1">Stationary<br>Kebutuhan Anda.</h3>
                            <p class="text-white/80 text-sm">Write good, feel good.</p>
                        </div>
                    </div>
                </x-ui.glass-card>
            </div>

            <!-- Right Product Grid -->
            <div class="w-full lg:w-2/3 xl:w-3/4">
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
                    @forelse($products as $product)
                        <x-catalog.product-card :product="$product['name']" :brand="$product['brand']"
                            :price="$product['price']" :id="$product['id']" :originalPrice="$product['original_price']"
                            :badge="$product['badge']" :image="$product['image']" />
                    @empty
                        <div class="col-span-full py-16 text-center text-slate-500 bg-white/50 rounded-2xl">
                            Belum ada produk untuk kategori atau merek ini.
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($dbProductBrands->hasPages())
                    <div class="mt-8">
                        {{ $dbProductBrands->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Tags Row -->
        <div class="mt-8 flex flex-wrap gap-2">
            <span
                class="px-4 py-2 bg-white text-slate-600 text-xs font-semibold rounded-lg shadow-sm border border-slate-100 cursor-pointer hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-colors">#NewProduct</span>
            <span
                class="px-4 py-2 bg-white text-slate-600 text-xs font-semibold rounded-lg shadow-sm border border-slate-100 cursor-pointer hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-colors">#ClearanceSale</span>
            <span
                class="px-4 py-2 bg-white text-slate-600 text-xs font-semibold rounded-lg shadow-sm border border-slate-100 cursor-pointer hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-colors">#BestSeller</span>
            <span
                class="px-4 py-2 bg-white text-slate-600 text-xs font-semibold rounded-lg shadow-sm border border-slate-100 cursor-pointer hover:bg-brand-primary hover:text-white hover:border-brand-primary transition-colors">#MostViewed</span>
        </div>

        <!-- Brands Row -->
        <div class="mt-6 flex flex-wrap gap-4 items-center mb-12">
            @foreach(['Joyko', 'Kenko', 'SDI', 'Faber Castell', 'Standard', 'Snowman', 'Zebra'] as $brand)
                <div
                    class="bg-white px-4 py-2 rounded-lg shadow-sm border border-slate-100 flex items-center justify-center opacity-70 hover:opacity-100 cursor-pointer transition-opacity">
                    <span class="font-bold text-slate-800 text-sm uppercase tracking-widest">{{ $brand }}</span>
                </div>
            @endforeach
        </div>

    </div>
</x-layouts.app>