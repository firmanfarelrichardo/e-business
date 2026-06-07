# Modul: Transaksi Pembayaran

**Penanggung Jawab:** Elthon (Core Transaction & Payment Integration)  
**Fokus Utama:** Semua yang berhubungan dengan uang, pembayaran, dan transaksi.

## Deskripsi
Modul ini menangani seluruh alur pembayaran dan transaksi pada sistem, termasuk proses integrasi secara penuh dengan *Payment Gateway* (Midtrans).

## Daftar Fitur & Tabel Terkait

| Fitur | Deskripsi | Tabel Terkait |
| --- | --- | --- |
| **Init payment ke Midtrans** | Menginisiasi transaksi pembayaran (pembuatan transaksi) ke Midtrans. | `transactions` |
| **Generate QRIS / Virtual Account** | Menghasilkan kode QR atau nomor Virtual Account via Midtrans untuk pembayaran pelanggan. | `transactions` |
| **Webhook handler Midtrans** | Endpoint yang menerima sinyal (*callback*) dari Midtrans terkait perubahan status pembayaran. | `transactions` |
| **Update status pembayaran** | Memperbarui status transaksi (*pending*, *success*, *failed*, *expired*) pada sistem lokal. | `transactions` |
| **Cancel / Expire / Refund** | Memproses pembatalan transaksi, transaksi kadaluwarsa, atau pengembalian dana. | `transactions` |
