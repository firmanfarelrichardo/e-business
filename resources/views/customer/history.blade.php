<x-layouts.app title="Histori Pesanan - Sinergi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-8">
        <div class="flex items-center justify-between mb-8">
            <h1 class="font-display font-bold text-[var(--color-text)]" style="font-size: clamp(1.75rem, 2vw + 1rem, 2.25rem);">Histori Pesanan</h1>
        </div>

        <div class="w-full">
            <x-ui.glass-card variant="default" padding="none" class="overflow-hidden" data-testid="history-table">
                {{-- Table Header --}}
                <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 border-b border-[var(--color-border-subtle)]" style="background: var(--color-bg-sunken);">
                    <div class="col-span-3 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">No Order</div>
                    <div class="col-span-3 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Tanggal</div>
                    <div class="col-span-2 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Total</div>
                    <div class="col-span-2 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Status</div>
                    <div class="col-span-2 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] text-right font-display">Aksi</div>
                </div>

                @forelse($orders as $order)
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-6 md:px-8 py-5 border-b border-[var(--color-border-subtle)] hover:bg-[var(--color-bg-elevated)]/50 transition-colors" data-testid="history-order-{{ $order->order_number }}">
                        <div class="col-span-1 md:col-span-3 flex items-center">
                            <h4 class="font-bold text-[var(--color-text)] text-lg md:text-sm font-mono" style="font-variant-numeric: tabular-nums;">{{ $order->order_number }}</h4>
                        </div>
                        <div class="col-span-1 md:col-span-3 text-sm text-[var(--color-text-muted)]">
                            {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}
                        </div>
                        <div class="col-span-1 md:col-span-2 font-bold text-[var(--color-text)] font-mono" style="font-variant-numeric: tabular-nums;">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            @if($order->status === 'pending')
                                <x-ui.badge variant="warning">Menunggu</x-ui.badge>
                            @elseif($order->status === 'processing')
                                <x-ui.badge variant="info">Diproses</x-ui.badge>
                            @elseif($order->status === 'completed')
                                <x-ui.badge variant="success">Selesai</x-ui.badge>
                            @else
                                <x-ui.badge variant="danger">Dibatalkan</x-ui.badge>
                            @endif
                        </div>
                        <div class="col-span-1 md:col-span-2 flex justify-end">
                            <x-ui.button variant="glass" size="xs" as="a" href="/invoice/{{ $order->id }}" data-testid="history-detail-button-{{ $order->id }}">
                                Lihat Detail
                            </x-ui.button>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: var(--color-bg-sunken);">
                            <svg class="w-10 h-10 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"></path>
                            </svg>
                        </div>
                        <p class="text-[var(--color-text-muted)] mb-4">Belum ada histori pesanan.</p>
                        <x-ui.button variant="primary" size="sm" as="a" href="{{ url('/katalog') }}" data-testid="history-empty-cta">
                            Mulai Belanja
                        </x-ui.button>
                    </div>
                @endforelse
            </x-ui.glass-card>
        </div>
    </div>
</x-layouts.app>
