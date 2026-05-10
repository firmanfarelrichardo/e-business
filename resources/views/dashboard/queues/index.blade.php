@extends('components.layouts.dashboard')

@section('content')
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Antrean Kasir</h1>
            <p class="text-sm text-gray-500">Kelola dan proses pesanan yang masuk secara real-time.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex space-x-2 border-b border-gray-200 mb-6 overflow-x-auto">
        <a href="{{ route('dashboard.queues', ['status' => 'pending']) }}" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $statusFilter === 'pending' ? 'border-brand-primary text-brand-dark' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Menunggu 
            @if($pendingCount > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('dashboard.queues', ['status' => 'processing']) }}" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $statusFilter === 'processing' ? 'border-brand-primary text-brand-dark' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Diproses
        </a>
        <a href="{{ route('dashboard.queues', ['status' => 'completed']) }}" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap {{ $statusFilter === 'completed' ? 'border-brand-primary text-brand-dark' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
            Selesai Hari Ini
        </a>
    </div>

    <!-- Orders Grid -->
    @if($orders->isEmpty())
        <x-ui.glass-card class="p-12 text-center border border-gray-100 shadow-sm rounded-3xl">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Tidak Ada Antrean</h3>
            <p class="text-sm text-gray-400">Belum ada pesanan dengan status ini untuk ditampilkan.</p>
        </x-ui.glass-card>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($orders as $order)
                <x-ui.glass-card class="p-5 border border-gray-100 shadow-sm rounded-2xl flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <!-- Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y H:i') }}</div>
                                <h3 class="text-xl font-black text-brand-dark">#{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}</h3>
                                <div class="text-sm font-semibold text-gray-700">{{ $order->order_number }}</div>
                            </div>
                            
                            <!-- Status Badge -->
                            @if($order->status === 'pending')
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">Menunggu</span>
                            @elseif($order->status === 'processing')
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Diproses</span>
                            @elseif($order->status === 'completed')
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full">Selesai</span>
                            @endif
                        </div>
                        
                        <!-- Customer & Items -->
                        <div class="mb-4">
                            <div class="text-sm text-gray-600 mb-2">
                                <span class="font-medium">Pelanggan:</span> {{ $order->user->name }}
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-sm">
                                <ul class="space-y-1">
                                    @foreach($order->items as $item)
                                        @php
                                            $itemName = $item->product_brand_id ? $item->productBrand->product->name : $item->service->name;
                                        @endphp
                                        <li class="flex flex-col mb-1 pb-1 border-b border-gray-100 last:border-0 last:mb-0 last:pb-0">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 truncate mr-2">{{ $item->quantity }}x {{ $itemName }}</span>
                                                <span class="text-gray-800 font-medium">Rp{{ number_format($item->subtotal_price, 0, ',', '.') }}</span>
                                            </div>
                                            @if($item->document_path)
                                                <div class="mt-1 flex items-center gap-2">
                                                    <a href="{{ asset('storage/' . $item->document_path) }}" target="_blank" class="text-[10px] bg-brand-primary/10 text-brand-primary px-2 py-0.5 rounded font-bold hover:bg-brand-primary hover:text-white transition flex items-center gap-1">
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
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-lg font-bold text-gray-800">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </div>
                        
                        <div class="flex gap-2">
                            @if($order->status === 'pending')
                                <form action="{{ route('dashboard.queues.status', $order->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">
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
                                <a href="{{ url('invoice/' . $order->id) }}" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition flex items-center gap-1">
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