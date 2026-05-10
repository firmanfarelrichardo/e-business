<x-layouts.app title="Keranjang Belanja - Sinergi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-8">
        @if(session('success'))
            <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif
        
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ url('/katalog') }}" class="w-10 h-10 bg-white/50 rounded-full flex items-center justify-center hover:bg-white text-slate-600 shadow-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-3xl font-bold text-brand-dark">Keranjang Belanja</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Left: Cart Items -->
            <div class="w-full lg:w-2/3">
                <x-ui.glass-card class="p-0 border-none overflow-visible">
                    
                    <!-- Table Header (Hidden on Mobile) -->
                    <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 bg-white/40 border-b border-white/60 rounded-t-[2rem]">
                        <div class="col-span-6 text-sm font-bold text-slate-500 uppercase tracking-wider">Produk</div>
                        <div class="col-span-3 text-sm font-bold text-slate-500 uppercase tracking-wider text-center">Kuantitas</div>
                        <div class="col-span-3 text-sm font-bold text-slate-500 uppercase tracking-wider text-right">Subtotal</div>
                    </div>

                    <!-- Cart Items Loop -->
                    @forelse($cart as $key => $item)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-6 md:px-8 py-6 border-b border-white/40 hover:bg-white/20 transition-colors">
                            <div class="col-span-1 md:col-span-6 flex items-center gap-4">
                                <div class="w-20 h-20 bg-white rounded-xl p-2 shrink-0 border border-slate-100 shadow-sm flex items-center justify-center">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" class="w-full h-full object-contain" alt="{{ $item['name'] }}">
                                    @else
                                        @if($item['type'] === 'service')
                                            <svg class="w-10 h-10 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        @else
                                            <svg class="w-10 h-10 text-brand-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    @endif
                                </div>
                                <div>
                                    @if($item['type'] === 'service')
                                        <span class="bg-brand-tertiary text-brand-dark text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">Jasa</span>
                                    @else
                                        <span class="bg-brand-primary text-white text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider mb-1 inline-block">Produk</span>
                                    @endif
                                    <h4 class="font-bold text-slate-800 text-lg mb-1">{{ $item['name'] }}</h4>
                                    <p class="text-sm text-brand-primary font-semibold mb-2">Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">/ unit</span></p>

                                    @if($item['type'] === 'service')
                                        <div class="mt-3 p-3 bg-white/40 rounded-xl border border-white/60">
                                            <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-2">Dokumen Cetak (PDF/DOC/IMG)</label>
                                            <div class="flex items-center gap-3">
                                                <input type="file" 
                                                       id="doc-{{ $key }}" 
                                                       class="hidden" 
                                                       onchange="uploadDocument('{{ $key }}')">
                                                <button type="button" 
                                                        onclick="document.getElementById('doc-{{ $key }}').click()"
                                                        class="bg-white px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                                                    <svg class="w-3.5 h-3.5 text-brand-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                    {{ isset($item['document_path']) ? 'Ganti File' : 'Pilih File' }}
                                                </button>
                                                <div id="status-{{ $key }}" class="text-[10px] font-medium text-slate-500 truncate max-w-[150px]">
                                                    {{ $item['document_filename'] ?? 'Belum ada file' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 md:col-span-3 flex items-center justify-between md:justify-center mt-4 md:mt-0">
                                <span class="md:hidden text-sm text-slate-500 font-bold">Kuantitas:</span>
                                <div class="flex items-center gap-3 bg-white/50 px-3 py-1 rounded-lg border border-slate-200">
                                    <form action="{{ url('/cart/update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="text-slate-500 hover:text-brand-primary p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></button>
                                    </form>
                                    <span class="font-bold text-slate-800 w-6 text-center">{{ $item['quantity'] }}</span>
                                    <form action="{{ url('/cart/update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <input type="hidden" name="action" value="increase">
                                        <button class="text-slate-500 hover:text-brand-primary p-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-span-1 md:col-span-3 flex items-center justify-between md:justify-end mt-2 md:mt-0">
                                <span class="md:hidden text-sm text-slate-500 font-bold">Subtotal:</span>
                                <div class="text-right">
                                    <span class="font-bold text-slate-800 text-lg">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                            <!-- Delete action -->
                            <div class="absolute right-6 mt-[-100px] md:mt-0 md:relative md:col-span-12 md:flex justify-end hidden">
                                <form action="{{ url('/cart/remove') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="cart_key" value="{{ $key }}">
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium flex items-center gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-500">
                            Keranjang belanja Anda masih kosong. <br><br>
                            <a href="{{ url('/katalog') }}" class="mt-4 px-4 py-2 bg-brand-primary text-white rounded-lg">Mulai Belanja</a>
                        </div>
                    @endforelse
                </x-ui.glass-card>
            </div>

            <!-- Right: Order Summary -->
            <div class="w-full lg:w-1/3">
                <x-ui.glass-card variant="dark" class="p-8 sticky top-32">
                    <h3 class="text-xl font-bold text-white mb-6">Ringkasan Pesanan</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-white/80">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-white">Rp {{ number_format($totalProduk, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-white/80">
                            <span>Biaya Layanan</span>
                            <span class="font-medium text-white">Rp {{ number_format($totalJasa, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-white/20 pt-6 mb-8 flex justify-between items-end">
                        <span class="text-white/80 font-medium">Total Harga</span>
                        <span class="text-3xl font-extrabold text-white tracking-tight">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ url('/orders') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-white/80 text-sm font-medium mb-2">Catatan Pesanan (Opsional)</label>
                            <textarea name="note" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-brand-tertiary focus:border-transparent transition-all" rows="3" placeholder="Misal: Tolong dipisahkan plastiknya..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-brand-primary to-brand-secondary hover:from-brand-secondary hover:to-brand-primary text-brand-dark font-bold text-lg py-4 rounded-xl shadow-lg hover:shadow-brand-primary/50 transform hover:-translate-y-1 transition duration-300">
                            Proses Checkout
                        </button>
                    </form>
                    
                    <p class="text-center text-white/50 text-xs mt-4">
                        <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                        Pembayaran dienkripsi secara aman
                    </p>
                </x-ui.glass-card>
            </div>
            
        </div>
    </div>

    <script>
        function uploadDocument(cartKey) {
            const fileInput = document.getElementById('doc-' + cartKey);
            const statusDiv = document.getElementById('status-' + cartKey);
            
            if (!fileInput.files.length) return;
            
            const formData = new FormData();
            formData.append('cart_key', cartKey);
            formData.append('document', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');
            
            statusDiv.innerHTML = '<span class="text-brand-primary animate-pulse">Mengupload...</span>';
            
            fetch('{{ url("/cart/upload-document") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusDiv.innerHTML = '<span class="text-green-600 font-bold">✓ ' + data.filename + '</span>';
                } else {
                    statusDiv.innerHTML = '<span class="text-red-500">' + (data.message || 'Gagal upload') + '</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusDiv.innerHTML = '<span class="text-red-500">Error sistem</span>';
            });
        }
    </script>
</x-layouts.app>
