@extends('components.layouts.dashboard')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[var(--color-text)]">Manajemen Antrean Kasir</h1>
            <p class="text-sm text-[var(--color-text-muted)]">Kelola dan proses pesanan yang masuk secara real-time.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-[var(--accent-teal)]/10 border border-[var(--accent-teal)]/20 text-[var(--accent-teal)] px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-[var(--accent-rose)]/10 border border-[var(--accent-rose)]/20 text-[var(--accent-rose)] px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex space-x-2 border-b border-[var(--color-border)] mb-6 overflow-x-auto">
        <a href="{{ route('dashboard.queues', ['status' => 'pending']) }}" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $statusFilter === 'pending' ? 'border-brand-primary text-[var(--color-text)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text)] hover:border-[var(--color-border)]' }}">
            Menunggu 
            @if($pendingCount > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-[var(--accent-rose)]/100 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('dashboard.queues', ['status' => 'processing']) }}" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $statusFilter === 'processing' ? 'border-brand-primary text-[var(--color-text)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text)] hover:border-[var(--color-border)]' }}">
            Diproses
        </a>
        <a href="{{ route('dashboard.queues', ['status' => 'completed']) }}" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $statusFilter === 'completed' ? 'border-brand-primary text-[var(--color-text)]' : 'border-transparent text-[var(--color-text-muted)] hover:text-[var(--color-text)] hover:border-[var(--color-border)]' }}">
            Selesai Hari Ini
        </a>
    </div>

    <!-- Orders Grid -->
    @if($orders->isEmpty())
        <x-ui.glass-card class="p-12 text-center border border-[var(--color-border-subtle)] shadow-sm rounded-3xl">
            <svg class="w-16 h-16 mx-auto mb-4 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="text-lg font-semibold text-[var(--color-text-secondary)] mb-2">Tidak Ada Antrean</h3>
            <p class="text-sm text-[var(--color-text-muted)]">Belum ada pesanan dengan status ini untuk ditampilkan.</p>
        </x-ui.glass-card>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <x-ui.glass-card class="p-5 border border-[var(--color-border-subtle)] shadow-sm rounded-[var(--radius-xl)] flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="text-xs text-[var(--color-text-muted)] mb-1">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</div>
                                <h3 class="text-xl font-black text-[var(--color-text)]">#{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</h3>
                                <div class="text-sm font-semibold text-[var(--color-text)]">{{ $order->order_number }}</div>
                            </div>
                            
                            <!-- Status Badge -->
                            @if($order->status === 'pending')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Menunggu</span>
                            @elseif($order->status === 'processing')
                                <span class="px-3 py-1 bg-[var(--accent-cyan)]/15 text-[var(--accent-cyan)] text-xs font-bold rounded-full">Diproses</span>
                            @elseif($order->status === 'completed')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Selesai</span>
                            @endif
                        </div>
                        
                        <!-- Customer & Items -->
                        <div class="mb-4">
                            <div class="text-sm text-[var(--color-text-secondary)] mb-2">
                                <span class="font-medium">Pelanggan:</span> {{ $order->user->name }}
                            </div>
                            <div class="bg-[var(--color-bg-sunken)] rounded-lg p-3 text-sm">
                                <ul class="space-y-1">
                                    @foreach($order->items as $item)
                                        @php
                                            $itemName = $item->product_brand_id ? $item->productBrand->product->name : $item->service->name;
                                        @endphp
                                        <li class="flex flex-col mb-1 pb-1 border-b border-[var(--color-border-subtle)] last:border-0 last:mb-0 last:pb-0">
                                            <div class="flex justify-between">
                                                <span class="text-[var(--color-text-secondary)] truncate mr-2">{{ $item->quantity }}x {{ $itemName }}</span>
                                                <span class="text-[var(--color-text)] font-medium">Rp{{ number_format($item->subtotal_price, 0, ',', '.') }}</span>
                                            </div>
                                            @if($item->document_path)
                                                <div class="mt-1 flex items-center gap-2">
                                                    <a href="{{ asset('storage/' . $item->document_path) }}" target="_blank" class="text-[10px] bg-[var(--color-primary)]/10 text-[var(--color-primary)] px-2 py-0.5 rounded font-bold hover:bg-[var(--color-primary)] hover:text-white transition flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                        LIHAT DOKUMEN
                                                    </a>
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="mt-4 pt-4 border-t border-[var(--color-border-subtle)] flex items-center justify-between">
                        <div class="text-lg font-bold text-[var(--color-text)]">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                        
                        <div class="flex gap-2">
                            @if($order->status === 'pending')
                                <form action="{{ route('dashboard.queues.status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="px-4 py-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white rounded-lg text-sm font-medium transition shadow-sm">
                                        Proses
                                    </button>
                                </form>
                            @elseif($order->status === 'processing')
                                <form action="{{ route('dashboard.queues.status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg text-sm font-medium transition shadow-sm" onclick="return confirm('Peringatan: Pesanan selesai akan memotong stok fisik. Lanjutkan?')">
                                        Selesaikan
                                    </button>
                                </form>
                            @elseif($order->status === 'completed')
                                <a href="{{ url('invoice/' . $order->id) }}" target="_blank" class="px-4 py-2 bg-[var(--color-bg-sunken)] hover:bg-gray-200 text-[var(--color-text)] rounded-lg text-sm font-medium transition flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    Struk
                                </a>
                            @endif
                        </div>
                    </div>
                </x-ui.glass-card>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
@endsection