@extends('layouts.app')

@section('title', isset($expense) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran')
@section('page-title', 'Pengeluaran')
@section('breadcrumb', isset($expense) ? 'Edit' : 'Tambah')

@push('styles')
<style>
    .item-row { display:grid; grid-template-columns:1fr 100px 130px 36px; gap:0.65rem; align-items:start; }
    .remove-item { width:36px;height:36px;border:1px solid #FECACA;background:#FEF2F2;color:#DC2626;border-radius:9px;cursor:pointer;display:flex;align-items:center;justify-content:center;margin-top:24px;flex-shrink:0;transition:background 0.2s; }
    .remove-item:hover { background:#FEE2E2; }
    .total-box { background:var(--sage-pale);border-radius:12px;padding:1rem 1.25rem;display:flex;align-items:center;justify-content:space-between; }
</style>
@endpush

@section('content')

<div style="max-width:760px;">

<div class="page-header">
    <div>
        <h2>{{ isset($expense) ? 'Edit Pengeluaran' : 'Tambah Pengeluaran' }}</h2>
        <p>{{ isset($expense) ? 'Perbarui catatan pengeluaran' : 'Catat pengeluaran operasional baru' }}</p>
    </div>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ isset($expense) ? route('expenses.update', $expense->id) : route('expenses.store') }}" id="expenseForm">
        @csrf
        @if(isset($expense)) @method('PUT') @endif

        {{-- Header info --}}
        <div style="font-size:0.82rem;font-weight:600;color:var(--sage-deeper);margin-bottom:1rem;padding-bottom:0.5rem;border-bottom:1px solid #EAF0E7;text-transform:uppercase;letter-spacing:0.05em;">
            Informasi Pengeluaran
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Tanggal <span style="color:#C62828;">*</span></label>
                <input type="date" name="date" class="form-input @error('date') error @enderror"
                    value="{{ old('date', isset($expense) ? $expense->date?->format('Y-m-d') : now()->format('Y-m-d')) }}" required>
                @error('date')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kategori <span style="color:#C62828;">*</span></label>
                <select name="category" class="form-select @error('category') error @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['Operasional','Bahan Baku','Gaji','Utilitas','Peralatan','Lain-lain'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', $expense->category ?? '') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @error('category')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Deskripsi <span style="color:#C62828;">*</span></label>
                <input type="text" name="description" class="form-input @error('description') error @enderror"
                    value="{{ old('description', $expense->description ?? '') }}"
                    placeholder="Keterangan singkat pengeluaran" required>
                @error('description')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Catatan</label>
                <textarea name="notes" class="form-textarea" rows="2"
                    placeholder="Keterangan tambahan (opsional)">{{ old('notes', $expense->notes ?? '') }}</textarea>
            </div>
        </div>

        {{-- Items --}}
        <div style="font-size:0.82rem;font-weight:600;color:var(--sage-deeper);margin:1.25rem 0 1rem;padding-top:1rem;border-top:1px solid #EAF0E7;text-transform:uppercase;letter-spacing:0.05em;">
            Item Pengeluaran
        </div>

        <div id="itemsContainer">
            @forelse($expense->items ?? [['name'=>'','qty'=>1,'price'=>0]] as $i => $item)
            <div class="item-row" style="margin-bottom:0.65rem;" data-item>
                <div class="form-group" style="margin:0;">
                    @if($loop->first ?? true)
                    <label class="form-label">Nama Item</label>
                    @endif
                    <input type="text" name="items[{{ $i }}][name]" class="form-input"
                        value="{{ $item['name'] ?? $item->name ?? '' }}"
                        placeholder="Nama barang/jasa" required>
                </div>
                <div class="form-group" style="margin:0;">
                    @if($loop->first ?? true)
                    <label class="form-label">Qty</label>
                    @endif
                    <input type="number" name="items[{{ $i }}][qty]" class="form-input qty-input"
                        value="{{ $item['qty'] ?? $item->qty ?? 1 }}" min="1" required onchange="calcTotal()">
                </div>
                <div class="form-group" style="margin:0;">
                    @if($loop->first ?? true)
                    <label class="form-label">Harga Satuan</label>
                    @endif
                    <input type="number" name="items[{{ $i }}][price]" class="form-input price-input"
                        value="{{ $item['price'] ?? $item->unit_price ?? 0 }}" min="0" required onchange="calcTotal()">
                </div>
                <button type="button" class="remove-item" onclick="removeItem(this)" title="Hapus item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            @empty
            <div class="item-row" style="margin-bottom:0.65rem;" data-item>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Nama Item</label>
                    <input type="text" name="items[0][name]" class="form-input" placeholder="Nama barang/jasa" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Qty</label>
                    <input type="number" name="items[0][qty]" class="form-input qty-input" value="1" min="1" required onchange="calcTotal()">
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label">Harga Satuan</label>
                    <input type="number" name="items[0][price]" class="form-input price-input" value="0" min="0" required onchange="calcTotal()">
                </div>
                <button type="button" class="remove-item" onclick="removeItem(this)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            @endforelse
        </div>

        <button type="button" onclick="addItem()" class="btn btn-outline btn-sm" style="margin-bottom:1.5rem;">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Item
        </button>

        {{-- Total --}}
        <div class="total-box" style="margin-bottom:1.5rem;">
            <div style="font-size:0.9rem;font-weight:500;color:var(--sage-deeper);">Total Pengeluaran</div>
            <div style="font-size:1.35rem;font-weight:700;color:var(--sage-deeper);" id="grandTotal">Rp 0</div>
        </div>

        <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid #EAF0E7;">
            <a href="{{ route('expenses.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-sage">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                {{ isset($expense) ? 'Simpan Perubahan' : 'Simpan Pengeluaran' }}
            </button>
        </div>
    </form>
</div>

</div>
@endsection

@push('scripts')
<script>
let idx = document.querySelectorAll('[data-item]').length;

function addItem() {
    const container = document.getElementById('itemsContainer');
    const div = document.createElement('div');
    div.className = 'item-row';
    div.style.marginBottom = '0.65rem';
    div.dataset.item = '';
    div.innerHTML = `
        <div class="form-group" style="margin:0;">
            <input type="text" name="items[${idx}][name]" class="form-input" placeholder="Nama barang/jasa" required>
        </div>
        <div class="form-group" style="margin:0;">
            <input type="number" name="items[${idx}][qty]" class="form-input qty-input" value="1" min="1" required onchange="calcTotal()">
        </div>
        <div class="form-group" style="margin:0;">
            <input type="number" name="items[${idx}][price]" class="form-input price-input" value="0" min="0" required onchange="calcTotal()">
        </div>
        <button type="button" class="remove-item" onclick="removeItem(this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>`;
    container.appendChild(div);
    idx++;
    calcTotal();
}

function removeItem(btn) {
    const rows = document.querySelectorAll('[data-item]');
    if (rows.length <= 1) return;
    btn.closest('[data-item]').remove();
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('[data-item]').forEach(row => {
        const qty = parseFloat(row.querySelector('.qty-input')?.value || 0);
        const price = parseFloat(row.querySelector('.price-input')?.value || 0);
        total += qty * price;
    });
    document.getElementById('grandTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

calcTotal();
</script>
@endpush