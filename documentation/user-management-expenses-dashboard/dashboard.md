# Modul: Dashboard

**Penanggung Jawab:** Alyaa (User Management, Expenses, Dashboard & Frontend)

## Deskripsi
Halaman muka beranda (admin/owner) yang merangkum *snapshot* bisnis hari ini, memberikan informasi kilat agar operasional lebih responsif.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Antrian hari ini** | Daftar jumlah dan status pesanan yang masuk dan sedang diproses pada hari berjalan. | `orders` |
| **Pendapatan hari ini** | Realisasi kas atau uang yang masuk khusus pada hari berjalan. | `transactions` |
| **Grafik penjualan (7/30 hari)** | Visualisasi *chart* pergerakan penjualan selama kurun waktu mingguan atau bulanan. | `transactions`, `orders` |
