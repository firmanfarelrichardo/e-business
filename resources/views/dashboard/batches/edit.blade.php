<x-layouts.dashboard title="Edit Batch Stok">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Batch Stok</h1>
        <p class="text-sm text-gray-500">Perbaiki kesalahan input stok awal dan harga beli. Perhatikan batas minimum edit.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Form Area -->
        <div class="w-full lg:w-2/3">
            <x-ui.glass-card class="p-6 border border-gray-100 shadow-sm rounded-3xl">
                <form action="{{ route('dashboard.batches.update', $batch->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Readonly Info -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Produk Varian (Tidak bisa diubah)</label>
                            <div class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600 text-sm font-medium">
                                {{ $batch->productBrand->product->name }} - {{ $batch->productBrand->brand->name }} ({{ $batch->productBrand->unit }})
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Batch (Tidak bisa diubah)</label>
                            <div class="px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-600 text-sm font-medium">
                                {{ $batch->batch_code }}
                            </div>
                        </div>

                        <!-- Editable Fields -->
                        <div>
                            <label for="initial_stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok Awal Masuk</label>
                            <input type="number" id="initial_stock" name="initial_stock" value="{{ old('initial_stock', $batch->initial_stock) }}" min="{{ $soldQty > 0 ? $soldQty : 1 }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-brand-primary focus:ring focus:ring-brand-primary/20 transition bg-white text-gray-800">
                            @error('initial_stock')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-amber-600 mt-1">Minimal: {{ $soldQty }} (Karena barang sudah terjual)</p>
                        </div>

                        <div>
                            <label for="purchase_price" class="block text-sm font-semibold text-gray-700 mb-2">Harga Beli Dasar (Rp)</label>
                            <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $batch->purchase_price) }}" min="0" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-brand-primary focus:ring focus:ring-brand-primary/20 transition bg-white text-gray-800">
                            @error('purchase_price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                        <a href="{{ route('dashboard.batches') }}" class="px-6 py-3 text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">Batal</a>
                        <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-brand-primary hover:bg-brand-dark rounded-xl transition shadow">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </x-ui.glass-card>
        </div>

        <!-- Information Sidebar -->
        <div class="w-full lg:w-1/3">
            <x-ui.glass-card class="p-6 border border-amber-200 bg-amber-50/50 shadow-sm rounded-3xl sticky top-8">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-lg font-bold text-amber-800">Status Stok Batch Ini</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                        <span class="text-sm text-amber-700">Stok Masuk Lama</span>
                        <span class="font-bold text-amber-900">{{ $batch->initial_stock }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-amber-100 pb-2">
                        <span class="text-sm text-red-600 font-semibold">Barang Terjual</span>
                        <span class="font-bold text-red-600">-{{ $soldQty }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-emerald-700 font-semibold">Sisa Stok (Current)</span>
                        <span class="font-bold text-emerald-700 text-xl">{{ $batch->current_stock }}</span>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-white/60 rounded-xl text-xs text-amber-800 leading-relaxed border border-amber-100">
                    <strong>Penting:</strong> Mengurangi "Stok Awal Masuk" di bawah jumlah yang sudah laku akan membuat sistem mendeteksi barang negatif (Negative Stock). Sistem secara otomatis mencegah Anda menginput di bawah angka barang yang telah terjual.
                </div>
            </x-ui.glass-card>
        </div>

    </div>
</x-layouts.dashboard>
