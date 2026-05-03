# Modul: Manajemen Order (Item)

**Penanggung Jawab:** Firman (Order Management & Product Catalog)

## Deskripsi
Modul operasional utama yang mengontrol keranjang belanja pelanggan, memasukkan item produk atau jasa ke dalam pesanan, dan sistem penomoran antrean.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Buat order baru** | Pembuatan sesi pesanan baru oleh *customer* maupun via kasir. | `orders` |
| **Tambah item ke order** | Memilih dan memasukkan produk spesifik (berbasis merk/brand) atau jasa ke dalam satu nomor antrean pesanan. | `order_items` |
| **Generate nomor antrian** | Membuat nomor *queue* otomatis yang akan meningkat nilainya (*increment*). | `orders.queue_number` |
| **Proses order** | Mengubah status berjalannya antrean pesanan (*pending* → *process* → *finish*). | `orders.status` |
