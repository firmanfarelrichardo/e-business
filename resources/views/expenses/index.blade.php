@extends('layouts.app')

@section('title', 'Pengeluaran')
@section('page-title', 'Pengeluaran')
@section('breadcrumb', 'Manajemen Pengeluaran')

@section('content')

<div class="page-header">
    <div>
        <h2>Manajemen Pengeluaran</h2>
        <p>Catat dan pantau semua pengeluaran operasional</p>
    </div>
    <a href="{{ route('expenses.create') }}" class="btn btn-sage">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Pengeluaran
    </a>
</div>

{{-- Summary cards --}}
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.25rem;">
    <div class="stat-card">
        <div class="stat-label">Total Bulan Ini</div>
        <div class="stat-value" style="font-size:1.4rem;">Rp {{ number_format($summary['month'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub">{{ $summary['count_month'] ?? 0 }} transaksi</div>
        <div class="stat-icon" style="background:#FFEBEE;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C62828" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Hari Ini</div>
        <div class="stat-value" style="font-size:1.4rem;">Rp {{ number_format($summary['today'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-sub">{{ $summary['count_today'] ?? 0 }} transaksi</div>
        <div class="stat-icon" style="background:#FFF8E1;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F57F17" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Kategori Terbesar</div>
        <div class="stat-value" style="font-size:1.2rem;font-family:'DM Sans';">{{ $summary['top_category'] ?? '—' }}</div>
        <div class="stat-sub">Rp {{ number_format($summary['top_category_amount'] ?? 0, 0, ',', '.') }}</div>
        <div class="stat-icon" style="background:var(--sage-pale);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--sage-deeper)" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
        </div>
    </div>
</div>

{{-- Filter --}}
<div class="card card-sm" style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('expenses.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <div style="position:relative;flex:1;min-width:180px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);opacity:0.45;" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4A5645" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="form-input" style="padding-left:2.25rem;" placeholder="Cari deskripsi…" value="{{ request('search') }}">
        </div>
        <select name="category" class="form-select" style="width:auto;">
            <option value="">Semua Kategori</option>
            @foreach($categories ?? ['Operasional','Bahan Baku','Gaji','Utilitas','Lain-lain'] as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" class="form-input" style="width:auto;" value="{{ request('date_from') }}">
        <input type="date" name="date_to" class="form-input" style="width:auto;" value="{{ request('date_to') }}">
        <button type="submit" class="btn btn-sage">Filter</button>
        @if(request()->anyFilled(['search','category','date_from','date_to']))
            <a href="{{ route('expenses.index') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="padding-left:1.5rem;">Tanggal</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Dicatat Oleh</th>
                    <th style="text-align:right;">Total</th>
                    <th style="text-align:right;padding-right:1.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses ?? [] as $expense)
                <tr>
                    <td style="padding-left:1.5rem;color:#9CA89A;font-size:0.82rem;white-space:nowrap;">
                        {{ $expense->date?->format('d M Y') ?? now()->format('d M Y') }}
                    </td>
                    <td>
                        <div style="font-weight:500;font-size:0.875rem;">{{ $expense->description ?? 'Pengeluaran' }}</div>
                        @if($expense->notes ?? false)
                        <div style="font-size:0.72rem;color:#9CA89A;">{{ Str::limit($expense->notes, 50) }}</div>
                        @endif
                    </td>
                    <td>
                        @php
                        $catColors = ['Operasional'=>'badge-blue','Bahan Baku'=>'badge-sage','Gaji'=>'badge-amber','Utilitas'=>'badge-gray'];
                        $cat = $expense->category ?? 'Lain-lain';
                        @endphp
                        <span class="badge {{ $catColors[$cat] ?? 'badge-gray' }}">{{ $cat }}</span>
                    </td>
                    <td style="color:#7A8A78;font-size:0.85rem;">{{ $expense->user->name ?? 'Admin' }}</td>
                    <td style="text-align:right;font-weight:600;color:var(--sage-deeper);">
                        Rp {{ number_format($expense->total_amount ?? $expense->amount ?? 0, 0, ',', '.') }}
                    </td>
                    <td style="text-align:right;padding-right:1.5rem;">
                        <div style="display:flex;gap:0.4rem;justify-content:flex-end;">
                            <a href="{{ route('expenses.show', $expense->id) }}" class="btn btn-outline btn-sm">Detail</a>
                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form method="POST" action="{{ route('expenses.destroy', $expense->id) }}" onsubmit="return confirm('Hapus pengeluaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:3rem;color:#9CA89A;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#D0DAC8" stroke-width="1.5" style="display:block;margin:0 auto 0.75rem;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        Belum ada data pengeluaran
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($expenses) && method_exists($expenses, 'hasPages') && $expenses->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #EAF0E7;">
        {{ $expenses->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection