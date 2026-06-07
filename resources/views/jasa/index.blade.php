<x-layouts.app title="Layanan Jasa Percetakan - Sinergi">
    {{-- Top Category Nav --}}
    <x-catalog.category-nav :categories="$categories" active="Pencetakan Dokumen" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">

        {{-- Hero Header --}}
        <div class="mt-8 mb-12 text-center lg:text-left">
            <x-ui.badge variant="info" size="md" class="mb-4">Jasa Percetakan</x-ui.badge>
            <h1 class="font-display font-bold text-[var(--color-text)] mb-4" style="font-size: clamp(1.75rem, 2vw + 1rem, 2.25rem);">Percetakan Dokumen</h1>
            <p class="text-[var(--color-text-muted)] max-w-3xl text-base font-light">Pilih spesifikasi pencetakan yang Anda butuhkan. Kami menggunakan mesin
                beresolusi tinggi untuk menjamin hasil teks yang tajam dan warna yang akurat sesuai standar perusahaan.
            </p>
        </div>

        {{-- Service Tiles Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            @php
                $serviceAccents = ['cyan', 'violet', 'teal', 'amber', 'coral', 'magenta'];
                $serviceGradients = [
                    ['from' => 'var(--accent-cyan)', 'to' => 'var(--gold-400)'],
                    ['from' => 'var(--accent-violet)', 'to' => 'var(--accent-magenta)'],
                    ['from' => 'var(--accent-teal)', 'to' => 'var(--accent-mint)'],
                    ['from' => 'var(--accent-amber)', 'to' => 'var(--accent-coral)'],
                    ['from' => 'var(--accent-coral)', 'to' => 'var(--accent-rose)'],
                    ['from' => 'var(--accent-magenta)', 'to' => 'var(--accent-violet)'],
                ];
            @endphp
            @forelse($dbServices as $index => $service)
                @php $gradient = $serviceGradients[$index % count($serviceGradients)]; @endphp
                <x-ui.glass-card variant="frosted" padding="lg" class="flex flex-col items-start group motion-card" data-testid="service-card-{{ $service->id }}">
                    <div class="w-12 h-12 rounded-[var(--radius-md)] flex items-center justify-center mb-4 shadow-lg transition-transform duration-300 group-hover:scale-110"
                         style="background: linear-gradient(135deg, {{ $gradient['from'] }}, {{ $gradient['to'] }});">
                        @if(!empty($service->attachments) && count($service->attachments) > 0)
                            <img src="{{ Storage::url($service->attachments[0]) }}" alt="{{ $service->name }}"
                                class="w-full h-full object-cover rounded-[var(--radius-md)]" />
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-[var(--color-text)] mb-2 font-display">{{ $service->name }}</h3>
                    <p class="text-sm text-[var(--color-text-muted)] mb-6 flex-grow font-light">
                        {{ $service->description ?? 'Layanan Jasa Percetakan Sinergi.' }}</p>
                    <div class="w-full flex items-center justify-between border-t border-[var(--color-border-subtle)] pt-4 mt-auto">
                        <div>
                            <span class="text-[10px] text-[var(--color-text-muted)] block uppercase tracking-[0.08em] font-semibold">Harga</span>
                            <span class="text-[var(--color-text)] font-bold text-lg font-mono" style="font-variant-numeric: tabular-nums;">Rp
                                {{ number_format($service->piece_price, 0, ',', '.') }} <span
                                    class="text-xs font-normal text-[var(--color-text-muted)] font-sans">/ pcs</span></span>
                        </div>
                        <form action="{{ url('/cart/add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="service">
                            <input type="hidden" name="id" value="{{ $service->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <x-ui.button variant="primary" size="sm" type="submit" data-testid="service-add-button-{{ $service->id }}">
                                Pesan
                            </x-ui.button>
                        </form>
                    </div>
                </x-ui.glass-card>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: var(--color-bg-sunken);">
                        <svg class="w-10 h-10 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                        </svg>
                    </div>
                    <p class="text-[var(--color-text-muted)] font-medium">Belum ada layanan jasa dicatat</p>
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