@extends('components.layouts.dashboard')

@section('content')

    @push('scripts')
        <script type="module">
            import { animate, stagger } from "https://esm.sh/motion";
            animate(".motion-title", { y: [20, 0], opacity: [0, 1] }, { duration: 0.5, easing: "ease-out" });
            animate(".motion-table-row", { y: [20, 0], opacity: [0, 1] }, { delay: stagger(0.08), duration: 0.5, easing: "ease-out" });
        </script>
    @endpush

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[var(--color-text)] tracking-tight motion-title">Manajemen Produk & Inventori</h1>
        <p class="text-sm text-[var(--color-text-muted)] motion-title mt-1">Kelola data seluruh produk ATK, stok, dan harga jual</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-[var(--accent-teal)]/10 border border-[var(--accent-teal)]/20 text-[var(--accent-teal)] px-4 py-3 rounded-xl text-sm motion-title">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-[var(--accent-rose)]/10 border border-[var(--accent-rose)]/20 text-[var(--accent-rose)] px-4 py-3 rounded-xl text-sm motion-title">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <!-- Control Panel -->
    <div
        class="motion-title bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] p-6 shadow-sm border border-[var(--color-border-subtle)] flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="relative w-full md:w-80">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Cari nama produk..."
                class="w-full bg-[var(--color-bg-sunken)] border border-[var(--color-border)] rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition"
                oninput="filterProducts(this.value)">
        </div>

        <button onclick="openProductCreateModal()"
            class="w-full md:w-auto bg-[var(--accent-teal)] hover:bg-[var(--night-700)] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Produk
        </button>
    </div>

    <!-- Products Table -->
    <div class="bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] shadow-sm border border-[var(--color-border-subtle)] overflow-hidden motion-title">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[var(--color-bg-sunken)] border-b border-[var(--color-border-subtle)]">
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider">Info Produk / ID
                        </th>
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider w-1/6">Kategori</th>
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider w-1/3">Varian & Harga Jual</th>
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider text-right">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($products as $product)
                        <tr class="hover:bg-[var(--color-bg-sunken)]/50 transition motion-table-row product-row"
                            data-name="{{ strtolower($product->name) }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    @if(!empty($product->attachments) && is_array($product->attachments) && count($product->attachments) > 0)
                                        <img src="{{ Storage::url($product->attachments[0]) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-xl object-cover border border-[var(--color-border)] shrink-0">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-xl bg-[var(--color-bg-sunken)] border border-[var(--color-border)] flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 text-[var(--color-text-muted)]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-[var(--color-text)] text-sm">{{ $product->name }}</div>
                                        <div class="text-xs text-[var(--color-text-muted)] mt-0.5 font-mono">
                                            PRD-{{ strtoupper(substr($product->id, 0, 8)) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="space-y-3">
                                    @forelse($product->brands as $pb)
                                        @php
                                            $totalStock = $pb->batches->where('is_active', true)->sum('current_stock');
                                            $wacValue = $pb->average_cost;
                                        @endphp
                                        <div class="flex items-center justify-between gap-4 p-2 bg-[var(--color-bg-sunken)] rounded-lg border border-[var(--color-border-subtle)] shadow-sm">
                                            <div class="text-xs">
                                                <span class="font-semibold text-[var(--color-text)]">{{ optional($pb->brand)->name ?? 'Unknown' }}</span>
                                                <div class="text-[var(--color-text-muted)] mt-0.5 text-[10px]">Stok: <span class="font-medium text-[var(--color-text)]">{{ $totalStock }}</span> | <span class="text-amber-600 font-medium">Modal: Rp{{ number_format($wacValue, 0, ',', '.') }}</span></div>
                                            </div>
                                            <form action="{{ route('dashboard.productbrand.price', $pb->id) }}" method="POST" class="flex items-center gap-1 shrink-0">
                                                @csrf @method('PUT')
                                                <input type="number" name="selling_price" value="{{ round($pb->selling_price) }}" required min="0" class="w-24 px-2 py-1 text-xs border border-[var(--color-border)] rounded focus:border-[var(--accent-teal)] focus:ring-0">
                                                <button type="submit" class="bg-[var(--accent-teal)] text-white px-2 py-1 rounded text-xs hover:bg-[var(--night-700)] transition">Simpan</button>
                                            </form>
                                        </div>
                                    @empty
                                        <span class="text-xs text-[var(--color-text-muted)]">Belum ada varian. Tambahkan lewat API.</span>
                                    @endforelse
                                    @if($product->description)
                                        <div class="mt-2 text-[10px] text-[var(--color-text-muted)] italic">Deskripsi: {{ $product->description }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button
                                    onclick="openProductEditModal('{{ $product->id }}','{{ addslashes($product->name) }}','{{ $product->category_id }}','{{ addslashes($product->description ?? '') }}')"
                                    class="text-[var(--color-text-muted)] hover:text-[var(--accent-teal)] transition p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.products.destroy', $product->id) }}"
                                    class="inline-block"
                                    onsubmit="return confirm('Yakin hapus produk {{ addslashes($product->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[var(--color-text-muted)] hover:text-[var(--accent-rose)] transition p-1 ml-1"
                                        title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-[var(--color-text-muted)]">Belum ada data produk di sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== CREATE PRODUCT MODAL ===== -->
    <div id="modal-create-product"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-[var(--color-border-subtle)]">
                <h2 class="text-lg font-bold text-[var(--color-text)]">Tambah Produk Baru</h2>
                <button onclick="closeProductCreateModal()" class="text-[var(--color-text-muted)] hover:text-[var(--color-text-secondary)] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('dashboard.products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Nama Produk <span
                            class="text-[var(--accent-rose)]">*</span></label>
                    <input type="text" name="name" required maxlength="50" placeholder="Contoh: Pulpen Pilot BPT"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Kategori <span
                            class="text-[var(--accent-rose)]">*</span></label>
                    
                    <div class="flex rounded-lg bg-[var(--color-bg-sunken)] p-1 mb-2">
                        <button type="button" onclick="setCreateCategoryMode('existing')"
                            id="create-cat-tab-existing"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition bg-[var(--color-bg-elevated)] shadow text-[var(--night-700)]">
                            Pilih dari daftar
                        </button>
                        <button type="button" onclick="setCreateCategoryMode('new')"
                            id="create-cat-tab-new"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition text-[var(--color-text-muted)]">
                            Buat kategori baru
                        </button>
                    </div>

                    <div id="create-cat-panel-existing">
                        <select name="category_id" id="create-category-select" required
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition bg-[var(--color-bg-elevated)]">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="create-cat-panel-new" class="hidden">
                        <input type="text" name="category_name" id="create-category-name-field"
                            placeholder="Contoh: Alat Tulis"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi produk (opsional)"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Foto Produk <span class="text-[10px] text-[var(--color-text-muted)] font-normal">(bisa pilih banyak logo/gambar)</span></label>
                    <input type="file" name="attachments[]" multiple accept="image/*"
                        class="w-full text-sm text-[var(--color-text-muted)] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#E8F0E5] file:text-[var(--night-700)] hover:file:bg-[#D5E1D1] transition cursor-pointer">
                </div>
                <div class="border-t border-[var(--color-border-subtle)] pt-4">
                    <h3 class="text-xs font-semibold text-[var(--color-text-secondary)] mb-3">Varian Produk <span class="text-[var(--color-text-muted)] font-normal">(opsional)</span></h3>

                    {{-- Mode toggle --}}
                    <div class="flex rounded-lg bg-[var(--color-bg-sunken)] p-1 mb-3" id="create-brand-tabs">
                        <button type="button" onclick="setCreateBrandMode('existing')"
                            id="create-tab-existing"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition bg-[var(--color-bg-elevated)] shadow text-[var(--night-700)]">
                            Pilih dari daftar
                        </button>
                        <button type="button" onclick="setCreateBrandMode('new')"
                            id="create-tab-new"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition text-[var(--color-text-muted)]">
                            Buat brand baru
                        </button>
                    </div>

                    {{-- Panel: pilih existing --}}
                    <div id="create-panel-existing">
                        <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Pilih Brand</label>
                        <select id="create-brand-select" name="brand_id"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition bg-[var(--color-bg-elevated)]">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[10px] text-[var(--color-text-muted)]">Biarkan kosong jika belum ingin varian.</p>
                    </div>

                    {{-- Panel: brand baru --}}
                    <div id="create-panel-new" class="hidden">
                        {{-- hidden brand_id = "" saat mode new --}}
                        <input type="hidden" id="create-brand-id-hidden" name="brand_id" value="">
                        <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Nama Brand Baru <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="text" name="brand_name" id="create-brand-name-field"
                            placeholder="Contoh: Pilot"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                        <p class="mt-1 text-[10px] text-[var(--color-text-muted)]">Brand ini akan dibuat otomatis jika belum ada.</p>
                    </div>

                    {{-- Unit & harga (selalu tampil) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Unit</label>
                            <input type="text" name="unit" placeholder="Contoh: pcs"
                                class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Harga Jual Awal (Rp)</label>
                            <input type="number" name="selling_price" min="0" placeholder="Contoh: 5000"
                                class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeProductCreateModal()"
                        class="flex-1 py-2.5 border border-[var(--color-border)] text-[var(--color-text-secondary)] rounded-xl text-sm font-medium hover:bg-[var(--color-bg-sunken)] transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[var(--accent-teal)] hover:bg-[var(--night-700)] text-white rounded-xl text-sm font-semibold transition shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== EDIT PRODUCT MODAL ===== -->
    <div id="modal-edit-product"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-[var(--color-border-subtle)]">
                <h2 class="text-lg font-bold text-[var(--color-text)]">Edit Produk</h2>
                <button onclick="closeProductEditModal()" class="text-[var(--color-text-muted)] hover:text-[var(--color-text-secondary)] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-product-form" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Nama Produk <span
                            class="text-[var(--accent-rose)]">*</span></label>
                    <input type="text" name="name" id="edit-product-name" required maxlength="50"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Kategori <span
                            class="text-[var(--accent-rose)]">*</span></label>
                    
                    <div class="flex rounded-lg bg-[var(--color-bg-sunken)] p-1 mb-2">
                        <button type="button" onclick="setEditCategoryMode('existing')"
                            id="edit-cat-tab-existing"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition bg-[var(--color-bg-elevated)] shadow text-[var(--night-700)]">
                            Pilih dari daftar
                        </button>
                        <button type="button" onclick="setEditCategoryMode('new')"
                            id="edit-cat-tab-new"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition text-[var(--color-text-muted)]">
                            Buat kategori baru
                        </button>
                    </div>

                    <div id="edit-cat-panel-existing">
                        <select name="category_id" id="edit-product-category" required
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition bg-[var(--color-bg-elevated)]">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="edit-cat-panel-new" class="hidden">
                        <input type="text" name="category_name" id="edit-category-name-field"
                            placeholder="Contoh: Alat Tulis"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Deskripsi</label>
                    <textarea name="description" id="edit-product-description" rows="3"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Tambah Foto <span class="text-[10px] text-[var(--color-text-muted)] font-normal">(foto sebelumnya tidak dihapus jika upload baru)</span></label>
                    <input type="file" name="attachments[]" multiple accept="image/*"
                        class="w-full text-sm text-[var(--color-text-muted)] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#E8F0E5] file:text-[var(--night-700)] hover:file:bg-[#D5E1D1] transition cursor-pointer">
                </div>

                {{-- ===== BRAND SECTION ===== --}}
                <div class="border-t border-[var(--color-border-subtle)] pt-4">
                    <h3 class="text-xs font-semibold text-[var(--color-text-secondary)] mb-1">Tambah Varian Brand <span class="text-[var(--color-text-muted)] font-normal">(opsional)</span></h3>
                    <p class="text-[10px] text-[var(--color-text-muted)] mb-3">Kosongkan semua field ini jika tidak ingin tambah varian.</p>

                    {{-- Mode toggle --}}
                    <div class="flex rounded-lg bg-[var(--color-bg-sunken)] p-1 mb-3">
                        <button type="button" onclick="setEditBrandMode('existing')"
                            id="edit-tab-existing"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition bg-[var(--color-bg-elevated)] shadow text-[var(--night-700)]">
                            Pilih dari daftar
                        </button>
                        <button type="button" onclick="setEditBrandMode('new')"
                            id="edit-tab-new"
                            class="flex-1 text-xs font-medium py-1.5 rounded-md transition text-[var(--color-text-muted)]">
                            Buat brand baru
                        </button>
                    </div>

                    {{-- Panel: pilih existing --}}
                    <div id="edit-panel-existing">
                        <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Pilih Brand</label>
                        <select id="edit-brand-select" name="brand_id"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition bg-[var(--color-bg-elevated)]">
                            <option value="">-- Pilih Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Panel: brand baru --}}
                    <div id="edit-panel-new" class="hidden">
                        <input type="hidden" id="edit-brand-id-hidden" name="brand_id" value="">
                        <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Nama Brand Baru <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="text" name="brand_name" id="edit-brand-name-field"
                            placeholder="Contoh: Pilot"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                        <p class="mt-1 text-[10px] text-[var(--color-text-muted)]">Brand ini akan dibuat otomatis jika belum ada.</p>
                    </div>

                    {{-- Unit & harga (selalu tampil) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                        <div>
                            <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Unit</label>
                            <input type="text" name="unit" placeholder="Contoh: pcs"
                                class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Harga Jual Awal (Rp)</label>
                            <input type="number" name="selling_price" min="0" placeholder="Contoh: 5000"
                                class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeProductEditModal()"
                        class="flex-1 py-2.5 border border-[var(--color-border)] text-[var(--color-text-secondary)] rounded-xl text-sm font-medium hover:bg-[var(--color-bg-sunken)] transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[var(--accent-teal)] hover:bg-[var(--night-700)] text-white rounded-xl text-sm font-semibold transition shadow">Update</button>
                </div>
            </form>
        </div>
    </div>




    @push('scripts')
        <script>
            /* ====================================================
             * Brand Tab Toggle
             * ==================================================== */
            function setCreateBrandMode(mode) {
                const panelExisting = document.getElementById('create-panel-existing');
                const panelNew      = document.getElementById('create-panel-new');
                const tabExisting   = document.getElementById('create-tab-existing');
                const tabNew        = document.getElementById('create-tab-new');
                const nameField     = document.getElementById('create-brand-name-field');
                const brandIdHidden = document.getElementById('create-brand-id-hidden');
                const brandSelect   = document.getElementById('create-brand-select');

                if (mode === 'new') {
                    panelExisting.classList.add('hidden');
                    panelNew.classList.remove('hidden');
                    tabNew.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.remove('text-[var(--color-text-muted)]');
                    tabExisting.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.add('text-[var(--color-text-muted)]');
                    // disable the select so it doesn't submit brand_id
                    brandSelect.disabled = true;
                    brandSelect.name     = '';
                    if (nameField) nameField.focus();
                } else {
                    panelExisting.classList.remove('hidden');
                    panelNew.classList.add('hidden');
                    tabExisting.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.remove('text-[var(--color-text-muted)]');
                    tabNew.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.add('text-[var(--color-text-muted)]');
                    // re-enable select
                    brandSelect.disabled = false;
                    brandSelect.name     = 'brand_id';
                    if (nameField) nameField.value = '';
                }
            }

            function setEditBrandMode(mode) {
                const panelExisting = document.getElementById('edit-panel-existing');
                const panelNew      = document.getElementById('edit-panel-new');
                const tabExisting   = document.getElementById('edit-tab-existing');
                const tabNew        = document.getElementById('edit-tab-new');
                const nameField     = document.getElementById('edit-brand-name-field');
                const brandSelect   = document.getElementById('edit-brand-select');

                if (mode === 'new') {
                    panelExisting.classList.add('hidden');
                    panelNew.classList.remove('hidden');
                    tabNew.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.remove('text-[var(--color-text-muted)]');
                    tabExisting.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.add('text-[var(--color-text-muted)]');
                    brandSelect.disabled = true;
                    brandSelect.name     = '';
                    if (nameField) nameField.focus();
                } else {
                    panelExisting.classList.remove('hidden');
                    panelNew.classList.add('hidden');
                    tabExisting.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.remove('text-[var(--color-text-muted)]');
                    tabNew.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.add('text-[var(--color-text-muted)]');
                    brandSelect.disabled = false;
                    brandSelect.name     = 'brand_id';
                    if (nameField) nameField.value = '';
                }
            }

            /* ====================================================
             * Category Tab Toggle
             * ==================================================== */
            function setCreateCategoryMode(mode) {
                const panelExisting = document.getElementById('create-cat-panel-existing');
                const panelNew      = document.getElementById('create-cat-panel-new');
                const tabExisting   = document.getElementById('create-cat-tab-existing');
                const tabNew        = document.getElementById('create-cat-tab-new');
                const nameField     = document.getElementById('create-category-name-field');
                const catSelect     = document.getElementById('create-category-select');

                if (mode === 'new') {
                    panelExisting.classList.add('hidden');
                    panelNew.classList.remove('hidden');
                    tabNew.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.remove('text-[var(--color-text-muted)]');
                    tabExisting.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.add('text-[var(--color-text-muted)]');
                    catSelect.disabled = true;
                    if (nameField) nameField.focus();
                } else {
                    panelExisting.classList.remove('hidden');
                    panelNew.classList.add('hidden');
                    tabExisting.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.remove('text-[var(--color-text-muted)]');
                    tabNew.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.add('text-[var(--color-text-muted)]');
                    catSelect.disabled = false;
                    if (nameField) nameField.value = '';
                }
            }

            function setEditCategoryMode(mode) {
                const panelExisting = document.getElementById('edit-cat-panel-existing');
                const panelNew      = document.getElementById('edit-cat-panel-new');
                const tabExisting   = document.getElementById('edit-cat-tab-existing');
                const tabNew        = document.getElementById('edit-cat-tab-new');
                const nameField     = document.getElementById('edit-category-name-field');
                const catSelect     = document.getElementById('edit-product-category');

                if (mode === 'new') {
                    panelExisting.classList.add('hidden');
                    panelNew.classList.remove('hidden');
                    tabNew.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.remove('text-[var(--color-text-muted)]');
                    tabExisting.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.add('text-[var(--color-text-muted)]');
                    catSelect.disabled = true;
                    if (nameField) nameField.focus();
                } else {
                    panelExisting.classList.remove('hidden');
                    panelNew.classList.add('hidden');
                    tabExisting.classList.add('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabExisting.classList.remove('text-[var(--color-text-muted)]');
                    tabNew.classList.remove('bg-[var(--color-bg-elevated)]', 'shadow', 'text-[var(--night-700)]');
                    tabNew.classList.add('text-[var(--color-text-muted)]');
                    catSelect.disabled = false;
                    if (nameField) nameField.value = '';
                }
            }

            /* ====================================================
             * Modal helpers
             * ==================================================== */
            function openProductCreateModal() {
                setCreateBrandMode('existing');
                setCreateCategoryMode('existing');
                document.getElementById('create-brand-select').value = '';
                document.getElementById('create-category-select').value = '';
                document.getElementById('modal-create-product').classList.remove('hidden');
            }
            function closeProductCreateModal() {
                document.getElementById('modal-create-product').classList.add('hidden');
            }

            function openProductEditModal(id, name, categoryId, description) {
                const form = document.getElementById('edit-product-form');
                form.action = `/dashboard/products/${id}`;
                document.getElementById('edit-product-name').value = name;
                document.getElementById('edit-product-category').value = categoryId;
                document.getElementById('edit-product-description').value = description;
                setEditBrandMode('existing');
                setEditCategoryMode('existing');
                document.getElementById('edit-brand-select').value = '';
                document.getElementById('modal-edit-product').classList.remove('hidden');
            }
            function closeProductEditModal() {
                document.getElementById('modal-edit-product').classList.add('hidden');
            }

            function filterProducts(query) {
                const q = query.toLowerCase();
                document.querySelectorAll('.product-row').forEach(row => {
                    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
                });
            }

            ['modal-create-product', 'modal-edit-product'].forEach(id => {
                document.getElementById(id).addEventListener('click', function (e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            });
        </script>
    @endpush

@endsection
