# Modul: Laporan Lainnya

**Penanggung Jawab:** Alyaa (User Management, Expenses, Dashboard & Frontend)

## Deskripsi
Fitur pencetakan laporan untuk analisis strategi pemasaran (marketing) dan analisis segmentasi produk mana yang paling disukai pasar.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Laporan order per customer** | Menyajikan data aktivitas dan intensitas pelanggan dalam bertransaksi (sebagai bahan *loyalty program*). | `orders`, `users` |
| **Laporan produk terlaris** | Menampilkan grafik atau ranking barang fisik yang sering terjual. | `order_items`, `product_brands` |
| **Laporan jasa terlaris** | Menampilkan ranking layanan fotokopi/jilid yang paling sering di-*request* konsumen. | `order_items`, `services` |
