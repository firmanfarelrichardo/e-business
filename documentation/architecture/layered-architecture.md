# Layered Architecture dalam Sistem E-Business

Sistem E-Business ini dirancang menggunakan konsep **Layered Architecture** (Arsitektur Berlapis) untuk menjamin kode yang bersih (*clean code*), *scalable*, dan mudah dipelihara (*maintainable*). 

## Struktur Lapisan (Layers)

Konsep ini memisahkan logika aplikasi ke dalam 4 lapisan (layer) utama yang saling terhubung secara terstruktur:

### 1. Model Layer (Data Structure)
Berada di dalam direktori `app/Models/`.
- **Fungsi:** Representasi tabel di database. Mengatur relasi (ORM), format *casting* data, dan pengaturan tipe data yang dikirim (*fillables*).
- **Contoh:** `Product.php`, `Brand.php`.
- **Keterangan:** Murni berisi struktur relasional dan definisi *behavior* data dasar.

### 2. Repository Layer (Data Access)
Berada di dalam direktori `app/Repositories/`.
- **Fungsi:** Mengambil data, menyimpan, memperbarui, atau menghapus data ke database menggunakan *Eloquent ORM*. 
- **Tujuan:** *Repository Pattern* mencegah *Controller* dan *Service* untuk melakukan *query* SQL/Eloquent secara langsung. Jika di masa depan terjadi pergantian database atau struktur, cukup perbarui bagian *Repository* tanpa merusak logika aplikasi.
- **Contoh:** `ProductRepository.php` memiliki fungsi `findById()` atau `create()`.

### 3. Service Layer (Business Logic)
Berada di dalam direktori `app/Services/`.
- **Fungsi:** Jantung utama logika bisnis. Mengatur *flow* (alur kerja) dari sebuah fitur, misal kalkulasi harga diskon, pengunggahan file gambar ke penyimpanan publik, dan lain sebagainya.
- **Tujuan:** Menjaga *Controller* agar tetap "kurus" (*thin controller*). *Service* memanggil *Repository* untuk mendapatkan atau memanipulasi data yang telah selesai diolah oleh logika bisnisnya.
- **Contoh:** `ProductService.php` akan mengunggah gambar ke sistem *storage* lokal terlebih dahulu, lalu hasil lokasinya diserahkan kepada `ProductRepository` untuk disimpan ke database.

### 4. Controller Layer (HTTP Interface)
Berada di dalam direktori `app/Http/Controllers/Api/`.
- **Fungsi:** Gerbang masuk (*entry point*) untuk menerima permintaan (Request) dari pengguna (melalui *Routes* API).
- **Tujuan:** Menangani validasi menggunakan `FormRequests` (`StoreProductRequest`), meneruskan request yang valid ke *Service Layer*, lalu mengembalikan *response* dalam format standar (misal JSON standar RESTful).
- **Contoh:** `ProductController.php` tidak punya logika kompleks, hanya meneruskan data ke `ProductService` dan mengeluarkan `$response->json()`.

---

## Alur Data (Data Flow)

Saat *client* (Mobile/Frontend) mengirimkan request `POST /api/products`:
1. **Routing:** `routes/api.php` mengarahkan ke `ProductController@store`.
2. **Controller (Validasi):** `StoreProductRequest` memvalidasi input (memastikan ukuran gambar pas, field `name` terisi). Jika valid, controller memanggil `ProductService`.
3. **Service (Logika):** `ProductService` menyimpan array gambar ke `storage/app/public/products`, kemudian meminta `ProductRepository` untuk menyimpan *path* gambar berserta detail produk.
4. **Repository (Query):** `ProductRepository` menjalankan perintah `Product::create()`.
5. **Kembali:** *Controller* menerima hasilnya dari *Service* dan mengembalikan status HTTP 201 (Created) ke *Client*.

> [!TIP]
> Dengan mengikuti pola struktur ini, jika tim bertambah besar, setiap modifikasi akan terisolasi di layernya masing-masing, sangat meminimalisir risiko "merusak" fitur yang lain.
