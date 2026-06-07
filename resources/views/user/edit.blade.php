@extends('layouts.app')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page-title', 'Pengguna')
@section('breadcrumb', isset($user) ? 'Edit' : 'Tambah')

@section('content')

<div style="max-width:680px;">

<div class="page-header">
    <div>
        <h2>{{ isset($user) ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</h2>
        <p>{{ isset($user) ? 'Perbarui informasi akun pengguna' : 'Isi data untuk membuat akun baru' }}</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali
    </a>
</div>

<div class="card">
    <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        {{-- Avatar preview --}}
        <div style="display:flex;align-items:center;gap:1rem;padding-bottom:1.5rem;margin-bottom:1.5rem;border-bottom:1px solid #EAF0E7;">
            <div id="avatarPreview" style="width:60px;height:60px;background:var(--sage);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:'DM Serif Display',serif;font-size:1.4rem;color:white;">
                {{ isset($user) ? strtoupper(substr($user->name,0,2)) : 'AB' }}
            </div>
            <div>
                <div style="font-size:0.85rem;font-weight:500;color:var(--sage-deeper);">Avatar Otomatis</div>
                <div style="font-size:0.75rem;color:#9CA89A;">Dibuat dari inisial nama pengguna</div>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:#C62828;">*</span></label>
                <input type="text" name="name" class="form-input @error('name') error @enderror"
                    value="{{ old('name', $user->name ?? '') }}"
                    placeholder="Nama lengkap" required
                    oninput="updateAvatar(this.value)">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email <span style="color:#C62828;">*</span></label>
                <input type="email" name="email" class="form-input @error('email') error @enderror"
                    value="{{ old('email', $user->email ?? '') }}"
                    placeholder="nama@email.com" required>
                @error('email')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">No. Telepon</label>
                <input type="tel" name="phone" class="form-input @error('phone') error @enderror"
                    value="{{ old('phone', $user->phone ?? '') }}"
                    placeholder="08xx-xxxx-xxxx">
                @error('phone')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Role <span style="color:#C62828;">*</span></label>
                <select name="role" class="form-select @error('role') error @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="member"   {{ old('role', $user->role ?? '') == 'member'   ? 'selected' : '' }}>Member</option>
                    <option value="employee" {{ old('role', $user->role ?? '') == 'employee' ? 'selected' : '' }}>Karyawan (Employee)</option>
                    <option value="owner"    {{ old('role', $user->role ?? '') == 'owner'    ? 'selected' : '' }}>Pemilik (Owner)</option>
                </select>
                @error('role')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status <span style="color:#C62828;">*</span></label>
                <select name="status" class="form-select @error('status') error @enderror" required>
                    <option value="active"   {{ old('status', $user->status ?? 'active') == 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')<p class="form-error">{{ $message }}</p>@enderror
            </div>

            {{-- Alamat --}}
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Alamat</label>
                <textarea name="address" class="form-textarea" rows="2"
                    placeholder="Alamat lengkap (opsional)">{{ old('address', $user->address ?? '') }}</textarea>
            </div>
        </div>

        {{-- Password section --}}
        <div style="border-top:1px solid #EAF0E7;padding-top:1.25rem;margin-top:0.5rem;">
            <div style="font-size:0.85rem;font-weight:600;color:var(--sage-deeper);margin-bottom:1rem;">
                {{ isset($user) ? 'Ganti Password (opsional)' : 'Password' }}
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">
                        Password {{ !isset($user) ? '<span style="color:#C62828;">*</span>' : '' }}
                    </label>
                    <input type="password" name="password" class="form-input @error('password') error @enderror"
                        placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Min. 8 karakter' }}"
                        {{ !isset($user) ? 'required' : '' }}>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-input"
                        placeholder="Ulangi password">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div style="display:flex;gap:0.75rem;justify-content:flex-end;padding-top:1rem;border-top:1px solid #EAF0E7;margin-top:0.5rem;">
            <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-sage">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                {{ isset($user) ? 'Simpan Perubahan' : 'Buat Pengguna' }}
            </button>
        </div>
    </form>
</div>

</div>

@endsection

@push('scripts')
<script>
function updateAvatar(name) {
    const el = document.getElementById('avatarPreview');
    const initials = name.trim().split(' ').map(w => w[0]).slice(0,2).join('').toUpperCase() || 'AB';
    el.textContent = initials;
}
</script>
@endpush