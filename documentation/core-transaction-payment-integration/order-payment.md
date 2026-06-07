# Modul: Order (Payment Section)

**Penanggung Jawab:** Elthon (Core Transaction & Payment Integration)  
**Fokus Utama:** Manajemen order dari sisi validasi pembayaran dan kalkulasi akhir.

## Deskripsi
Modul ini berfokus pada kalkulasi biaya dari sebuah pesanan (order) serta memperbarui status dari pesanan tersebut sesuai dengan keberhasilan pembayaran.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Update status order setelah bayar** | Memperbarui status pesanan menjadi tahap diproses (*processing*) segera setelah transaksi dinyatakan lunas. | `orders` |
| **Total harga & subtotal** | Kalkulasi akhir dari kumpulan total harga per item (`subtotal_price`) menjadi `total_price` secara keseluruhan. | `orders`, `order_items` |
