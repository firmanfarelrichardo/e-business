# Modul: Manajemen Produk (ATK)

**Penanggung Jawab:** Firman (Order Management & Product Catalog)  
**Fokus Utama:** Proses bisnis inti terkait manajemen dasar produk toko.

## Deskripsi
Modul ini digunakan untuk mengelola data produk master seperti Alat Tulis Kantor (ATK).

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **CRUD Produk** | Menambahkan produk baru, mengubah informasi produk, atau melakukan *soft delete* agar histori data tidak hilang. | `products` |
| **Upload gambar produk** | Mengunggah dan melampirkan *multiple* foto produk ke dalam format JSON. | `products.attachments` |
