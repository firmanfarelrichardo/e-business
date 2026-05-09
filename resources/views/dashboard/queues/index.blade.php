@extends('components.layouts.dashboard')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Antrian Order</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pesanan dari member secara real-time</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- TABS -->
    <div class="flex gap-4 border-b border-gray-200 mb-6">
        <a href="?status=active"
            class="px-4 py-2 border-b-2 font-medium text-sm transition {{ $statusFilter === 'active' ? 'border-[#7B9B6F] text-[#7B9B6F]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Semua
            Aktif</a>
        <a href="?status=pending"
            class="px-4 py-2 border-b-2 font-medium text-sm transition {{ $statusFilter === 'pending' ? 'border-[#7B9B6F] text-[#7B9B6F]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            Menunggu
            @if($pendingCount > 0)
                <span class="ml-1 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="?status=processing"
            class="px-4 py-2 border-b-2 font-medium text-sm transition {{ $statusFilter === 'processing' ? 'border-[#7B9B6F] text-[#7B9B6F]' : 'border-transparent text-gray-500 hover:text-gray-700' }}">Sedang
            Diproses</a>
    </div>

    <!-- QUEUE GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 rounded-xl bg-[#E8F0E5] text-[#5A6852] font-bold text-lg flex items-center justify-center border border-[#D5E1D1] shadow-sm">
                                #{{ $order->queue_number ?? '-' }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-800">{{ $order->user->name ?? 'Customer' }}</div>
                                <div class="text-[10px] text-gray-400 font-mono">{{ $order->order_number }} •
                                    {{ $order->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div>
                            @if($order->status === 'pending')
                                <span
                                    class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-md border border-amber-200">Menunggu</span>
                            @elseif($order->status === 'processing')
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-md border border-blue-200">Diproses</span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2 mb-4 bg-gray-50 rounded-xl p-3 border border-gray-100">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 font-medium">
                                        @if($item->product_brand_id)
                                            {{ optional(optional($item->productBrand)->product)->name }}
                                            ({{ optional(optional($item->productBrand)->brand)->name }})
                                        @elseif($item->service_id)
                                            [JASA] {{ optional($item->service)->name }}
                                        @endif
                                    </span>
                                    <span class="text-gray-400">x{{ $item->quantity }}</span>
                                </div>
                                <span class="text-gray-700 font-mono">Rp
                                    {{ number_format($item->subtotal_price, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                        <div class="pt-2 mt-2 border-t border-gray-200 flex justify-between font-bold text-sm text-gray-800">
                            <span>TOTAL</span>
                            <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($order->note)
                        <div class="text-xs text-gray-500 italic mb-4 bg-yellow-50 p-2 rounded-lg border border-yellow-100">
                            <span class="font-semibold text-yellow-800">Catatan:</span> {{ $order->note }}
                        </div>
                    @endif
                </div>

                <div class="flex gap-2 border-t border-gray-100 pt-4 mt-auto">
                    @if($order->status === 'pending')
                        <form action="{{ route('dashboard.queues.status', $order->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="processing">
                            <button type="submit"
                                class="w-full bg-[#7B9B6F] hover:bg-[#5A6852] text-white py-2 rounded-xl text-sm font-semibold transition shadow-sm">Proses
                                Pesanan</button>
                        </form>
                        <form action="{{ route('dashboard.queues.status', $order->id) }}" method="POST" class="flex-none">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" onclick="return confirm('Batal pesanan ini?')"
                                class="px-4 py-2 border border-red-200 text-red-500 hover:bg-red-50 rounded-xl text-sm font-medium transition">Tolak</button>
                        </form>
                    @elseif($order->status === 'processing')
                        <form action="{{ route('dashboard.queues.status', $order->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button type="submit"
                                class="w-full bg-[#7B9B6F] hover:bg-[#5A6852] text-white py-2 rounded-xl text-sm font-semibold transition shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Selesai
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center text-gray-400 bg-white rounded-2xl border border-gray-100 shadow-sm">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                <p class="text-sm">Tidak ada pesanan aktif saat ini.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>

@endsection