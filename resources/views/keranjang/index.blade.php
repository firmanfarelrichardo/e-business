<x-layouts.app title="Keranjang Belanja - Sinergi">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-8">
        @if(session('success'))
            <div class="mb-4 rounded-[var(--radius-md)] border border-[var(--accent-teal)]/20 bg-[var(--accent-teal)]/10 px-4 py-3 text-sm text-[var(--accent-teal)] font-medium" data-testid="cart-success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-[var(--radius-md)] border border-[var(--accent-rose)]/20 bg-[var(--accent-rose)]/10 px-4 py-3 text-sm text-[var(--accent-rose)] font-medium" data-testid="cart-error-alert">
                {{ session('error') }}
            </div>
        @endif

        {{-- Title bar with back arrow --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ url('/katalog') }}" class="w-10 h-10 glass-panel rounded-full flex items-center justify-center text-[var(--color-text-muted)] hover:text-[var(--color-primary)] transition" data-testid="cart-back-link" aria-label="Kembali ke katalog">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="font-display font-bold text-[var(--color-text)]" style="font-size: clamp(1.75rem, 2vw + 1rem, 2.25rem);">Keranjang Belanja</h1>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Left: Cart Items --}}
            <div class="w-full lg:w-2/3">
                <x-ui.glass-card variant="default" padding="none" class="overflow-visible">

                    {{-- Table Header --}}
                    <div class="hidden md:grid grid-cols-12 gap-4 px-8 py-4 border-b border-[var(--color-border-subtle)]" style="background: var(--color-bg-sunken);">
                        <div class="col-span-6 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] font-display">Produk</div>
                        <div class="col-span-3 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] text-center font-display">Kuantitas</div>
                        <div class="col-span-3 text-[10px] font-bold text-[var(--color-text-muted)] uppercase tracking-[0.08em] text-right font-display">Subtotal</div>
                    </div>

                    {{-- Cart Items Loop --}}
                    @forelse($cart as $key => $item)
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center px-6 md:px-8 py-6 border-b border-[var(--color-border-subtle)] hover:bg-[var(--color-bg-elevated)]/50 transition-colors" data-testid="cart-item-{{ $key }}">
                            <div class="col-span-1 md:col-span-6 flex items-center gap-4">
                                <div class="w-20 h-20 rounded-[var(--radius-md)] p-2 shrink-0 border border-[var(--color-border-subtle)] shadow-[var(--shadow-xs)] flex items-center justify-center" style="background: var(--color-bg-sunken);">
                                    @if($item['image'])
                                        <img src="{{ $item['image'] }}" class="w-full h-full object-contain" alt="{{ $item['name'] }}">
                                    @else
                                        @if($item['type'] === 'service')
                                            <svg class="w-10 h-10 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        @else
                                            <svg class="w-10 h-10 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        @endif
                                    @endif
                                </div>
                                <div>
                                    @if($item['type'] === 'service')
                                        <x-ui.badge variant="info" size="xs" class="mb-1">Jasa</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="premium" size="xs" class="mb-1">Produk</x-ui.badge>
                                    @endif
                                    <h4 class="font-bold text-[var(--color-text)] text-lg mb-1 font-display">{{ $item['name'] }}</h4>
                                    <p class="text-sm text-[var(--color-primary)] font-semibold font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($item['price'], 0, ',', '.') }} <span class="text-xs text-[var(--color-text-muted)] font-normal font-sans">/ unit</span></p>

                                    @if($item['type'] === 'service')
                                        <x-ui.glass-card variant="sky" padding="sm" class="mt-3">
                                            <label class="block text-[10px] font-bold uppercase tracking-[0.08em] text-[var(--color-text-muted)] mb-2 font-display">Dokumen Cetak (PDF/DOC/IMG)</label>
                                            <div class="flex items-center gap-3">
                                                <input type="file"
                                                       id="doc-{{ $key }}"
                                                       class="hidden"
                                                       onchange="uploadDocument('{{ $key }}')">
                                                <x-ui.button variant="outline" size="xs" type="button"
                                                    onclick="document.getElementById('doc-{{ $key }}').click()"
                                                    data-testid="cart-upload-button-{{ $key }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                    {{ isset($item['document_path']) ? 'Ganti File' : 'Pilih File' }}
                                                </x-ui.button>
                                                <div id="status-{{ $key }}" class="text-[10px] font-medium text-[var(--color-text-muted)] truncate max-w-[150px]">
                                                    {{ $item['document_filename'] ?? 'Belum ada file' }}
                                                </div>
                                            </div>
                                        </x-ui.glass-card>
                                    @endif
                                </div>
                            </div>
                            <div class="col-span-1 md:col-span-3 flex items-center justify-between md:justify-center mt-4 md:mt-0">
                                <span class="md:hidden text-sm text-[var(--color-text-muted)] font-bold">Kuantitas:</span>
                                <div class="flex items-center gap-2 glass-panel px-2 py-1 rounded-full" data-testid="cart-quantity-stepper-{{ $key }}">
                                    <form action="{{ url('/cart/update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <input type="hidden" name="action" value="decrease">
                                        <button type="submit" class="w-7 h-7 rounded-full flex items-center justify-center text-[var(--color-text-muted)] hover:text-[var(--color-primary)] hover:bg-[var(--color-bg-elevated)] transition" aria-label="Kurangi"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></button>
                                    </form>
                                    <span class="font-bold text-[var(--color-text)] w-6 text-center font-mono">{{ $item['quantity'] }}</span>
                                    <form action="{{ url('/cart/update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <input type="hidden" name="action" value="increase">
                                        <button class="w-7 h-7 rounded-full flex items-center justify-center text-[var(--color-text-muted)] hover:text-[var(--color-primary)] hover:bg-[var(--color-bg-elevated)] transition" aria-label="Tambah"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-span-1 md:col-span-3 flex items-center justify-between md:justify-end mt-2 md:mt-0">
                                <span class="md:hidden text-sm text-[var(--color-text-muted)] font-bold">Subtotal:</span>
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-[var(--color-text)] text-lg font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                    <form action="{{ url('/cart/remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cart_key" value="{{ $key }}">
                                        <button type="submit" class="w-8 h-8 rounded-full flex items-center justify-center text-[var(--accent-rose)] hover:bg-[var(--accent-rose)]/10 transition" aria-label="Hapus item" data-testid="cart-remove-button-{{ $key }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: var(--color-bg-sunken);">
                                <svg class="w-10 h-10 text-[var(--color-text-muted)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
                                </svg>
                            </div>
                            <p class="text-[var(--color-text-muted)] mb-4">Keranjang belanja Anda masih kosong.</p>
                            <x-ui.button variant="primary" size="sm" as="a" href="{{ url('/katalog') }}" data-testid="cart-empty-cta">Mulai Belanja</x-ui.button>
                        </div>
                    @endforelse
                </x-ui.glass-card>
            </div>

            {{-- Right: Order Summary --}}
            <div class="w-full lg:w-1/3">
                <x-ui.glass-card variant="aurora" padding="lg" class="sticky top-32" data-testid="cart-order-summary">
                    <h3 class="text-xl font-bold text-[var(--color-text)] mb-6 font-display">Ringkasan Pesanan</h3>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-[var(--color-text-muted)]">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-[var(--color-text)] font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($totalProduk, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[var(--color-text-muted)]">
                            <span>Biaya Layanan</span>
                            <span class="font-medium text-[var(--color-text)] font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($totalJasa, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-[var(--color-border)] pt-6 mb-8 flex justify-between items-end">
                        <span class="text-[var(--color-text-muted)] font-medium">Total Harga</span>
                        <span class="text-3xl font-extrabold text-[var(--color-text)] tracking-tight font-mono" style="font-variant-numeric: tabular-nums;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ url('/orders') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-[var(--color-text-muted)] text-sm font-medium mb-2">Catatan Pesanan (Opsional)</label>
                            <textarea name="note" class="w-full bg-[var(--color-bg-sunken)] border border-[var(--color-border)] rounded-[var(--radius-sm)] px-4 py-3 text-[var(--color-text)] placeholder:text-[var(--color-text-muted)] outline-none transition-all duration-200 focus:bg-[var(--color-bg-elevated)] focus:border-[var(--color-primary)] focus:shadow-[0_0_0_3px_rgba(30,133,251,0.18)] backdrop-blur-sm text-sm" rows="3" placeholder="Misal: Tolong dipisahkan plastiknya..." data-testid="cart-note-input"></textarea>
                        </div>

                        <x-ui.button variant="aurora" size="lg" type="submit" class="w-full" data-testid="cart-checkout-button">
                            Proses Checkout
                        </x-ui.button>
                    </form>

                    <p class="text-center text-[var(--color-text-muted)] text-xs mt-4 flex items-center justify-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
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

            statusDiv.innerHTML = '<span class="text-[var(--color-primary)] animate-pulse">Mengupload...</span>';

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
                    statusDiv.innerHTML = '<span class="text-[var(--accent-teal)] font-bold">✓ ' + data.filename + '</span>';
                } else {
                    statusDiv.innerHTML = '<span class="text-[var(--accent-rose)]">' + (data.message || 'Gagal upload') + '</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusDiv.innerHTML = '<span class="text-[var(--accent-rose)]">Error sistem</span>';
            });
        }
    </script>
</x-layouts.app>
