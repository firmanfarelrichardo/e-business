# Modul: Frontend / UI

**Penanggung Jawab:** Alyaa (User Management, Expenses, Dashboard & Frontend)

## Deskripsi
Semua perancangan antarmuka pengguna *(User Interface)* yang menjembatani *backend* dengan fungsionalitas bagi admin, kasir, maupun konsumen publik.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Halaman kasir (buat order)** | Layar *Point of Sales (POS)* untuk staf dalam melayani pembuatan antrean dan pilihan keranjang belanja di kasir. | *Semua tabel master* |
| **Halaman admin (dashboard)** | Sistem tata kelola utama (*Content Management System*) yang kompleks bagi admin. | *Semua tabel master* |
| **Halaman customer (lihat histori)** | Portal mandiri untuk konsumen melacak pesanan mereka atau melihat riwayat. | `orders`, `transactions` |
| **Cetak struk / invoice** | Fungsi generasi file PDF atau mencetak secara fisik dengan printer kasir (*thermal*). | `orders`, `transactions` |
