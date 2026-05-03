# Modul: Laporan Stok Gudang

**Penanggung Jawab:** Firman (Order Management & Product Catalog)

## Deskripsi
Modul pemantauan stok yang dirancang agar *owner* atau pengelola operasional dapat segera melakukan belanja persediaan (*restock*) jika suatu produk hampir habis.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Stok menipis alert** | Menampilkan notifikasi atau daftar produk yang stok berjalannya (*current_stock*) jatuh di bawah ambang batas minimal. | `product_brands` |
| **Rekap stok per produk/batch** | Menyediakan tampilan rekam jejak arus stok masuk terhadap sisa barang secara mendetail per entri batch pembelian. | `product_brands` |
