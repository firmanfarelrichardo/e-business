# Rencana Implementasi: COGS / HPP Snapshotting

Fitur ini akan mengamankan laporan laba historis (*Data Immutability*) dengan menyimpan nilai Harga Pokok Penjualan (HPP/COGS) ke dalam setiap item pesanan pada saat pesanan diselesaikan, sehingga kebal terhadap perubahan harga modal di masa depan.

## User Review Required

> [!IMPORTANT]
> **Metode Perhitungan COGS: WAC vs Exact FIFO**
> Anda menginstruksikan untuk mengambil nilai WAC terbaru. Namun, karena sistem pemotongan stok Anda di `OrderService` sudah menggunakan **Metode FIFO (First-In, First-Out) yang sangat presisi**, mengambil nilai WAC (rata-rata) justru akan mengurangi keakuratan laporan laba.
> **Usulan Saya**: Di dalam loop pemotongan stok FIFO, saya akan langsung menghitung **Exact COGS** berdasarkan `purchase_price` asli dari masing-masing *Batch* yang terpotong. Ini memberikan angka HPP yang 100% akurat sesuai fisik barang yang keluar.
> Apakah Anda setuju menggunakan Exact FIFO Cost untuk kolom `cogs` ini?

## Proposed Changes

### 1. Database Schema Update

#### [NEW] database/migrations/..._add_cogs_to_order_items_table.php
Menambahkan kolom baru:
- `cogs` (Decimal `15, 0`, `nullable`, `default(0)`): Menyimpan total Harga Pokok Penjualan untuk baris item tersebut (bukan harga satuan, melainkan total HPP dari `qty`).

### 2. Model Update

#### [MODIFY] app/Models/OrderItem.php
- Menambahkan `cogs` ke dalam *array* `$fillable` agar bisa disimpan secara massal/langsung.

### 3. Business Logic (COGS Snapshot)

#### [MODIFY] app/Services/OrderService.php
- **`deductStock()`**: Saya akan memodifikasi metode ini agar tidak hanya memotong stok, tetapi juga mengembalikan/menyimpan nilai COGS.
  - Untuk Produk (ATK): COGS akan diakumulasi di dalam iterasi FIFO (`$quantityToDeduct * $batch->purchase_price`). Setelah iterasi *batch* selesai untuk item tersebut, nilai COGS disimpan permanen ke `$item->update(['cogs' => $totalCogs])`.
  - Untuk Jasa (Service): Sistem akan otomatis menyetel `cogs` = 0 (karena modul layanan saat ini tidak memiliki field modal/HPP khusus).
- **Data Immutability Guarantee**: Saya akan menambahkan blok *inline comment* berstandar industri yang menegaskan bahwa *field* `cogs` ini terkunci secara logika bisnis setelah tercatat.

## Verification Plan

1. **Jalankan Migrasi**: `php artisan migrate`.
2. **Pengujian Kasir**: Membuat pesanan baru, memprosesnya hingga `completed`.
3. **Pengecekan Database**: Memverifikasi tabel `order_items` bahwa kolom `cogs` telah terisi dengan angka HPP yang tepat sesuai dengan *batch* yang terpotong, dan untuk Jasa bernilai 0.
