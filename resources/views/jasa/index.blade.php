<x-layouts.app title="Layanan Jasa Percetakan - Sinergi">
    <!-- Top Category Nav -->
    <x-catalog.category-nav :categories="$categories" active="Pencetakan Dokumen" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        <!-- Breadcrumb & Subcategory (for Jasa) -->
        <x-catalog.sub-category title="Layanan Percetakan Digital" :icon="'<svg class=\'w-6 h-6\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z\'></path></svg>'" :subcategories="['Print Hitam Putih', 'Print Warna', 'Print Kertas Khusus', 'Laminasi']" />

        <div class="mt-8 mb-16 text-center lg:text-left">
            <h1 class="text-3xl font-bold text-slate-800 mb-4">Percetakan Dokumen Cepat & Presisi</h1>
            <p class="text-slate-600 max-w-3xl">Pilih spesifikasi pencetakan yang Anda butuhkan. Kami menggunakan mesin
                beresolusi tinggi untuk menjamin hasil teks yang tajam dan warna yang akurat sesuai standar perusahaan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            @forelse($dbServices as $service)
                <x-ui.glass-card class="p-6 flex flex-col items-start hover:border-brand-primary/50 group">
                    <div
                        class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-4 text-brand-dark group-hover:bg-brand-primary group-hover:text-white transition-colors">
                        @if(!empty($service->attachments) && count($service->attachments) > 0)
                            <img src="{{ Storage::url($service->attachments[0]) }}" alt="{{ $service->name }}"
                                class="w-full h-full object-cover rounded-xl" />
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $service->name }}</h3>
                    <p class="text-sm text-slate-600 mb-6 flex-grow">
                        {{ $service->description ?? 'Layanan Jasa Percetakan Sinergi.' }}</p>
                    <div class="w-full flex items-center justify-between border-t border-slate-100 pt-4 mt-auto">
                        <div>
                            <span class="text-xs text-slate-500 block">Harga</span>
                            <span class="text-brand-dark font-bold text-lg">Rp
                                {{ number_format($service->piece_price, 0, ',', '.') }} <span
                                    class="text-xs font-normal text-slate-500">/ pcs</span></span>
                        </div>
                        <form action="{{ url('/cart/add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="service">
                            <input type="hidden" name="id" value="{{ $service->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit"
                                class="bg-brand-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-brand-primary transition">Pesan</button>
                        </form>
                    </div>
                </x-ui.glass-card>
            @empty
                <div class="col-span-full py-16 text-center text-slate-500">
                    Belum ada layanan jasa dicatat
                </div>
            @endforelse
        </div>
        @if($dbServices->hasPages())
            <div class="mt-8 mb-12">
                {{ $dbServices->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>