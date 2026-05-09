# Rencana Implementasi: Edit Batch & Validasi Anti-Minus

Fitur ini akan memungkinkan admin untuk mengoreksi kesalahan *input* pada Batch (Harga Beli & Stok Awal) dengan pengamanan ketat agar tidak terjadi *negative stock* (stok minus) pada sistem.

## User Review Required

> [!WARNING]
> **Penyelarasan Nama Kolom (Field)**
> Di dalam instruksi, Anda menyebutkan field `stock_added`. Berdasarkan skema tabel `batches` yang ada, nama kolom yang menyimpan stok awal masuk adalah **`initial_stock`**. Saya akan menggunakan `initial_stock` di dalam kode untuk memastikan kompatibilitas dengan database tanpa perlu migrasi ulang. Apakah Anda setuju?

## Proposed Changes

### 1. Logika Pembaruan (Service Layer)

#### [MODIFY] app/Services/BatchService.php
Memperbarui metode `updateBatch($id, array $data)` untuk memasukkan *Constraint Anti-Minus*:
- Mengunci data *ProductBrand* dengan `lockForUpdate()`.
- **Rumus:** `$soldQty = $batch->initial_stock - $batch->current_stock;`
- **Validasi:** Jika input `$data['initial_stock'] < $soldQty`, sistem akan melempar `Exception` ("Stok awal tidak bisa diubah lebih rendah dari jumlah barang yang sudah terjual").
- **Kalkulasi Ulang:** Jika lolos, `$batch->current_stock` akan disetel menjadi `$data['initial_stock'] - $soldQty`. `purchase_price` juga diperbarui.
- *Total Stock* dan *WAC* otomatis dihitung ulang melalui pemanggilan fungsi yang sudah ada di bawahnya.

### 2. Validasi Request

#### [NEW] app/Http/Requests/UpdateBatchRequest.php
Membuat form request untuk validasi input:
- `initial_stock`: required, integer, minimal 1.
- `purchase_price`: required, numeric, minimal 0.

### 3. Kontroler & Rute

#### [MODIFY] app/Http/Controllers/Web/BatchController.php
- Menambahkan metode `edit(string $id)` untuk mengembalikan *view* form edit.
- Menambahkan metode `update(UpdateBatchRequest $request, string $id)` untuk memproses pembaruan melalui `BatchService`.

#### [MODIFY] routes/web.php
Menambahkan 2 rute baru di bawah grup `dashboard/batches`:
- `GET /batches/{id}/edit` -> `edit`
- `PUT /batches/{id}` -> `update`

### 4. Antarmuka UI (Frontend)

#### [NEW] resources/views/dashboard/batches/edit.blade.php
Membuat halaman Edit (*Glassmorphism* style):
- Form khusus untuk mengubah `initial_stock` dan `purchase_price`.
- **CRITICAL UI:** Menampilkan kotak informasi _Readonly_ berisi:
  - Jumlah Stok Awal (Lama)
  - Jumlah Terjual (`$soldQty`)
  - Sisa Stok Saat Ini
- Form akan memberikan peringatan visual jika user mengetik angka di bawah `$soldQty`.

## Verification Plan

1. **Uji Validasi:** Memasukkan nilai `initial_stock` yang lebih kecil dari barang yang sudah terjual -> Harus gagal dengan pesan *error*.
2. **Uji Sukses:** Memasukkan nilai yang valid -> Harus berhasil menyimpan, dan nilai WAC / *Current Stock* pada Product Brand harus otomatis menyesuaikan.
