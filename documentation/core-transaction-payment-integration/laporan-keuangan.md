# Modul: Laporan Keuangan

**Penanggung Jawab:** Elthon (Core Transaction & Payment Integration)  
**Fokus Utama:** Kalkulasi performa finansial bisnis.

## Deskripsi
Modul ini menyajikan ringkasan dan detail laporan keuangan untuk membantu *owner* dalam memantau laba kotor, perbandingan pengeluaran dan pendapatan, serta metode pembayaran terlaris.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Rekap pendapatan harian/bulanan** | Menampilkan total laporan jumlah pesanan sukses dan pendapatan berdasarkan filter hari atau bulan. | `transactions`, `orders` |
| **Rekap per metode pembayaran** | Laporan sebaran penggunaan metode pembayaran (misal: porsi transaksi via QRIS vs Bank Transfer). | `transactions` |
| **Rekap pengeluaran vs pendapatan** | Menampilkan laba kotor dari perhitungan: `Total Penjualan - Harga Pokok Penjualan (HPP)` (pendapatan bersih). | `expenses`, `transactions` |
