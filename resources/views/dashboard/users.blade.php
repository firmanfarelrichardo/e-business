@extends('components.layouts.dashboard')

@section('header')
    <h1 class="text-lg font-bold text-[var(--color-text)] font-display">Manajemen Pengguna</h1>
    <p class="text-xs text-[var(--color-text-muted)]">Daftar semua pengguna dan peran mereka di platform Sinergi</p>
@endsection

@section('content')

    @push('scripts')
        <script type="module">
            import { animate, stagger } from "https://esm.sh/motion";
            animate(".motion-title", { y: [20, 0], opacity: [0, 1] }, { duration: 0.5, easing: "ease-out" });
            animate(".motion-table-row", { y: [20, 0], opacity: [0, 1] }, { delay: stagger(0.08), duration: 0.5, easing: "ease-out" });
        </script>
    @endpush

    @if(session('success'))
        <div class="mb-4 rounded-[var(--radius-md)] border border-[var(--accent-teal)]/20 bg-[var(--accent-teal)]/10 px-4 py-3 text-sm text-[var(--accent-teal)] font-medium motion-title">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 rounded-[var(--radius-md)] border border-[var(--accent-rose)]/20 bg-[var(--accent-rose)]/10 px-4 py-3 text-sm text-[var(--accent-rose)] motion-title">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Control Panel --}}
    <x-ui.glass-card variant="default" padding="lg" class="motion-title flex flex-col md:flex-row justify-between items-center gap-4 mb-6" data-testid="users-control-panel">
        <div class="relative w-full md:w-80">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[var(--color-text-muted)]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input id="user-search" type="text" placeholder="Cari nama atau email..."
                class="w-full bg-[var(--color-bg-sunken)] border border-[var(--color-border)] rounded-[var(--radius-sm)] py-2.5 pl-10 pr-4 text-sm text-[var(--color-text)] placeholder:text-[var(--color-text-muted)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]"
                oninput="filterUsers(this.value)"
                data-testid="users-search-input">
        </div>

        <x-ui.button variant="primary" size="sm" type="button" onclick="openCreateModal()" data-testid="users-add-button">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Pengguna
        </x-ui.button>
    </x-ui.glass-card>

    {{-- Users Table --}}
    <x-ui.glass-card variant="default" padding="none" class="overflow-hidden motion-title" data-testid="users-table-card">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="users-table">
                <thead>
                    <tr class="border-b border-[var(--color-border-subtle)]" style="background: var(--color-bg-sunken);">
                        <th class="py-4 px-6 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Pengguna</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Kontak</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Peran (Role)</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Status</th>
                        <th class="py-4 px-6 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] text-right font-display">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-border-subtle)]" id="users-tbody">
                    @forelse($users as $user)
                        <tr class="hover:bg-[var(--color-bg-elevated)]/50 transition motion-table-row user-row"
                            data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}"
                            data-testid="users-row-{{ $user->id }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm"
                                         style="background: linear-gradient(135deg, var(--accent-violet), var(--accent-cyan));">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-[var(--color-text)] font-display">{{ $user->name }}</div>
                                        <div class="text-xs text-[var(--color-text-muted)] font-mono">{{ '@' . $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm text-[var(--color-text)]">{{ $user->email }}</div>
                                <div class="text-xs text-[var(--color-text-muted)]">{{ $user->phone ?? 'Belum ada no. telp' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($user->role === 'owner')
                                    <x-ui.badge variant="premium">Owner</x-ui.badge>
                                @elseif($user->role === 'employee')
                                    <x-ui.badge variant="info">Employee</x-ui.badge>
                                @else
                                    <x-ui.badge variant="default">Member</x-ui.badge>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-[var(--accent-teal)]' : 'bg-[var(--accent-rose)]' }}"></span>
                                    <span class="text-sm text-[var(--color-text-secondary)]">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button
                                    onclick="openEditModal('{{ $user->id }}','{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->role }}','{{ addslashes($user->address ?? '') }}')"
                                    class="text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition p-1" title="Edit"
                                    data-testid="users-edit-button-{{ $user->id }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('dashboard.users.destroy', $user->id) }}"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin hapus pengguna {{ addslashes($user->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[var(--color-text-muted)] hover:text-[var(--accent-rose)] transition p-1 ml-1" title="Hapus"
                                            data-testid="users-delete-button-{{ $user->id }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-[var(--color-text-muted)]">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.glass-card>

    {{-- ===== CREATE USER MODAL ===== --}}
    <div id="modal-create-user" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <x-ui.glass-card variant="frosted" padding="none" class="w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-[var(--color-border-subtle)]">
                <h2 class="text-lg font-bold text-[var(--color-text)] font-display">Tambah Pengguna Baru</h2>
                <button onclick="closeCreateModal()" class="text-[var(--color-text-muted)] hover:text-[var(--color-text)] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('dashboard.users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Nama Lengkap <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="text" name="name" required maxlength="50" placeholder="Nama lengkap pengguna"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Username <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="text" name="username" required maxlength="50" placeholder="username unik"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Email <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="email" name="email" required maxlength="50" placeholder="email@domain.com"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Password <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="password" name="password" required minlength="6" placeholder="Min. 6 karakter"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Peran (Role) <span class="text-[var(--accent-rose)]">*</span></label>
                        <select name="role" required
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                            <option value="member">Member</option>
                            <option value="employee">Employee</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Alamat</label>
                        <input type="text" name="address" maxlength="50" placeholder="Alamat lengkap (opsional)"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <x-ui.button variant="ghost" size="sm" type="button" onclick="closeCreateModal()" class="flex-1">Batal</x-ui.button>
                    <x-ui.button variant="primary" size="sm" type="submit" class="flex-1">Simpan</x-ui.button>
                </div>
            </form>
        </x-ui.glass-card>
    </div>

    {{-- ===== EDIT USER MODAL ===== --}}
    <div id="modal-edit-user" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <x-ui.glass-card variant="frosted" padding="none" class="w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="flex items-center justify-between p-6 border-b border-[var(--color-border-subtle)]">
                <h2 class="text-lg font-bold text-[var(--color-text)] font-display">Edit Pengguna</h2>
                <button onclick="closeEditModal()" class="text-[var(--color-text-muted)] hover:text-[var(--color-text)] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-user-form" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_method" value="PUT">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Nama Lengkap <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="text" name="name" id="edit-name" required maxlength="50"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Email <span class="text-[var(--accent-rose)]">*</span></label>
                        <input type="email" name="email" id="edit-email" required maxlength="50"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Peran (Role) <span class="text-[var(--accent-rose)]">*</span></label>
                        <select name="role" id="edit-role" required
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                            <option value="member">Member</option>
                            <option value="employee">Employee</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Password Baru <span class="text-[var(--color-text-muted)] font-normal normal-case tracking-normal">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" minlength="6" placeholder="Biarkan kosong"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-[var(--color-text-muted)] mb-1.5 uppercase tracking-[0.06em] font-display">Alamat</label>
                        <input type="text" name="address" id="edit-address" maxlength="50"
                            class="w-full border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-2.5 text-sm bg-[var(--color-bg-sunken)] text-[var(--color-text)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)]">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <x-ui.button variant="ghost" size="sm" type="button" onclick="closeEditModal()" class="flex-1">Batal</x-ui.button>
                    <x-ui.button variant="primary" size="sm" type="submit" class="flex-1">Update</x-ui.button>
                </div>
            </form>
        </x-ui.glass-card>
    </div>

    @push('scripts')
        <script>
            function openCreateModal() {
                document.getElementById('modal-create-user').classList.remove('hidden');
            }
            function closeCreateModal() {
                document.getElementById('modal-create-user').classList.add('hidden');
            }
            function openEditModal(id, name, email, role, address) {
                const form = document.getElementById('edit-user-form');
                form.action = `/dashboard/users/${id}`;
                document.getElementById('edit-name').value = name;
                document.getElementById('edit-email').value = email;
                document.getElementById('edit-role').value = role;
                document.getElementById('edit-address').value = address;
                document.getElementById('modal-edit-user').classList.remove('hidden');
            }
            function closeEditModal() {
                document.getElementById('modal-edit-user').classList.add('hidden');
            }
            function filterUsers(query) {
                const q = query.toLowerCase();
                document.querySelectorAll('.user-row').forEach(row => {
                    const name = row.dataset.name || '';
                    const email = row.dataset.email || '';
                    row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
                });
            }
            // Close modals on backdrop click
            ['modal-create-user', 'modal-edit-user'].forEach(id => {
                document.getElementById(id).addEventListener('click', function(e) {
                    if (e.target === this) this.classList.add('hidden');
                });
            });
        </script>
    @endpush

@endsection