@extends('components.layouts.dashboard')

@section('content')

    @push('scripts')
        <script type="module">
            import { animate, stagger } from "https://esm.sh/motion";
            animate(".motion-title", { y: [20, 0], opacity: [0, 1] }, { duration: 0.5, easing: "ease-out" });
            animate(".motion-stat-card", { y: [30, 0], opacity: [0, 1] }, { delay: stagger(0.1), duration: 0.5, easing: "ease-out" });
            animate(".motion-table-row", { y: [15, 0], opacity: [0, 1] }, { delay: stagger(0.06), duration: 0.4, easing: "ease-out" });
        </script>
    @endpush

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight motion-title">Manajemen Batch Stok</h1>
        <p class="text-sm text-gray-500 motion-title mt-1">Kelola stok masuk per-batch, pantau pergerakan inventori & kalkulasi WAC otomatis</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm motion-title flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm motion-title flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm motion-title">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- Summary Stat Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Total Batches --}}
        <div class="motion-stat-card glass-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-brand-primary/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Batch</p>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($totalBatches) }}</p>
                </div>
            </div>
        </div>

        {{-- Active Batches --}}
        <div class="motion-stat-card glass-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Batch Aktif</p>
                    <p class="text-xl font-bold text-emerald-700">{{ number_format($activeBatches) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Stock Units --}}
        <div class="motion-stat-card glass-card rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Total Unit Tersedia</p>
                    <p class="text-xl font-bold text-blue-700">{{ number_format($totalStockUnits) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Control Panel: Search + Add Button --}}
    <div class="motion-title bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <form method="GET" action="{{ route('dashboard.batches') }}" class="relative w-full md:w-96 flex gap-2">
            <div class="relative flex-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode batch, produk, brand..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
            </div>
            <button type="submit" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-sm font-medium transition">Cari</button>
            @if(request('search'))
                <a href="{{ route('dashboard.batches') }}" class="px-3 py-2.5 text-gray-400 hover:text-gray-600 text-sm transition">Reset</a>
            @endif
        </form>

        <button onclick="document.getElementById('modal-batch').classList.remove('hidden')"
            class="w-full md:w-auto text-black border border-gray-200 px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Batch Stok
        </button>
    </div>

    {{-- Batch Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden motion-title">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode Batch</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Produk / Varian</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Stok Awal</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Stok Saat Ini</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Harga Beli</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat</th>
                        <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($batches as $batch)
                        @php
                            $stockPercentage = $batch->initial_stock > 0 ? ($batch->current_stock / $batch->initial_stock) * 100 : 0;
                            $stockColor = $stockPercentage > 50 ? 'bg-emerald-500' : ($stockPercentage > 20 ? 'bg-amber-500' : 'bg-red-500');
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition motion-table-row">
                            {{-- Batch Code --}}
                            <td class="py-4 px-5">
                                <span class="font-mono text-sm font-medium text-gray-800 bg-gray-100 px-2.5 py-1 rounded-lg">{{ $batch->batch_code }}</span>
                            </td>

                            {{-- Product + Brand --}}
                            <td class="py-4 px-5">
                                <div class="text-sm font-medium text-gray-800">{{ optional(optional($batch->productBrand)->product)->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ optional(optional($batch->productBrand)->brand)->name ?? '-' }} · {{ optional($batch->productBrand)->unit ?? '' }}</div>
                            </td>

                            {{-- Initial Stock --}}
                            <td class="py-4 px-5 text-center">
                                <span class="text-sm text-gray-600 font-medium">{{ number_format($batch->initial_stock) }}</span>
                            </td>

                            {{-- Current Stock with Progress Bar --}}
                            <td class="py-4 px-5">
                                <div class="flex flex-col items-center gap-1.5">
                                    <span class="text-sm font-bold {{ $batch->current_stock <= 0 ? 'text-red-500' : 'text-gray-800' }}">{{ number_format($batch->current_stock) }}</span>
                                    <div class="w-20 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="{{ $stockColor }} h-full rounded-full transition-all" style="width: {{ min($stockPercentage, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>

                            {{-- Purchase Price --}}
                            <td class="py-4 px-5 text-right">
                                <span class="text-sm font-semibold text-gray-800">Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}</span>
                            </td>

                            {{-- Active Status --}}
                            <td class="py-4 px-5 text-center">
                                @if($batch->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- Created Info --}}
                            <td class="py-4 px-5">
                                <div class="text-xs text-gray-500">{{ optional($batch->created_at)->format('d M Y') }}</div>
                                <div class="text-[10px] text-gray-400 mt-0.5">{{ optional($batch->creator)->name ?? 'System' }}</div>
                            </td>

                            {{-- Actions --}}
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    {{-- Toggle Active --}}
                                    <form action="{{ route('dashboard.batches.toggle', $batch->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="{{ $batch->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            class="p-2 rounded-lg {{ $batch->is_active ? 'text-amber-500 hover:bg-amber-50' : 'text-emerald-500 hover:bg-emerald-50' }} transition">
                                            @if($batch->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </button>
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('dashboard.batches.destroy', $batch->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus batch ini? Stok & WAC akan diperbarui otomatis.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus Batch"
                                            class="p-2 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <p class="text-sm font-medium">Belum ada data batch</p>
                                <p class="text-xs mt-1">Klik "Tambah Batch Stok" untuk menambahkan stok masuk</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($batches->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $batches->links() }}
            </div>
        @endif
    </div>

    {{-- WAC Info Card --}}
    <div class="mt-6 bg-gradient-to-br from-brand-primary/10 to-brand-tertiary/20 rounded-2xl p-5 border border-brand-primary/20 motion-title">
        <h3 class="font-semibold text-gray-700 text-sm mb-1 flex items-center gap-2">
            <svg class="w-4 h-4 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Concurrency Protection Aktif
        </h3>
        <p class="text-xs text-gray-500 leading-relaxed">
            Setiap penambahan batch dilindungi oleh <strong class="text-gray-700">lockForUpdate()</strong> dan <strong class="text-gray-700">DB::transaction()</strong>.
            Jika dua admin menambahkan batch bersamaan, data dijamin tidak bertabrakan — sistem akan mengantrikan operasi secara atomik.
            Harga modal (WAC) diperbarui otomatis berdasarkan rata-rata tertimbang seluruh batch aktif.
        </p>
    </div>

    {{-- ==================== CREATE BATCH MODAL ==================== --}}
    <div id="modal-batch" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Tambah Batch Stok Baru</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Catat stok masuk untuk varian produk</p>
                </div>
                <button onclick="document.getElementById('modal-batch').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form action="{{ route('dashboard.batches.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Product Brand Selection --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Varian Produk <span class="text-red-400">*</span></label>
                    <select name="product_brand_id" required
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition bg-white">
                        <option value="">— Pilih Produk & Brand —</option>
                        @foreach($productBrands as $pb)
                            <option value="{{ $pb->id }}" {{ old('product_brand_id') == $pb->id ? 'selected' : '' }}>
                                {{ optional($pb->product)->name ?? '-' }} — {{ optional($pb->brand)->name ?? '-' }} ({{ $pb->unit }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Stock & Price Row --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jumlah Stok <span class="text-red-400">*</span></label>
                        <input type="number" name="initial_stock" min="1" value="{{ old('initial_stock') }}" required placeholder="Contoh: 100"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Harga Beli / Unit <span class="text-red-400">*</span></label>
                        <input type="number" name="purchase_price" min="0" value="{{ old('purchase_price') }}" required placeholder="Contoh: 5000"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
                    </div>
                </div>

                {{-- Supplier Name --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Supplier</label>
                    <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" maxlength="100" placeholder="Contoh: PT. Alat Tulis Nusantara"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
                </div>

                {{-- Batch Code (Optional) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Batch <span class="text-gray-400 font-normal">(opsional, auto-generate jika kosong)</span></label>
                    <input type="text" name="batch_code" value="{{ old('batch_code') }}" maxlength="50" placeholder="BCH-20260509-XXXXX"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
                </div>

                {{-- Info Alert --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                    <p class="text-xs text-amber-700 font-medium leading-relaxed">
                        ⚡ <strong>Auto-Recalculate:</strong> Setelah batch ditambahkan, total stok produk dan harga modal WAC akan dihitung ulang secara otomatis dari seluruh batch aktif.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modal-batch').classList.add('hidden')"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-brand-primary hover:bg-brand-dark text-gray-600 rounded-xl text-sm font-semibold transition shadow flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Batch
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
