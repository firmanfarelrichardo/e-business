@extends('components.layouts.dashboard')

@section('content')

    @push('scripts')
        <script type="module">
            import { animate, stagger } from "https://esm.sh/motion";
            animate(".motion-title", { y: [20, 0], opacity: [0, 1] }, { duration: 0.5, easing: "ease-out" });
            animate(".motion-stat-card", { y: [30, 0], opacity: [0, 1] }, { delay: stagger(0.1), duration: 0.5, easing: "ease-out" });
            animate(".motion-table-row", { y: [12, 0], opacity: [0, 1] }, { delay: stagger(0.04), duration: 0.35, easing: "ease-out" });
        </script>
    @endpush

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight motion-title">Kartu Stok</h1>
        <p class="text-sm text-gray-500 motion-title mt-1">Laporan pergerakan stok — riwayat barang masuk & keluar per varian produk</p>
    </div>

    {{-- Filter Form --}}
    <div class="motion-title glass-card rounded-2xl p-6 mb-6">
        <form method="GET" action="{{ route('dashboard.reports.stock-card') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

            {{-- Product Brand Dropdown --}}
            <div class="md:col-span-5">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Varian Produk <span class="text-red-400">*</span></label>
                <select name="product_brand_id" required
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition bg-white">
                    <option value="">— Pilih Produk & Brand —</option>
                    @foreach($productBrands as $pb)
                        <option value="{{ $pb->id }}" {{ $productBrandId == $pb->id ? 'selected' : '' }}>
                            {{ optional($pb->product)->name ?? '-' }} — {{ optional($pb->brand)->name ?? '-' }} ({{ $pb->unit }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Start Date --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Awal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
            </div>

            {{-- End Date --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-brand-primary/50 transition">
            </div>

            {{-- Action Buttons --}}
            <div class="md:col-span-3 flex gap-2">
                <button type="submit"
                    class="flex-1 bg-brand-primary hover:bg-brand-dark text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Tampilkan
                </button>
                @if($productBrandId)
                    <a href="{{ route('dashboard.reports.stock-card') }}"
                        class="px-4 py-2.5 border border-gray-200 text-gray-500 hover:bg-gray-50 rounded-xl text-sm font-medium transition">Reset</a>
                @endif
            </div>
        </form>
    </div>

    @if($stockCard && $selectedBrand)

        {{-- Selected Product Info --}}
        <div class="motion-title mb-6">
            <div class="inline-flex items-center gap-3 bg-white rounded-xl px-4 py-2.5 shadow-sm border border-gray-100">
                <div class="w-8 h-8 rounded-lg bg-brand-primary/15 flex items-center justify-center">
                    <svg class="w-4 h-4 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <span class="font-semibold text-gray-800 text-sm">{{ optional($selectedBrand->product)->name }}</span>
                    <span class="text-gray-400 text-xs ml-1">— {{ optional($selectedBrand->brand)->name }} ({{ $selectedBrand->unit }})</span>
                </div>
                @if($startDate || $endDate)
                    <span class="text-xs text-gray-400 ml-2 border-l border-gray-200 pl-2">
                        Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d M Y') : 'Awal' }}
                        — {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d M Y') : 'Sekarang' }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Summary Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            {{-- Opening Balance --}}
            <div class="motion-stat-card glass-card rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Saldo Awal</p>
                        <p class="text-xl font-bold text-gray-700">{{ number_format($stockCard['opening_balance']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Total In --}}
            <div class="motion-stat-card glass-card rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Masuk</p>
                        <p class="text-xl font-bold text-emerald-700">+{{ number_format($stockCard['summary']['total_in']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Out --}}
            <div class="motion-stat-card glass-card rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Total Keluar</p>
                        <p class="text-xl font-bold text-red-600">-{{ number_format($stockCard['summary']['total_out']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Current Stock --}}
            <div class="motion-stat-card glass-card rounded-2xl p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Stok Aktual</p>
                        <p class="text-xl font-bold text-blue-700">{{ number_format($stockCard['summary']['current_stock']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stock Card Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden motion-title">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-[140px]">Tanggal</th>
                            <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-[100px]">Tipe Mutasi</th>
                            <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right w-[100px]">Qty Masuk</th>
                            <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right w-[100px]">Qty Keluar</th>
                            <th class="py-4 px-5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right w-[120px]">Saldo Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">

                        {{-- Opening Balance Row --}}
                        <tr class="bg-gray-50/70 motion-table-row">
                            <td class="py-3 px-5 text-sm text-gray-400 font-medium" colspan="3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Saldo Awal Periode
                                </div>
                            </td>
                            <td class="py-3 px-5 text-sm text-gray-400 text-right">—</td>
                            <td class="py-3 px-5 text-sm text-gray-400 text-right">—</td>
                            <td class="py-3 px-5 text-sm font-bold text-gray-700 text-right">{{ number_format($stockCard['opening_balance']) }}</td>
                        </tr>

                        {{-- Movement Rows --}}
                        @forelse($stockCard['movements'] as $movement)
                            <tr class="hover:bg-gray-50/50 transition motion-table-row">
                                {{-- Date --}}
                                <td class="py-3.5 px-5 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($movement['date'])->format('d M Y') }}
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($movement['date'])->format('H:i') }}</div>
                                </td>

                                {{-- Mutation Type Badge --}}
                                <td class="py-3.5 px-5">
                                    @if($movement['type'] === 'in')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                                            Masuk
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-600 border border-red-200">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                                            Keluar
                                        </span>
                                    @endif
                                </td>

                                {{-- Description --}}
                                <td class="py-3.5 px-5">
                                    <div class="text-sm text-gray-700">{{ $movement['description'] }}</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">@ Rp {{ number_format($movement['purchase_price'], 0, ',', '.') }}/unit</div>
                                </td>

                                {{-- Qty In --}}
                                <td class="py-3.5 px-5 text-right">
                                    @if($movement['qty_in'] > 0)
                                        <span class="text-sm font-semibold text-emerald-600">+{{ number_format($movement['qty_in']) }}</span>
                                    @else
                                        <span class="text-sm text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Qty Out --}}
                                <td class="py-3.5 px-5 text-right">
                                    @if($movement['qty_out'] > 0)
                                        <span class="text-sm font-semibold text-red-500">-{{ number_format($movement['qty_out']) }}</span>
                                    @else
                                        <span class="text-sm text-gray-300">—</span>
                                    @endif
                                </td>

                                {{-- Running Balance --}}
                                <td class="py-3.5 px-5 text-right">
                                    <span class="text-sm font-bold {{ $movement['running_balance'] < 0 ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ number_format($movement['running_balance']) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr class="motion-table-row">
                                <td colspan="6" class="py-16 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <p class="text-sm font-medium">Tidak ada pergerakan stok pada periode ini</p>
                                    <p class="text-xs mt-1">Coba perluas rentang tanggal atau pilih varian lain</p>
                                </td>
                            </tr>
                        @endforelse

                        {{-- Closing Balance Row --}}
                        @if(count($stockCard['movements']) > 0)
                            <tr class="bg-brand-primary/5 border-t-2 border-brand-primary/20 motion-table-row">
                                <td class="py-3.5 px-5 text-sm font-bold text-brand-dark" colspan="3">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Saldo Akhir Periode
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-right text-sm font-bold text-emerald-600">+{{ number_format($stockCard['summary']['total_in']) }}</td>
                                <td class="py-3.5 px-5 text-right text-sm font-bold text-red-500">-{{ number_format($stockCard['summary']['total_out']) }}</td>
                                <td class="py-3.5 px-5 text-right">
                                    <span class="text-base font-extrabold {{ $stockCard['closing_balance'] < 0 ? 'text-red-600' : 'text-brand-dark' }}">
                                        {{ number_format($stockCard['closing_balance']) }}
                                    </span>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Print Hint --}}
        <div class="mt-4 text-xs text-gray-400 motion-title flex items-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2z"/></svg>
            Gunakan <kbd class="px-1.5 py-0.5 bg-gray-100 rounded text-gray-500 font-mono text-[10px]">Ctrl+P</kbd> untuk mencetak laporan ini
        </div>

    @elseif(!$productBrandId)

        {{-- Empty State: No product selected --}}
        <div class="glass-card rounded-2xl p-12 text-center motion-title">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Pilih Varian Produk</h3>
            <p class="text-sm text-gray-400 max-w-md mx-auto">Pilih varian produk (produk + brand) dari dropdown di atas untuk menampilkan riwayat pergerakan stok. Anda juga dapat memfilter berdasarkan rentang tanggal.</p>
        </div>

    @endif

@endsection
