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
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight motion-title">Manajemen Pengguna</h1>
        <p class="text-sm text-gray-500 motion-title mt-1">Daftar semua pengguna dan peran mereka di platform Sinergi</p>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm motion-title">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm motion-title">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Control Panel -->
    <div class="motion-title bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        <div class="relative w-full md:w-80">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input id="user-search" type="text" placeholder="Cari nama atau email..."
                class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 pl-10 pr-4 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] focus:bg-white transition"
                oninput="filterUsers(this.value)">
        </div>

        <button onclick="openCreateModal()"
            class="w-full md:w-auto bg-[#7B9B6F] hover:bg-[#5A6852] text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Pengguna
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden motion-title">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="users-table">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peran (Role)</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="py-4 px-6 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="users-tbody">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition motion-table-row user-row"
                            data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#7B9B6F] to-[#5A6852] text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ '@' . $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm text-gray-800">{{ $user->email }}</div>
                                <div class="text-xs text-gray-500">{{ $user->phone ?? 'Belum ada no. telp' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($user->role === 'owner')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200">Owner</span>
                                @elseif($user->role === 'employee')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">Employee</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">Member</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    <span class="text-sm text-gray-600">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <button
                                    onclick="openEditModal('{{ $user->id }}','{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->role }}','{{ addslashes($user->address ?? '') }}')"
                                    class="text-gray-400 hover:text-[#7B9B6F] transition p-1" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('dashboard.users.destroy', $user->id) }}"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin hapus pengguna {{ addslashes($user->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition p-1 ml-1" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">Belum ada data pengguna.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== CREATE USER MODAL ===== -->
    <div id="modal-create-user" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Tambah Pengguna Baru</h2>
                <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form action="{{ route('dashboard.users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required maxlength="50" placeholder="Nama lengkap pengguna"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" required maxlength="50" placeholder="username unik"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required maxlength="50" placeholder="email@domain.com"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="6" placeholder="Min. 6 karakter"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Peran (Role) <span class="text-red-500">*</span></label>
                        <select name="role" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition bg-white">
                            <option value="member">Member</option>
                            <option value="employee">Employee</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat</label>
                        <input type="text" name="address" maxlength="50" placeholder="Alamat lengkap (opsional)"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeCreateModal()"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#7B9B6F] hover:bg-[#5A6852] text-white rounded-xl text-sm font-semibold transition shadow">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== EDIT USER MODAL ===== -->
    <div id="modal-edit-user" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">Edit Pengguna</h2>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
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
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="edit-name" required maxlength="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="edit-email" required maxlength="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Peran (Role) <span class="text-red-500">*</span></label>
                        <select name="role" id="edit-role" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition bg-white">
                            <option value="member">Member</option>
                            <option value="employee">Employee</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                        <input type="password" name="password" minlength="6" placeholder="Biarkan kosong"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat</label>
                        <input type="text" name="address" id="edit-address" maxlength="50"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 py-2.5 border border-gray-200 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-50 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2.5 bg-[#7B9B6F] hover:bg-[#5A6852] text-white rounded-xl text-sm font-semibold transition shadow">Update</button>
                </div>
            </form>
        </div>
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