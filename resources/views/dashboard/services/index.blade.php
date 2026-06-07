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
        <h1 class="text-2xl font-bold text-[var(--color-text)] tracking-tight motion-title">Manajemen Jasa & Layanan</h1>
        <p class="text-sm text-[var(--color-text-muted)] motion-title mt-1">Kelola data servis percetakan dan harga satuan</p>
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
    <div class="motion-title bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] p-6 shadow-sm border border-[var(--color-border-subtle)] flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="relative w-full md:w-80">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" placeholder="Cari nama layanan..."
                class="w-full bg-[var(--color-bg-sunken)] border border-[var(--color-border)] rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition"
                oninput="filterServices(this.value)">
        </div>

        <button onclick="openServiceCreateModal()"
            class="w-full md:w-auto bg-[var(--accent-teal)] hover:bg-[var(--night-700)] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Jasa
        </button>
    </div>

    <!-- Services Table -->
    <div class="bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] shadow-sm border border-[var(--color-border-subtle)] overflow-hidden motion-title">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[var(--color-bg-sunken)] border-b border-[var(--color-border-subtle)]">
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider w-1/3">Layanan</th>
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider w-1/3">Deskripsi</th>
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider lg:w-1/6">Harga / Pcs</th>
                        <th class="py-4 px-6 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider text-right lg:w-1/6">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($services as $service)
                        <tr class="hover:bg-[var(--color-bg-sunken)]/50 transition motion-table-row service-row" data-name="{{ strtolower($service->name) }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    @if(!empty($service->attachments) && is_array($service->attachments) && count($service->attachments) > 0)
                                        <img src="{{ Storage::url($service->attachments[0]) }}" alt="{{ $service->name }}" class="w-10 h-10 rounded-lg object-cover border border-[var(--color-border)] shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-[var(--accent-teal)]/10 text-[var(--accent-teal)] flex items-center justify-center shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-[var(--color-text)] text-sm">{{ $service->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-xs text-[var(--color-text-muted)] line-clamp-2 max-w-sm">{{ $service->description ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-semibold text-[var(--color-text)]">Rp {{ number_format($service->piece_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button
                                    onclick="openServiceEditModal('{{ $service->id }}','{{ addslashes($service->name) }}','{{ addslashes($service->description ?? '') }}','{{ $service->piece_price }}')"
                                    class="text-[var(--color-text-muted)] hover:text-[var(--accent-teal)] transition p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('dashboard.services.destroy', $service->id) }}"
                                    class="inline-block"
                                    onsubmit="return confirm('Yakin hapus jasa {{ addslashes($service->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[var(--color-text-muted)] hover:text-[var(--accent-rose)] transition p-1 ml-1" title="Hapus">
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
                            <td colspan="4" class="py-12 text-center text-[var(--color-text-muted)]">Belum ada data jasa/layanan di sistem.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== CREATE SERVICE MODAL ===== -->
    <div id="modal-create-service" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-[var(--color-border-subtle)]">
                <h2 class="text-lg font-bold text-[var(--color-text)]">Tambah Jasa Baru</h2>
                <button onclick="closeServiceCreateModal()" class="text-[var(--color-text-muted)] hover:text-[var(--color-text-secondary)] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('dashboard.services.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Nama Layanan <span class="text-[var(--accent-rose)]">*</span></label>
                    <input type="text" name="name" required maxlength="100" placeholder="Contoh: Print Warna A4"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="3" placeholder="Deskripsi layanan (opsional)"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Harga Per Pcs (Rp) <span class="text-[var(--accent-rose)]">*</span></label>
                    <input type="number" name="piece_price" required min="0" placeholder="5000"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Foto Layanan <span class="text-[10px] text-[var(--color-text-muted)] font-normal">(bisa banyak)</span></label>
                    <input type="file" name="attachments[]" multiple accept="image/*"
                        class="w-full text-sm text-[var(--color-text-muted)] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#E8F0E5] file:text-[var(--night-700)] hover:file:bg-[#D5E1D1] transition cursor-pointer">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeServiceCreateModal()"
                        class="flex-1 py-2.5 border border-[var(--color-border)] text-[var(--color-text-secondary)] rounded-xl text-sm font-medium hover:bg-[var(--color-bg-sunken)] transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[var(--accent-teal)] hover:bg-[var(--night-700)] text-white rounded-xl text-sm font-semibold transition shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== EDIT SERVICE MODAL ===== -->
    <div id="modal-edit-service" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-[var(--color-bg-elevated)] rounded-[var(--radius-xl)] shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-[var(--color-border-subtle)]">
                <h2 class="text-lg font-bold text-[var(--color-text)]">Edit Jasa</h2>
                <button onclick="closeServiceEditModal()" class="text-[var(--color-text-muted)] hover:text-[var(--color-text-secondary)] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-service-form" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Nama Layanan <span class="text-[var(--accent-rose)]">*</span></label>
                    <input type="text" name="name" id="edit-service-name" required maxlength="100"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Deskripsi</label>
                    <textarea name="description" id="edit-service-description" rows="3"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Harga Per Pcs (Rp) <span class="text-[var(--accent-rose)]">*</span></label>
                    <input type="number" name="piece_price" id="edit-service-price" required min="0"
                        class="w-full border border-[var(--color-border)] rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--accent-teal)] transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[var(--color-text-secondary)] mb-1.5">Tambah Foto <span class="text-[10px] text-[var(--color-text-muted)] font-normal">(opsional)</span></label>
                    <input type="file" name="attachments[]" multiple accept="image/*"
                        class="w-full text-sm text-[var(--color-text-muted)] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#E8F0E5] file:text-[var(--night-700)] hover:file:bg-[#D5E1D1] transition cursor-pointer">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeServiceEditModal()"
                        class="flex-1 py-2.5 border border-[var(--color-border)] text-[var(--color-text-secondary)] rounded-xl text-sm font-medium hover:bg-[var(--color-bg-sunken)] transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[var(--accent-teal)] hover:bg-[var(--night-700)] text-white rounded-xl text-sm font-semibold transition shadow">Update</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openServiceCreateModal() { document.getElementById('modal-create-service').classList.remove('hidden'); }
            function closeServiceCreateModal() { document.getElementById('modal-create-service').classList.add('hidden'); }
            function openServiceEditModal(id, name, description, price) {
                const form = document.getElementById('edit-service-form');
                form.action = `/dashboard/services/${id}`;
                document.getElementById('edit-service-name').value = name;
                document.getElementById('edit-service-description').value = description;
                document.getElementById('edit-service-price').value = price;
                document.getElementById('modal-edit-service').classList.remove('hidden');
            }
            function closeServiceEditModal() { document.getElementById('modal-edit-service').classList.add('hidden'); }
            function filterServices(query) {
                const q = query.toLowerCase();
                document.querySelectorAll('.service-row').forEach(row => {
                    row.style.display = row.dataset.name.includes(q) ? '' : 'none';
                });
            }
            ['modal-create-service', 'modal-edit-service'].forEach(id => {
                document.getElementById(id).addEventListener('click', function(e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            });
        </script>
    @endpush

@endsection