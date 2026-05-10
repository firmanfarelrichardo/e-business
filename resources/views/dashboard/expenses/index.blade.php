@extends('components.layouts.dashboard')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800">Manajemen Pengeluaran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Catat pembelian stok & harga modal produk (WAC Queue)</p>
        </div>
        @if(auth()->user()->role === 'owner')
            <button onclick="document.getElementById('modal-expense').classList.remove('hidden')"
                class="flex items-center gap-2 bg-[#7B9B6F] hover:bg-[#5A6852] text-white px-4 py-2 rounded-xl text-sm font-medium transition shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Catat Pengeluaran
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Item Produk</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Item</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Catatan</th>
                        <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dicatat Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($expenses as $expense)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ optional($expense->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="space-y-1">
                                    @foreach($expense->items as $item)
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs bg-[#E8F0E5] text-[#5A6852] px-2 py-0.5 rounded-full font-medium">
                                                {{ optional(optional($item->productBrand)->product)->name ?? '-' }}
                                            </span>
                                            <span class="text-xs text-gray-400">{{ $item->quantity }} pcs × Rp
                                                {{ number_format($item->purchase_price, 0, ',', '.') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-700">{{ $expense->items->count() }} item</td>
                            <td class="py-3 px-4 text-sm font-bold text-gray-800">Rp
                                {{ number_format($expense->total_amount, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-500 max-w-[160px] truncate">{{ $expense->note ?? '-' }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500">{{ optional($expense->creator)->name ?? 'System' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                                <p class="text-sm">Belum ada riwayat pengeluaran</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $expenses->links() }}
        </div>
    </div>

    <!-- WAC Info Card -->
    <div class="mt-6 bg-gradient-to-br from-[#7B9B6F]/10 to-[#B09282]/10 rounded-2xl p-5 border border-[#7B9B6F]/20">
        <h3 class="font-semibold text-gray-700 text-sm mb-3 flex items-center gap-2">
            <svg class="w-4 h-4 text-[#7B9B6F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Harga Modal Saat Ini (WAC Queue)
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($productBrands as $pb)
                <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                    <div class="text-xs text-gray-400 truncate">{{ optional($pb->product)->name ?? '-' }}</div>
                    <div class="text-sm font-bold text-gray-800 mt-0.5">Rp {{ number_format($pb->selling_price, 0, ',', '.') }}
                    </div>
                    <div class="text-[10px] text-[#7B9B6F] mt-1">Stok: {{ $pb->current_stock ?? 0 }} unit</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Expense Modal -->
    @if(auth()->user()->role === 'owner')
        <div id="modal-expense"
            class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-800">Catat Pengeluaran Baru</h2>
                    <button onclick="document.getElementById('modal-expense').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form action="{{ route('dashboard.expenses.store') }}" method="POST" class="p-6 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan (opsional)</label>
                        <input type="text" name="note" placeholder="Misal: Restock ATK bulan Mei"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-xs font-semibold text-gray-600">Item Produk</label>
                            <button type="button" onclick="addExpenseItem()"
                                class="text-xs text-[#7B9B6F] hover:underline font-medium">+ Tambah Item</button>
                        </div>
                        <div id="expense-items" class="space-y-3">
                            <div class="expense-item grid grid-cols-12 gap-2 items-start">
                                <div class="col-span-5">
                                    <div class="pb-search-wrap">
                                        <input type="text"
                                            class="pb-search-input w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition"
                                            placeholder="Cari produk/varian..." autocomplete="off">
                                        <input type="hidden" name="items[0][product_brand_id]" class="pb-hidden-input" required>
                                        <div class="pb-dropdown"></div>
                                    </div>
                                </div>
                                <div class="col-span-3">
                                    <input type="number" name="items[0][quantity]" min="1" placeholder="Qty" required
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                                </div>
                                <div class="col-span-3">
                                    <input type="number" name="items[0][purchase_price]" min="0" placeholder="Harga beli (Rp)"
                                        required
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                                </div>
                                <div class="col-span-1 flex items-center justify-center pt-2">
                                    <button type="button" onclick="this.closest('.expense-item').remove()"
                                        class="text-red-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 mt-2 text-[10px] text-gray-400 px-1">
                            <div class="col-span-5">Produk / Varian</div>
                            <div class="col-span-3">Kuantitas</div>
                            <div class="col-span-3">Harga Beli / Unit</div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3">
                        <p class="text-xs text-amber-700 font-medium">
                            ⚡ WAC Queue: Harga modal produk akan otomatis diperbarui. Batch yang sudah habis stok tidak ikut
                            dihitung.
                        </p>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('modal-expense').classList.add('hidden')"
                            class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                        <button type="submit"
                            class="flex-1 py-2.5 bg-[#7B9B6F] hover:bg-[#5A6852] text-white rounded-xl text-sm font-semibold transition shadow">Simpan
                            Pengeluaran</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @push('styles')
        <style>
            .pb-search-wrap {
                position: relative;
            }

            .pb-dropdown {
                display: none;
                position: absolute;
                z-index: 200;
                left: 0;
                right: 0;
                top: 100%;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
                max-height: 200px;
                overflow-y: auto;
                margin-top: 2px;
            }

            .pb-dropdown.open {
                display: block;
            }

            .pb-option {
                padding: 8px 12px;
                font-size: 13px;
                cursor: pointer;
                color: #374151;
            }

            .pb-option:hover,
            .pb-option.focused {
                background: #E8F0E5;
                color: #3d5c36;
            }

            .pb-option.no-result {
                color: #9ca3af;
                cursor: default;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            const productBrands = @json($productBrands->map(fn($pb) => ['id' => $pb->id, 'label' => (optional($pb->product)->name ?? '-') . ' (' . (optional($pb->brand)->name ?? '-') . ')']));
            let expenseItemCount = 1;

            // ── Searchable dropdown widget ──────────────────────────────────────
            function initExpenseSearch(wrapper) {
                const searchInput = wrapper.querySelector('.pb-search-input');
                const hiddenInput = wrapper.querySelector('.pb-hidden-input');
                const dropdown = wrapper.querySelector('.pb-dropdown');

                function render(filter) {
                    const kw = (filter || '').toLowerCase();
                    const filtered = productBrands.filter(pb => pb.label.toLowerCase().includes(kw));
                    if (filtered.length === 0) {
                        dropdown.innerHTML = '<div class="pb-option no-result">Produk tidak ditemukan</div>';
                        return;
                    }
                    dropdown.innerHTML = filtered.map(pb =>
                        `<div class="pb-option" data-id="${pb.id}" data-label="${pb.label}">${pb.label}</div>`
                    ).join('');
                    dropdown.querySelectorAll('.pb-option[data-id]').forEach(opt => {
                        opt.addEventListener('mousedown', (e) => {
                            e.preventDefault();
                            hiddenInput.value = opt.dataset.id;
                            searchInput.value = opt.dataset.label;
                            dropdown.classList.remove('open');
                        });
                    });
                }

                searchInput.addEventListener('focus', () => { render(searchInput.value); dropdown.classList.add('open'); });
                searchInput.addEventListener('input', () => render(searchInput.value));
                searchInput.addEventListener('blur', () => setTimeout(() => dropdown.classList.remove('open'), 150));
            }

            // ── Build one expense-item row HTML ─────────────────────────────────
            function buildExpenseItemHTML(idx) {
                const firstPb = productBrands.length > 0 ? productBrands[0] : null;
                return `
                                            <div class="expense-item grid grid-cols-12 gap-2 items-start">
                                                <div class="col-span-5">
                                                    <div class="pb-search-wrap">
                                                        <input type="text" class="pb-search-input w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition" placeholder="Cari produk/varian..." autocomplete="off">
                                                        <input type="hidden" name="items[${idx}][product_brand_id]" class="pb-hidden-input" required>
                                                        <div class="pb-dropdown"></div>
                                                    </div>
                                                </div>
                                                <div class="col-span-3">
                                                    <input type="number" name="items[${idx}][quantity]" min="1" placeholder="Qty" required
                                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                                                </div>
                                                <div class="col-span-3">
                                                    <input type="number" name="items[${idx}][purchase_price]" min="0" placeholder="Harga beli (Rp)" required
                                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                                                </div>
                                                <div class="col-span-1 flex items-center justify-center pt-2">
                                                    <button type="button" onclick="this.closest('.expense-item').remove()" class="text-red-400 hover:text-red-600 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </div>`;
            }

            function addExpenseItem() {
                const idx = expenseItemCount++;
                const container = document.getElementById('expense-items');
                container.insertAdjacentHTML('beforeend', buildExpenseItemHTML(idx));
                const newWrap = container.querySelectorAll('.pb-search-wrap');
                initExpenseSearch(newWrap[newWrap.length - 1]);
            }

            // ── Init the first (pre-rendered) row ────────────────────────────────
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.pb-search-wrap').forEach(initExpenseSearch);

                // Guard: warn before submit if any product_brand_id is blank
                document.querySelector('form[action*="expenses"]')?.addEventListener('submit', (e) => {
                    const blanks = document.querySelectorAll('.pb-hidden-input');
                    for (const b of blanks) {
                        if (!b.value) {
                            e.preventDefault();
                            alert('Pilih produk untuk setiap item pengeluaran terlebih dahulu.');
                            return;
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection