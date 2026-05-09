

    @push('scripts')
        <script type="module">
            import { animate, stagger } from "https://esm.sh/motion";

            // Smooth entry animations for the profile interface
            animate(
                ".motion-title",
                { y: [20, 0], opacity: [0, 1] },
                { duration: 0.5, easing: "ease-out" }
            );
            animate(
                ".motion-card",
                { y: [20, 0], opacity: [0, 1] },
                { delay: stagger(0.1), duration: 0.5, easing: "ease-out" }
            );
        </script>
    @endpush

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800 tracking-tight motion-title">Pengaturan Profil</h1>
        <p class="text-sm text-gray-500 motion-title mt-1">Kelola data personal dan keamanan akun Anda</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 mb-8">

        <!-- Left Column: Avatar & Password -->
        <div class="w-full lg:w-1/3 flex flex-col gap-6">

            <!-- Avatar Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 motion-card">
                <h3 class="text-sm font-bold text-gray-800 mb-6">Account Management</h3>

                <div class="flex flex-col items-center relative">
                @if($user->profile)
                    <div class="w-40 h-40 rounded-2xl bg-cover bg-center shadow-inner mb-6 relative group overflow-hidden" style="background-image: url('{{ asset('storage/' . $user->profile) }}')">
                        <label for="photo-upload" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                    </div>
                @else
                    <div class="w-40 h-40 rounded-2xl bg-gradient-to-br from-[#7B9B6F] to-[#5A6852] text-white flex items-center justify-center text-5xl font-bold shadow-inner mb-6 relative group overflow-hidden">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        
                        <label for="photo-upload" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-10">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </label>
                    </div>
                @endif
                
                <form action="{{ route('profile.uploadPhoto') }}" method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf
                    <input type="file" name="photo" id="photo-upload" class="hidden" accept="image/*" onchange="this.form.submit()">
                    <label for="photo-upload" class="block text-center w-full py-2.5 bg-white border border-gray-200 text-gray-700 font-medium text-sm rounded-xl cursor-pointer hover:bg-gray-50 transition shadow-sm">
                        Upload Photo
                    </label>
                </form>
            </div>
            </div>

            <!-- Change Password Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 motion-card">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 label-required">Old Password</label>
                        <input type="password" placeholder="••••••••"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 label-required">New Password</label>
                        <input type="password" placeholder="••••••••"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div class="pt-2">
                        <button
                            class="w-full py-2.5 bg-white border border-gray-200 text-gray-700 font-medium text-sm rounded-xl hover:bg-gray-50 transition shadow-sm">
                            Change Password
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Profile Information -->
        <div class="w-full lg:w-2/3 flex flex-col gap-6">

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 motion-card">

                <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                    <h3 class="text-sm font-bold text-gray-800">Profile Information</h3>
                    <button
                        class="text-xs bg-[#7B9B6F] text-white px-3 py-1.5 rounded-lg hover:bg-[#5A6852] transition">Simpan
                        Perubahan</button>
                </div>

                <!-- Grid Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Username</label>
                        <input type="text" value="{{ $user->username }}"
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}"
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Peran (Role)</label>
                        <select disabled
                            class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed appearance-none">
                            <option selected>{{ ucfirst($user->role) }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Alamat Domisili</label>
                        <input type="text" value="{{ $user->address ?? '-' }}" placeholder="Masukkan alamat..."
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                </div>

                <h3 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-50 pb-2">Contact Info</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 mb-8">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5 label-required">Email</label>
                        <input type="email" value="{{ $user->email }}"
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input type="text" value="{{ $user->phone ?? '-' }}" placeholder="08..."
                            class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition">
                    </div>
                </div>

                <h3 class="text-sm font-bold text-gray-800 mb-4 border-b border-gray-50 pb-2">About the User</h3>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Biographical Info / Catatan</label>
                    <textarea rows="4"
                        class="w-full bg-gray-50/50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#7B9B6F] transition leading-relaxed"
                        placeholder="Tulis sedikit tentang identitas atau referensi kustom Anda...">Seorang {{ ucfirst($user->role) }} yang telah bergabung di Sinergi platform sejak {{ optional($user->created_at)->format('d F Y') ?? 'lama' }}.</textarea>
                </div>

            </div>

        </div>

    </div>

    <!-- Histori Pembelian (Hanya tampil untuk Member/Pelanggan) -->
    @if(auth()->user()->role === 'member')
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 motion-card">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    Riwayat Pembelian & Pesanan
                    <span
                        class="bg-gray-100 text-gray-600 text-[10px] px-2 py-0.5 rounded-full font-bold">{{ count($history ?? []) }}</span>
                </h3>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">No. Order / Q
                            </th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Metode Bayar</th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Nominal
                            </th>
                            <th class="py-3 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($history ?? [] as $order)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-4">
                                    <div class="text-sm font-semibold text-gray-800">INV-{{ substr($order->id, 0, 8) }}</div>
                                    <div class="text-xs text-gray-500">Antrean: <span
                                            class="font-bold text-[#7B9B6F]">{{ $order->queue_number }}</span></div>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600">{{ $order->payment_method ?? 'Cash' }}</td>
                                <td class="py-3 px-4 text-sm font-semibold text-gray-800">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-right">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-orange-100 text-orange-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'done' => 'bg-green-100 text-green-700',
                                            'completed' => 'bg-green-100 text-green-700',
                                            'paid' => 'bg-green-100 text-green-700',
                                            'canceled' => 'bg-red-100 text-red-700'
                                        ];
                                        $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                                    @endphp
                                    <span class="inline-flex py-1 px-2.5 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-400">
                                    <svg class="w-12 h-12 mb-3 text-gray-300 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1.5"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <p class="text-sm">Anda belum pernah melakukan pemesanan.</p>
                                    <a href="/katalog" class="text-xs text-[#7B9B6F] hover:underline mt-1 block">Mulai Belanja
                                        &rarr;</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif