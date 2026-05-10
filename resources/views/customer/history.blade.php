<x-layouts.app title="Histori Pesanan - Sinergi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-8">
        <div class="flex items-center gap-3 mb-8">
            <h1 class="text-3xl font-bold text-brand-dark">Histori Pesanan</h1>
        </div>

        <div class="w-full">
            <x-ui.glass-card class="p-0 border-none overflow-hidden">
                <!-- Table Header (Hidden on Mobile) -->
                <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-white/40 border-b border-white/60">
                    <div class="col-span-3 text-sm font-bold text-slate-500 uppercase tracking-wider">No Order</div>
                    <div class="col-span-3 text-sm font-bold text-slate-500 uppercase tracking-wider">Tanggal</div>
                    <div class="col-span-2 text-sm font-bold text-slate-500 uppercase tracking-wider">Total</div>
                    <div class="col-span-2 text-sm font-bold text-slate-500 uppercase tracking-wider">Status</div>
                    <div class="col-span-2 text-sm font-bold text-slate-500 uppercase tracking-wider text-right">Aksi
                    </div>
                </div>

                @forelse($orders as $order)
                    <div
                        class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-6 md:px-8 py-6 border-b border-white/40 hover:bg-white/20 transition-colors">
                        <div class="col-span-1 md:col-span-3 flex items-center">
                            <h4 class="font-bold text-slate-800 text-lg md:text-base">{{ $order->order_number }}</h4>
                        </div>
                        <div class="col-span-1 md:col-span-3 text-sm text-slate-600">
                            {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}
                        </div>
                        <div class="col-span-1 md:col-span-2 font-bold text-brand-dark">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            @if($order->status === 'pending')
                                <span
                                    class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu</span>
                            @elseif($order->status === 'processing')
                                <span
                                    class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Diproses</span>
                            @elseif($order->status === 'completed')
                                <span
                                    class="bg-brand-primary text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Selesai</span>
                            @else
                                <span
                                    class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">Dibatalkan</span>
                            @endif
                        </div>
                        <div class="col-span-1 md:col-span-2 flex justify-end">
                            <a href="/invoice/{{ $order->id }}"
                                class="text-sm font-semibold text-brand-primary hover:text-brand-dark transition bg-white/50 px-4 py-2 rounded-lg border border-slate-200">Lihat
                                Detail</a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-500">
                        Belum ada histori pesanan. <br><br>
                        <a href="{{ url('/katalog') }}"
                            class="mt-4 inline-block px-4 py-2 bg-brand-primary text-white rounded-lg">Mulai Belanja</a>
                    </div>
                @endforelse
            </x-ui.glass-card>
        </div>
    </div>
</x-layouts.app>
