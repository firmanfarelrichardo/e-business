# Modul: Manajemen User

**Penanggung Jawab:** Alyaa (User Management, Expenses, Dashboard & Frontend)  
**Fokus Utama:** Sistem Autentikasi dan *Role-Based Access Control* (RBAC).

## Deskripsi
Modul yang menangani registrasi, log masuk (login), dan penentuan hak akses pengguna dalam sistem aplikasi.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **CRUD User** | Menambahkan, mengedit, dan menghapus (soft-delete) pengguna sistem (*member*, *employee*, *owner*). | `users` |
| **Login / Logout / Auth** | Menangani alur keamanan autentikasi pengguna. | `users` |
| **Role-based access control** | Mengatur izin akses halaman berdasarkan peran pengguna. | `users.role` |
| **Aktif/nonaktif user** | Menonaktifkan akun (*banned*) tanpa harus menghapus data secara permanen. | `users.is_active` |
