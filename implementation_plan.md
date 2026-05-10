# Rencana Implementasi: Master Database Seeder (Real-World Simulation)

Fitur ini akan menghasilkan *Mock Data* berukuran besar yang sangat realistis untuk menguji ketahanan antarmuka dasbor, akurasi pelaporan HPP (COGS), dan algoritma *Exact FIFO* pada lingkungan yang menyerupai *Production*.

## User Review Required

> [!WARNING]
> **Penataan Ulang File Seeder Lama**
> Pada struktur saat ini, sistem Anda memiliki `CategorySeeder`, `BrandSeeder`, dan `ProductSeeder` yang terpisah. Untuk menyederhanakan *call sequence* dan memastikan relasi produk benar-benar masuk akal di dunia nyata (Kertas HVS hanya untuk merk Sinar Dunia/PaperOne), saya mengusulkan untuk menggabungkan seeder katalog ini ke dalam satu **`CatalogSeeder.php`** terpusat. Seeder lama tidak akan dipanggil lagi di `DatabaseSeeder`. Apakah Anda setuju dengan pemadatan ini?

## Proposed Changes

### 1. Seeder Orchestrator

#### [MODIFY] database/seeders/DatabaseSeeder.php
Mengatur urutan pemanggilan (*Call Sequence*) yang sangat ketat untuk mencegah *Foreign Key Constraint Error*:
1. `UserSeeder` (Tabel terluar)
2. `CatalogSeeder` (Bergantung pada User untuk `created_by`, mengisi Kategori, Brand, Produk, ProductBrand/Varian, dan Layanan Jasa)
3. `BatchSeeder` (Bergantung pada ProductBrand, mengisi riwayat stok masuk & WAC awal)
4. `OrderSeeder` (Bergantung pada User, Employee, Batch, dan ProductBrand untuk membuat transaksi historis)

### 2. Individual Seeders (The Data Generators)

#### [NEW] database/seeders/UserSeeder.php
- **Static Data**: 1 Admin (owner), 2 Kasir (employee) dengan *password* statis agar Anda mudah melakukan *login testing*.
- **Faker Data**: `for` *loop* menghasilkan 50+ *users* berstatus `member`/`customer` dengan nama khas Indonesia.

#### [NEW] database/seeders/CatalogSeeder.php
- Menggunakan *Array List* manual.
- **Kategori**: Kertas, Alat Tulis, Peralatan Kantor, Tinta/Toner, dll.
- **Brand**: Sinar Dunia, Joyko, Kenko, dll.
- **Products & ProductBrands**: Akan dibuat minimal 50 kombinasi rasional (contoh: "Kertas HVS A4 70gsm Sinar Dunia").
- **Services**: Fotocopy (A4, F4, Warna), Jilid, Laminating.

#### [NEW] database/seeders/BatchSeeder.php
- **CRITICAL**: Mengiterasi setiap `ProductBrand`.
- Untuk setiap produk, sistem akan melahirkan 3-5 *Batch* masuk dalam rentang tanggal acak 6 bulan terakhir.
- Harga beli (`purchase_price`) berfluktuasi secara *random* ±10% dari harga dasar untuk mensimulasikan inflasi harga pemasok dunia nyata.
- Kondisi stok akan direkayasa: Beberapa batch dibuat habis (0), lainnya sebagian atau utuh.
- Memanggil `$productBrand->recalculateWAC()` di akhir iterasi produk untuk menyinkronkan data harga.

#### [NEW] database/seeders/OrderSeeder.php
- Menghasilkan 500+ pesanan *Completed* dalam 6 bulan terakhir.
- 10 pesanan *Pending* dan 5 pesanan *Processing* dengan `created_at` hari ini khusus untuk mengetes UI Dasbor Antrean Kasir Anda.
- **Simulasi FIFO & COGS**: Di dalam pembuatan `order_items`, seeder akan merangkai `cogs` yang rasional berdasarkan *purchase_price* rata-rata atau acak mendekati WAC, sehingga laporan laba Anda langsung terisi angka riil.

## Verification Plan
1. **Refresh Database**: Saya akan memberikan perintah `php artisan migrate:fresh --seed` untuk menghapus dan membangun ulang seluruh struktur beserta datanya.
2. **Uji Login**: Memastikan Admin dapat login.
3. **Uji Dasbor Antrean**: Memeriksa halaman antrean apakah 15 pesanan hari ini langsung muncul di kolom *Menunggu* dan *Diproses*.
4. **Uji COGS**: Memeriksa `order_items` di DB untuk memastikan kolom `cogs` telah terisi dengan benar.
