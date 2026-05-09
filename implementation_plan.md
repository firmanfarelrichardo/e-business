# Rencana Implementasi: Order Fulfillment & Cetak Invoice

Modul ini menyelesaikan siklus pesanan (Order Management) untuk staf kasir/admin.

## User Review Required

> [!IMPORTANT]
> **Penyesuaian Penamaan Status (Mapping)**
> Anda meminta status: `pending`, `process`, `finish`, `canceled`.
> Berdasarkan skema database dan Model `Order` yang sudah ada, nilai ENUM status adalah: `pending`, `processing`, `completed`, `cancelled`.
> **Solusi**: Saya akan tetap menggunakan ENUM asli bawaan database (`processing` untuk process, `completed` untuk finish, `cancelled` untuk canceled) agar tidak perlu merusak struktur DB yang sudah berjalan, namun *label di UI* akan ditampilkan sebagai "Diproses", "Selesai", dan "Dibatalkan". Apakah Anda setuju dengan pendekatan ini?

## Proposed Changes

### 1. Order Fulfillment Logic (Service & Repository)

#### [MODIFY] app/Services/OrderService.php
- **Penyempurnaan `updateOrderStatus()` dan `deductStock()`**: Saya akan menyuntikkan fungsi **`lockForUpdate()`** ke dalam iterasi item sebelum pemotongan stok.
- **Mekanisme (CRITICAL HOOK)**:
  1. Saat status diubah ke `completed` (finish), sistem membuka transaksi `DB::transaction()`.
  2. Melakukan *pessimistic locking* pada tabel `product_brands` (atau `batches`) melalui `BatchRepository` terkait untuk mencegah *race condition* kasir lain yang menekan tombol bersamaan.
  3. Memotong `current_stock` pada *Batch* secara FIFO.
  4. Commit transaksi. Jika gagal, rollback total.

### 2. Dashboard Manajemen Antrean (Controller & UI)

#### [MODIFY] app/Http/Controllers/Web/OrderController.php
- Menambahkan logika di metode `index()` untuk menarik semua pesanan hari ini.
- Membawa data ke Blade dengan pemisahan status (Pending, Processing, Completed) agar bisa di-render dalam struktur Tab UI.

#### [NEW] resources/views/dashboard/queues/index.blade.php
- **Antarmuka Real-time Kasir**: Menggunakan desain *Glassmorphism*.
- Membagi pesanan ke dalam 3 kolom/tab:
  - **Menunggu (Pending)**: Tombol aksi "Proses Pesanan".
  - **Diproses (Processing)**: Tombol aksi "Selesaikan Pesanan".
  - **Selesai (Completed)**: Tombol aksi "Cetak Struk/Invoice".

### 3. Cetak Invoice / Struk (Thermal Printer)

#### [NEW] resources/views/invoice/show.blade.php
- **Format Cetak Khusus**: Tidak ada sidebar, navigasi, atau elemen *dashboard* lainnya.
- **CSS Media Print (`@media print`)**: Mengatur lebar struk agar responsif terhadap *Thermal Printer* (58mm/80mm) atau A4 (jika dibuka di komputer standar).
- **Konten**: Menampilkan detail E-Business, Nomor Antrean pesanan (`queue_number`), Daftar Item (Produk/Jasa), Qty, Harga, Total, dan ucapan terima kasih.

## Verification Plan

1. **Uji Coba Pengubahan Status**:
   - Memastikan tombol "Proses" mengubah pesanan jadi `processing` tanpa memotong stok.
   - Memastikan tombol "Selesaikan" mengubah pesanan jadi `completed` dan **memotong stok** di `batches` secara FIFO dengan aman.
2. **Uji Coba Tampilan Print**: Mengakses URL `/invoice/{id}` dan mengecek apakah formatnya murni kertas struk tanpa gangguan UI lain.
