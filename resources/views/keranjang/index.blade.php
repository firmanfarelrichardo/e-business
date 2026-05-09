<x-layouts.app title="Keranjang Belanja - Sinergi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-16">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Keranjang Belanja</h1>
            <p class="text-sm text-gray-500 mt-1">Periksa kembali pesanan ATK dan layanan Fotocopy Anda sebelum checkout.</p>
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

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left: Cart Items -->
            <div class="w-full lg:w-2/3">
                @if(!$cart || $cart->items->isEmpty())
                    <x-ui.glass-card class="p-12 text-center border border-gray-100 shadow-sm rounded-3xl">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <h3 class="text-lg font-semibold text-gray-600 mb-2">Keranjang Anda Masih Kosong</h3>
                        <p class="text-sm text-gray-400 mb-6">Mulai eksplorasi katalog produk dan jasa kami untuk menemukan yang Anda butuhkan.</p>
                        <a href="{{ url('/katalog') }}" class="inline-flex items-center justify-center px-6 py-3 bg-brand-primary text-white rounded-xl font-medium hover:bg-brand-dark transition shadow-sm">
                            Belanja Sekarang
                        </a>
                    </x-ui.glass-card>
                @else
                    <div class="space-y-4">
                        @foreach($cart->items as $item)
                            @php
                                $isProduct = !is_null($item->productBrand);
                                $name = $isProduct ? $item->productBrand->product->name : $item->service->name;
                                $subName = $isProduct ? $item->productBrand->brand->name . ' (' . $item->productBrand->unit . ')' : 'Jasa';
                                $price = $isProduct ? $item->productBrand->selling_price : $item->service->piece_price;
                                $subtotal = $price * $item->quantity;
                            @endphp
                            
                            <x-ui.glass-card class="p-5 border border-gray-100 shadow-sm rounded-2xl flex flex-col sm:flex-row items-start sm:items-center gap-5">
                                
                                <!-- Icon/Image Placeholder -->
                                <div class="w-16 h-16 rounded-xl shrink-0 flex items-center justify-center {{ $isProduct ? 'bg-blue-50 text-blue-500' : 'bg-emerald-50 text-emerald-500' }}">
                                    @if($isProduct)
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    @else
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                                    @endif
                                </div>

                                <!-- Item Details -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $isProduct ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ $isProduct ? 'Produk ATK' : 'Jasa' }}
                                        </span>
                                    </div>
                                    <h4 class="text-gray-800 font-bold text-base leading-tight">{{ $name }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $subName }}</p>
                                    <div class="text-sm font-bold text-brand-dark mt-2">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                </div>

                                <!-- Quantity Control & Delete -->
                                <div class="flex items-center gap-4 w-full sm:w-auto mt-4 sm:mt-0 justify-between sm:justify-end">
                                    
                                    <form action="{{ route('keranjang.update', $item->id) }}" method="POST" class="flex items-center border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 transition" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                        
                                        <div class="px-3 py-1.5 text-sm font-semibold text-gray-800 border-x border-gray-200 min-w-[2.5rem] text-center">
                                            {{ $item->quantity }}
                                        </div>

                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="px-3 py-1.5 text-gray-500 hover:bg-gray-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </form>

                                    <div class="text-right sm:hidden ml-auto mr-4">
                                        <div class="text-xs text-gray-400">Subtotal</div>
                                        <div class="text-sm font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                                    </div>

                                    <form action="{{ route('keranjang.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus Item" onsubmit="return confirm('Hapus item ini?')">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>

                                </div>
                                
                                <!-- Desktop Subtotal -->
                                <div class="hidden sm:block text-right w-24">
                                    <div class="text-xs text-gray-400 mb-1">Subtotal</div>
                                    <div class="text-sm font-bold text-gray-800">Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
                                </div>

                            </x-ui.glass-card>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Order Summary -->
            @if($cart && !$cart->items->isEmpty())
                <div class="w-full lg:w-1/3">
                    <x-ui.glass-card class="p-6 border border-gray-100 shadow-sm rounded-3xl sticky top-8">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Ringkasan Pesanan</h3>
                        
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Item</span>
                                <span class="font-semibold text-gray-800">{{ $cart->items->sum('quantity') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Total Harga</span>
                                <span class="font-semibold text-gray-800">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                            <!-- Add additional fees here if needed (e.g., service fee, tax) -->
                        </div>
                        
                        <hr class="border-gray-100 mb-6">
                        
                        <div class="flex justify-between items-end mb-8">
                            <span class="text-base font-semibold text-gray-600">Total Tagihan</span>
                            <span class="text-2xl font-extrabold text-brand-dark">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>

                        <form action="{{ route('keranjang.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-4 bg-brand-primary hover:bg-brand-dark text-white rounded-xl font-bold text-base transition shadow flex items-center justify-center gap-2 group">
                                <span>Lanjut Pembayaran</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>

                        <div class="mt-4 flex items-center justify-center gap-2 text-xs text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Checkout aman & dienkripsi
                        </div>
                    </x-ui.glass-card>
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>
