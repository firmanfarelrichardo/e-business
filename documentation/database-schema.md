# E-Business Database Schema Documentation

This document outlines the database schema for the E-Business application. The schema uses `UUID` as the primary key for all tables.

## Entity Relationship Diagram

```mermaid
erDiagram
    users {
        uuid id PK
        varchar name
        varchar username
        varchar password
        varchar email
        varchar address
        enum role "member, employee, owner"
        text profile
        boolean is_active
        varchar remember_token
        timestamp email_verified_at
        timestamp last_login_at
        uuid created_by FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    brands {
        uuid id PK
        varchar name
        text description
        timestamp created_at
        timestamp updated_at
    }

    product_categories {
        uuid id PK
        varchar name
        timestamp created_at
        timestamp updated_at
    }

    products {
        uuid id PK
        varchar name
        json attachments
        varchar description
        uuid category_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    product_brands {
        uuid id PK
        varchar unit
        decimal selling_price
        uuid product_id FK
        uuid brand_id FK
        timestamp created_at
    }

    batches {
        uuid id PK
        varchar batch_code UK
        integer current_stock
        integer initial_stock
        decimal purchase_price
        boolean is_active
        uuid product_brand_id FK
        uuid created_by FK
        timestamp created_at
    }

    orders {
        uuid id PK
        varchar order_number UK
        integer queue_number
        enum status "pending, processing, completed, cancelled"
        uuid user_id FK
        uuid employee_id FK
        text note
        decimal total_price
        timestamp paid_at
        timestamp created_at
        timestamp completed_at
    }

    order_items {
        uuid id PK
        decimal subtotal_price
        integer quantity
        decimal price_per_unit
        text note
        uuid order_id FK
        uuid product_brand_id FK "nullable"
        uuid service_id FK "nullable"
    }

    services {
        uuid id PK
        varchar name
        json attachments
        text description
        decimal piece_price
        uuid created_by FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    expenses {
        uuid id PK
        decimal total_amount
        text note
        uuid batch_id FK
        uuid created_by FK
        timestamp created_at
    }

    expense_items {
        uuid id PK
        uuid expense_id FK
        uuid product_brand_id FK
        integer quantity
        decimal purchase_price
        decimal subtotal
        timestamp created_at
    }

    payment_methods {
        uuid id PK
        varchar name
        varchar code
        boolean is_active
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    transactions {
        uuid id PK
        uuid order_id FK
        varchar transaction_code UK
        enum transaction_status "failed, pending, success, expired"
        json gateway_response
        uuid payment_type_id FK
        timestamp created_at
        timestamp paid_at
    }

    %% Relationships
    users ||--o{ users : "created_by"
    product_categories ||--o{ products : "has"
    products ||--o{ product_brands : "has"
    brands ||--o{ product_brands : "has"
    product_brands ||--o{ batches : "has"
    users ||--o{ batches : "created_by"
    users ||--o{ orders : "user_id"
    users ||--o{ orders : "employee_id"
    orders ||--o{ order_items : "has"
    product_brands ||--o{ order_items : "in"
    services ||--o{ order_items : "in"
    users ||--o{ services : "created_by"
    batches ||--o{ expenses : "has"
    users ||--o{ expenses : "created_by"
    expenses ||--o{ expense_items : "has"
    product_brands ||--o{ expense_items : "in"
    orders ||--|| transactions : "has"
    payment_methods ||--o{ transactions : "uses"

```

## Tables Details

### 1. `users`
Tabel untuk menyimpan data pengguna aplikasi.
- **id**: (UUID, PK) Identifier unik
- **name, username, password, email, address, profile**: Data diri.
- **role**: Enum ('member', 'employee', 'owner')
- **is_active**: Boolean untuk flag status aktif atau tidak.
- **created_by**: (UUID, FK) Referensi ke user lain (seperti admin) yang membuat user ini.
- Dilengkapi dengan *Soft Deletes* (`deleted_at`).

### 2. `brands`
Tabel untuk menyimpan master data brand/merk dari suatu produk.
- **id**: (UUID, PK)
- **name, description**: Info brand.

### 3. `product_categories`
Tabel untuk menyimpan data kategori dari produk.
- **id**: (UUID, PK)
- **name**: Nama Kategori.

### 4. `products`
Tabel master data produk yang merujuk pada kategori tertentu.
- **id**: (UUID, PK)
- **category_id**: (UUID, FK) Referensi ke `product_categories.id`.
- **attachments**: Format JSON untuk menyimpan data array gambar atau file produk.
- Dilengkapi *Soft Deletes*.

### 5. `product_brands`
Tabel pivot/relasi yang mengaitkan `products` dengan `brands`, serta menyimpan informasi spesifik harga dan satuan jual per merk.
- **id**: (UUID, PK)
- **product_id**: (UUID, FK)
- **brand_id**: (UUID, FK)
- **unit**: String untuk satuan produk (contoh: Pcs, Kg).
- **selling_price**: Harga jual (`decimal`).

### 6. `batches`
Tabel untuk mengelola stok/batch (penerimaan barang).
- **id**: (UUID, PK)
- **batch_code**: Kode unik batch.
- **current_stock, initial_stock**: Stok yang masuk dan sisa.
- **purchase_price**: Harga modal/beli.
- **product_brand_id**: (UUID, FK) Produk merk apa yang masuk dalam batch ini.
- **created_by**: (UUID, FK) User/Employee yang mengurus batch ini.

### 7. `orders`
Tabel pesanan.
- **id**: (UUID, PK)
- **status**: Enum ('pending', 'processing', 'completed', 'cancelled').
- **user_id**: (UUID, FK) Pelanggan.
- **employee_id**: (UUID, FK) Pegawai yang melayani pesanan.

### 8. `services`
Tabel untuk menyimpan data jasa (selain produk fisik).
- **id**: (UUID, PK)
- **name, description, piece_price**: Informasi jasa.
- **attachments**: Format JSON.
- **created_by**: (UUID, FK) User yang membuat data layanan.

### 9. `order_items`
Tabel detil pesanan (isi keranjang). Dapat berisi produk atau jasa.
- **id**: (UUID, PK)
- **order_id**: (UUID, FK)
- **product_brand_id**: (UUID, FK, Nullable) Terisi jika item merupakan produk.
- **service_id**: (UUID, FK, Nullable) Terisi jika item merupakan jasa.

### 10. `expenses`
Tabel pengeluaran operasional.
- **id**: (UUID, PK)
- **batch_id**: (UUID, FK) Mengaitkan pengeluaran terhadap batch tertentu (opsional/jika pengeluaran terkait pembelian batch).
- **created_by**: (UUID, FK)

### 11. `expense_items`
Tabel detil dari `expenses`.
- **id**: (UUID, PK)
- **expense_id**: (UUID, FK)
- **product_brand_id**: (UUID, FK) Barang yang dibeli dalam pengeluaran.

### 12. `payment_methods`
Tabel master data metode pembayaran.
- **id**: (UUID, PK)
- **name, code**: Detail metode pembayaran.
- **is_active**: Status bisa digunakan atau tidak.

### 13. `transactions`
Tabel transaksi pembayaran dari suatu Order.
- **id**: (UUID, PK)
- **order_id**: (UUID, FK, Unique) Relasi One-to-One dengan pesanan.
- **payment_type_id**: (UUID, FK) Mengacu ke tabel `payment_methods`.
- **transaction_status**: Enum ('failed', 'pending', 'success', 'expired').
- **gateway_response**: (JSON) Response raw dari payment gateway jika menggunakan sistem eksternal.

