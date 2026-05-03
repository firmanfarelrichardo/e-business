# Modul: Manajemen Pengeluaran Operasional

**Penanggung Jawab:** Alyaa (User Management, Expenses, Dashboard & Frontend)

## Deskripsi
Modul untuk mendata semua biaya keluar yang menjadi tanggungan perusahaan, baik yang berkaitan dengan operasional (*listrik, gaji*) maupun pembelanjaan stok barang ulang.

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **CRUD Pengeluaran Operasional** | Mencatat tagihan dan arus kas keluar harian secara mendetail. | `expenses` |
| **Tambah item pengeluaran produk** | Jika jenis pengeluaran adalah pembelanjaan, fitur ini memecah apa saja barang yang dibeli beserta rinciannya. | `expense_items` |
| **Hitung total pengeluaran** | Kalkulasi akumulatif dari semua rincian item atau tagihan pokok. | `expenses.total_amount` |
