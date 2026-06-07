# Modul: Manajemen Batch & Stok

**Penanggung Jawab:** Firman (Order Management & Product Catalog)

## Deskripsi
Modul ini bertugas mengatur arus inventori (stok masuk dan stok keluar) berbasis sistem *"Batch"*. Hal ini memungkinkan pelacakan modal pembelian dan sisa stok dari setiap restock.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **CRUD Batch (Pembelian Stok)** | Setiap kali melakukan belanja persediaan (restock), data batch baru akan dibuat untuk mencatat harga modal dan total barang masuk. | `batches` |
| **Set harga per batch** | Menentukan harga jual untuk suatu produk dengan *brand* spesifik berdasarkan satu unit satuan jual. | `product_brands` |
| **Update stok masuk/keluar** | Stok akan otomatis bertambah melalui entry batch dan otomatis berkurang setelah proses `orders` selesai. | `product_brands` |
