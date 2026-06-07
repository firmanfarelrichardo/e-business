# Panduan Pengujian API (API Testing)

Dokumentasi ini memberikan panduan tentang cara melakukan *testing* pada titik akses (*endpoints*) RESTful API yang sudah selesai dibangun untuk modul **Manajemen Produk, Brand, Jasa, dan Kategori Produk**.

> [!NOTE]
> *Base URL* untuk aplikasi lokal di Docker adalah: `http://localhost:8000` (Sesuai request, prefix `/api` telah dihapus dengan memindahkan routing ke jalur utama web).

## Daftar Endpoints

Semua entitas dilayani dengan format RESTful standar (`GET`, `POST`, `PUT/PATCH`, `DELETE`).

### 1. Product Categories (Kategori Produk)
Endpoint untuk mengelola daftar kategori dari setiap produk.

- **[GET] `/categories`**: Menarik semua daftar kategori.
- **[POST] `/categories`**: Membuat kategori baru.
  - Body (JSON): `{"name": "Alat Tulis"}`
- **[GET] `/categories/{id}`**: Melihat detail 1 kategori.
- **[PUT] `/categories/{id}`**: Mengupdate data kategori.
- **[DELETE] `/categories/{id}`**: Menghapus data kategori.

### 2. Brands (Manajemen Merk)
Endpoint untuk mengelola pabrikan atau merk barang.

- **[GET] `/brands`**: Menarik semua daftar brand.
- **[POST] `/brands`**: Membuat brand baru.
  - Body (JSON): `{"name": "Joyko", "description": "Merk unggulan ATK"}`
- **[GET] `/brands/{id}`**: Melihat detail 1 brand.
- **[PUT] `/brands/{id}`**: Mengupdate data brand.
- **[DELETE] `/brands/{id}`**: Menghapus data brand.

### 3. Products (Manajemen Produk Fisik)
Endpoint utama untuk manajemen stok produk beserta kemampuannya menampung file gambar.

- **[GET] `/products`**: Menarik semua produk (sudah di-*load* beserta detail relasi kategorinya).
- **[POST] `/products`**: Membuat produk baru. **Perhatian:** karena mengirimkan file (gambar), pastikan di aplikasi Postman/Insomnia Anda mengatur mode *Body* ke **`multipart/form-data`**.
  - Form-Data Keys:
    - `name` (Text): "Buku Tulis Sinar Dunia"
    - `description` (Text): "Isi 58 Lembar"
    - `category_id` (Text): *Masukkan UUID dari response endpoint kategori*
    - `images[]` (File): Pilih satu gambar (atau bisa menduplikasi key `images[]` jika lebih dari satu gambar).
- **[GET] `/products/{id}`**: Melihat detail 1 produk.
- **[PUT] `/products/{id}`**: Mengupdate data produk. *Catatan untuk file upload:* Beberapa API client mensyaratkan untuk mengirim method pengganti `_method=PUT` saat melakukan upload file untuk mem-*bypass* batasan HTTP.
- **[DELETE] `/products/{id}`**: Menghapus (Soft-Delete) data produk.

### 4. Services (Manajemen Jasa)
Endpoint untuk pembuatan manajemen jasa seperti layanan print atau jilid.

- **[GET] `/services`**: Menarik daftar layanan.
- **[POST] `/services`**: Membuat layanan. Gunakan **`multipart/form-data`**.
  - Form-Data Keys:
    - `name` (Text): "Print Warna A4"
    - `piece_price` (Number): 1500
    - `images[]` (File): Pilih contoh hasil akhir (seperti preview print warna).
- **[GET] `/services/{id}`**: Detail jasa.
- **[PUT] `/services/{id}`**: Update data jasa.
- **[DELETE] `/services/{id}`**: Menghapus data jasa.

---

## Cara Mengetes dengan Postman (Testing via GUI)

1. Buka aplikasi **Postman**.
2. Klik **New > HTTP Request**.
3. Pilih metode `GET` atau `POST` sesuai kebutuhan endpoint.
4. Masukkan URL, misal: `http://localhost:8000/products`
5. Untuk request tipe `POST` yang melibatkan gambar (seperti Produk dan Jasa), konfigurasikan tab **Body**:
   - Pilih radio button **`form-data`**.
   - Tambahkan *Key* dan *Value* sesuai parameter.
   - Pada kolom *Key* bernama `images[]`, arahkan *hover* mouse Anda ke kanan kolom teks "images[]" lalu ganti tipe dari `Text` menjadi `File`.
   - Klik tombol **Select Files** di kolom *Value* dan pilih gambar dari komputer Anda.

**Contoh Payload Produk di Postman (Body -> form-data):**
| Key | Type | Value |
|---|---|---|
| `name` | Text | Buku Tulis Sinar Dunia |
| `description` | Text | Isi 58 Lembar |
| `category_id` | Text | *[Paste UUID Kategori dari hasil GET /categories]* |
| `images[]` | File | *[Pilih File Gambar]* |

6. Klik tombol **Send**.
7. Pastikan Anda mendapatkan respon `201 Created` di pojok kanan bawah beserta objek JSON yang baru saja di-*insert* ke database.

---

## Cara Mengetes dengan cURL (Terminal)

Contoh eksekusi POST untuk membuat **Kategori Produk**:

```bash
curl --location 'http://localhost:8000/categories' \
--header 'Content-Type: application/json' \
--header 'Accept: application/json' \
--data '{
    "name": "Alat Tulis"
}'
```

Contoh eksekusi POST **Produk Baru** dengan *Image Attachment* (Asumsi gambar berada di direktori terminal Anda bernama `test.png`):

```bash
curl --location 'http://localhost:8000/products' \
--header 'Accept: application/json' \
--form 'name="Spidol Papan Tulis"' \
--form 'category_id="[GANTI_DENGAN_UUID_KATEGORI_VALID]"' \
--form 'images[]=@"test.png"'
```
