# Rencana Implementasi: Fitur Keranjang Belanja & Checkout

Fitur ini akan menambahkan fungsionalitas keranjang belanja (Shopping Cart) untuk pelanggan (Customer) yang terintegrasi dengan modul pemesanan (Order) yang sudah ada, dengan tetap mempertahankan Arsitektur 4-Layer dan pengamanan concurrency.

## User Review Required

> [!IMPORTANT]
> **Struktur Relasi Item Campuran (Produk & Jasa)**
> Anda meminta saran antara *Polymorphic* atau *Nullable Foreign Keys* untuk keranjang campuran. Saya mengusulkan **Nullable Foreign Keys** (`product_brand_id` dan `service_id`). 
> **Alasan**: Tabel `order_items` saat ini sudah menggunakan pendekatan *nullable foreign keys*. Dengan menyamakan struktur `cart_items` dengan `order_items`, proses *checkout* (memindahkan data dari keranjang ke pesanan) akan menjadi sangat efisien dan konsisten secara arsitektur (1:1 mapping). Referential integrity di database juga lebih kuat dibanding polymorphic. Mohon konfirmasi apakah Anda setuju dengan pendekatan ini.

> [!NOTE]
> **Pengecekan Stok**
> Anda menyebutkan mengecek `total_stock` di tabel `products`. Namun, berdasarkan arsitektur yang sudah berjalan (sistem Batch/WAC), stok dikelola di level varian (`ProductBrand`). Oleh karena itu, validasi stok *real-time* akan mengecek ketersediaan `current_stock` pada `ProductBrand` yang dipilih.

## Proposed Changes

### 1. Database & Models
Pembuatan struktur data untuk keranjang belanja.

#### [NEW] database/migrations/..._create_carts_table.php
Tabel `carts`:
- `id` (UUID, primary)
- `user_id` (UUID, foreign to users, cascade delete)
- `timestamps()`

#### [NEW] database/migrations/..._create_cart_items_table.php
Tabel `cart_items`:
- `id` (UUID, primary)
- `cart_id` (UUID, foreign to carts, cascade delete)
- `product_brand_id` (UUID, nullable, foreign to product_brands)
- `service_id` (UUID, nullable, foreign to services)
- `quantity` (integer)
- `note` (text, nullable)

#### [NEW] app/Models/Cart.php & app/Models/CartItem.php
Model Eloquent dengan relasi `belongsTo` dan `hasMany` yang sesuai.

---

### 2. Repository Layer
Pengelolaan query database terisolasi.

#### [NEW] app/Repositories/CartRepository.php
- `findByUserId(string $userId)`
- `firstOrCreate(string $userId)`
- `clearCart(string $cartId)`

#### [NEW] app/Repositories/CartItemRepository.php
- `addOrUpdateItem(string $cartId, array $data)`
- `updateQuantity(string $itemId, int $quantity)`
- `deleteItem(string $itemId)`

---

### 3. Service Layer
Logika bisnis dan validasi stok.

#### [NEW] app/Services/CartService.php
- `getCart(string $userId)`
- `addToCart(string $userId, array $data)`: Melakukan validasi `ProductBrand->current_stock >= requested_quantity`.
- `updateItemQuantity(string $itemId, int $quantity)`: Validasi stok ulang jika quantity bertambah.
- `removeItem(string $itemId)`

#### [MODIFY] app/Services/OrderService.php
Menambahkan method `checkoutCart(User $user)`:
1. Membaca isi keranjang milik User.
2. Memvalidasi ketersediaan stok terakhir (real-time) sebelum order dibuat.
3. Membentuk array `$data['items']` dari `cart_items`.
4. Memanggil method `createOrder($data)` yang sudah ada (secara otomatis mewarisi perlindungan `DB::beginTransaction()` dan *thread-safe queue numbering*).
5. Memanggil `CartRepository->clearCart()` setelah pesanan berhasil terbuat.

---

### 4. Controller & Routing
Endpoint untuk antarmuka pengguna.

#### [NEW] app/Http/Controllers/Front/CartController.php
Menangani request dari UI Keranjang (menampilkan index, tambah item, update qty, hapus item, dan proses checkout).

#### [MODIFY] routes/web.php
Mendaftarkan rute keranjang (dengan middleware auth):
- `GET /keranjang`
- `POST /keranjang/add`
- `PUT /keranjang/update/{id}`
- `DELETE /keranjang/remove/{id}`
- `POST /keranjang/checkout`

---

### 5. Frontend UI
Antarmuka pelanggan dengan tema Glassmorphism.

#### [NEW] resources/views/keranjang/index.blade.php
Halaman keranjang yang interaktif:
- List item keranjang (ATK & Jasa) menggunakan `.glass-card`.
- Form update quantity (+ / -) yang melakukan submit otomatis (atau via Alpine/JS simpel).
- Ringkasan total harga.
- Tombol Checkout yang memicu proses pesanan.

## Verification Plan

### Automated Tests
- Menjalankan `php artisan migrate`.
- Melakukan verifikasi rute via `php artisan route:list`.

### Manual Verification
- Login sebagai pelanggan (owner/user).
- Menambah produk ATK dan Jasa Fotocopy ke keranjang (Campuran).
- Memastikan peringatan muncul jika mencoba menambah jumlah melebihi stok di `ProductBrand`.
- Menekan tombol Checkout, memastikan Order baru terbentuk dengan status `pending` dan item keranjang terhapus bersih.
