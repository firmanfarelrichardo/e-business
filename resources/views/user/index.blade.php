@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page-title', 'Pengguna')
@section('breadcrumb', 'Manajemen Pengguna')

@section('content')

<div class="page-header">
    <div>
        <h2>Manajemen Pengguna</h2>
        <p>Kelola semua akun pengguna sistem</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-sage">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Pengguna
    </a>
</div>

{{-- Filter bar --}}
<div class="card card-sm" style="margin-bottom:1.25rem;">
    <form method="GET" action="{{ route('users.index') }}" style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center;">
        <div style="position:relative;flex:1;min-width:200px;">
            <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);opacity:0.45;" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#4A5645" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="form-input" style="padding-left:2.25rem;" placeholder="Cari nama atau email…" value="{{ request('search') }}">
        </div>
        <select name="role" class="form-select" style="width:auto;">
            <option value="">Semua Role</option>
            <option value="member"   {{ request('role')=='member'   ? 'selected':'' }}>Member</option>
            <option value="employee" {{ request('role')=='employee' ? 'selected':'' }}>Karyawan</option>
            <option value="owner"    {{ request('role')=='owner'    ? 'selected':'' }}>Pemilik</option>
        </select>
        <select name="status" class="form-select" style="width:auto;">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Aktif</option>
            <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn btn-sage">Filter</button>
        @if(request()->anyFilled(['search','role','status']))
            <a href="{{ route('users.index') }}" class="btn btn-outline">Reset</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card" style="padding:0;overflow:hidden;">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="padding-left:1.5rem;">Pengguna</th>
                    <th>Email</th>
                    <th>No. Telepon</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th style="text-align:right;padding-right:1.5rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $user)
                <tr>
                    <td style="padding-left:1.5rem;">
                        <div style="display:flex;align-items:center;gap:0.65rem;">
                            <div style="width:36px;height:36px;background:var(--sage-pale);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.8rem;font-weight:600;color:var(--sage-deeper);flex-shrink:0;">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:0.875rem;">{{ $user->name }}</div>
                                <div style="font-size:0.72rem;color:#9CA89A;">#{{ $user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:#7A8A78;">{{ $user->email }}</td>
                    <td style="color:#7A8A78;">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @php $roleColors = ['owner'=>'badge-sage','employee'=>'badge-blue','member'=>'badge-gray']; @endphp
                        <span class="badge {{ $roleColors[$user->role] ?? 'badge-gray' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $user->status === 'active' ? 'badge-green' : 'badge-red' }}">
                            {{ $user->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="color:#9CA89A;font-size:0.8rem;">{{ $user->created_at?->format('d M Y') }}</td>
                    <td style="text-align:right;padding-right:1.5rem;">
                        <div style="display:flex;gap:0.4rem;justify-content:flex-end;">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline btn-sm">Edit</a>
                            <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:#9CA89A;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#D0DAC8" stroke-width="1.5" style="display:block;margin:0 auto 0.75rem;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        Belum ada pengguna
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($users) && $users->hasPages())
    <div style="padding:1rem 1.5rem;border-top:1px solid #EAF0E7;display:flex;align-items:center;justify-content:space-between;">
        <div style="font-size:0.8rem;color:#9CA89A;">
            Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
        </div>
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>

@endsection