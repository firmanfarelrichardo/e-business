<x-layouts.app title="Detail Pesanan - Sinergi">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-8">
        <div class="flex items-center gap-3 mb-8 print:hidden">
            @php
                $backUrl = (auth()->check() && auth()->user()->role !== 'member') ? url('/dashboard/queues') : url('/history');
            @endphp
            <a href="{{ $backUrl }}"
                class="w-10 h-10 bg-white/50 rounded-full flex items-center justify-center hover:bg-white text-slate-600 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-brand-dark">Detail Pesanan</h1>
        </div>

        <x-ui.glass-card class="p-8">
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-white/40 pb-6 mb-6">
                <div>
                    <p class="text-sm text-slate-500 mb-1">No. Order</p>
                    <h2 class="text-2xl font-bold text-slate-800">{{ $order->order_number }}</h2>
                    <p class="text-sm font-semibold text-slate-600 mt-2">
                        {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Tanggal tidak tersedia' }}
                    </p>                    </p>
                </div>
                <div class="mt-4 md:mt-0 text-right">
                    @if($order->status === 'pending')
                        <span
                            class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider block mb-2">Menunggu
                            Pembayaran</span>
                    @elseif($order->status === 'processing')
                        <span
                            class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider block mb-2">Sedang
                            Diproses</span>
                    @elseif($order->status === 'completed')
                        <span
                            class="bg-brand-primary text-white px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider block mb-2">Pesanan
                            Selesai</span>
                    @else
                        <span
                            class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm font-bold uppercase tracking-wider block mb-2">Dibatalkan</span>
                    @endif
                    <button onclick="window.print()"
                        class="text-brand-primary hover:text-brand-dark text-sm font-semibold transition flex items-center gap-1 justify-end ml-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                            </path>
                        </svg>
                        Cetak Invoice
                    </button>
                </div>
            </div>

            <h3 class="text-lg font-bold text-brand-dark mb-4 mt-2">Daftar Item</h3>
            <div class="space-y-4 mb-8">
                @foreach($order->items as $item)
                    <div class="flex justify-between items-center bg-white/50 p-4 rounded-xl border border-white/60">
                        <div>
                            @if(isset($item->service))
                                <span
                                    class="bg-brand-tertiary text-brand-dark text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">Jasa</span>
                                <h4 class="font-bold text-slate-800">{{ $item->service->name }}</h4>
                            @elseif(isset($item->productBrand))
                                <span
                                    class="bg-brand-primary text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">Produk</span>
                                <h4 class="font-bold text-slate-800">{{ $item->productBrand->product->name }}
                                    ({{ $item->productBrand->brand->name }})</h4>
                            @else
                                <h4 class="font-bold text-slate-800">Item Produk/Jasa</h4>
                            @endif
                            <p class="text-sm font-semibold text-slate-500 mt-1">{{ $item->quantity }} x Rp
                                {{ number_format($item->price_per_unit, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right font-bold text-slate-800">
                            Rp {{ number_format($item->subtotal_price, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-white/60 pt-6 flex justify-between items-end">
                <span class="text-slate-600 font-bold uppercase tracking-wider">Total Pembayaran</span>
                <span class="text-3xl font-extrabold text-brand-dark tracking-tight">Rp
                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
            @if($order->transaction)
                <div class="mt-6 border-t border-white/40 pt-5">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Status Pembayaran</span>
                        @php
                            $ts = $order->transaction->transaction_status;
                            $tsBadge = match($ts) {
                                'paid', 'settled' => 'bg-green-100 text-green-700',
                                'pending'         => 'bg-yellow-100 text-yellow-700',
                                'expired'         => 'bg-red-100 text-red-600',
                                'failed'          => 'bg-red-100 text-red-600',
                                default           => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <span class="text-xs font-bold px-3 py-1 rounded-full uppercase {{ $tsBadge }}">{{ strtoupper($ts) }}</span>
                    </div>
                    <p class="text-xs text-slate-400 mb-1">Kode: <span class="font-mono font-semibold text-slate-500">{{ $order->transaction->transaction_code }}</span></p>
                </div>
            @endif

            @if($order->status == 'pending')
                @if($order->transaction && $order->transaction->payment_url)
                    <div class="mt-6 bg-blue-50/60 border border-blue-100 rounded-xl p-6 text-center">
                        <p class="text-blue-800 text-sm font-medium mb-4">Selesaikan pembayaran Anda melalui halaman Xendit (pilihan VA, e-Wallet, QRIS tersedia).</p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                            <a href="{{ $order->transaction->payment_url }}" target="_blank"
                                class="inline-block bg-[#0256ba] hover:bg-[#024aa0] transition-colors text-white font-bold px-6 py-3 rounded-lg shadow-md">
                                💳 Bayar Sekarang (Xendit)
                            </a>
                            {{-- Simulator: only shown in local APP_ENV for dev testing without Ngrok --}}
                            @if(app()->environment('local'))
                                <a href="{{ url('/simulate-payment/' . $order->transaction->transaction_code) }}"
                                    onclick="return confirm('Simulasikan pembayaran berhasil? (Hanya untuk testing lokal)')"
                                    class="inline-block bg-emerald-500 hover:bg-emerald-600 transition-colors text-white font-bold px-6 py-3 rounded-lg shadow-md">
                                    🧪 Simulasi Bayar (Sandbox)
                                </a>
                            @endif
                        </div>
                    </div>
                @elseif($order->transaction)
                    <div class="mt-6 bg-yellow-50/60 border border-yellow-200 rounded-xl p-5 text-center">
                        <p class="text-yellow-800 text-sm font-medium">Memproses link pembayaran… Silakan refresh halaman sebentar lagi.</p>
                        <button onclick="window.location.reload()" class="mt-3 text-xs text-yellow-700 underline">Refresh Sekarang</button>
                    </div>
                @endif
            @endif
        </x-ui.glass-card>
    </div>
</x-layouts.app>