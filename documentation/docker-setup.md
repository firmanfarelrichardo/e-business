# Dokumentasi Environment Docker E-Business 📖

## 1. Arsitektur Terpisah (Local vs Production)
Sistem ini menggunakan arsitektur containerization (Docker) yang dipisahkan secara tegas untuk kebutuhan Development (Lokal) dan Deployment (Production). 
Pemisahan struktur direktori memiliki fungsi utamanya masing-masing.

### A. Environment Lokal (`deployment/local/`)
Ditargetkan khusus developer agar proses pengembangan berjalan mulus dengan auto-loading dan sinkronisasi code base dengan PC (*Host*).
- **Konsep:** *Volume Binding* diaktifkan 100%. Tidak perlu re-build image setiap kali merubah kode program.
- **Tools:** Node.js, bash, development extension diaktifkan sepenuhnya.

### B. Environment Production (`deployment/production/`)
Dirancang bagi operasional server dengan fokus penuh pada Keamanan (Security) dan Kecepatan (Performance).
- **Konsep Utama 1 (Isolasi Jaringan DB):** Server basis data dan redis tidak dapat diakses dari internet sama sekali, mereka berada di sebuah isolated internal network (`internal: true`).
- **Konsep Utama 2 (Zero Volume Source):** *No Bind Volume*, Source Code anda "di-bakar" langsung ke dalam Docker Image melalui teknik **Multi-stage build** sehingga container berjalan mandiri (Immutable Deployment).

---

## 2. Cara Menjalankan Environment Lokal (Panduan Terminal)

Untuk mulai mendevelop di mesin kamu, cukup buka terminal/command prompt/powershell di root project direktori anda (`e-business/`), lalu jalankan langkah yang dijabarkan berikut ini:

### Eksekusi Otomatis (Direkomendasikan)
Kamu dapat menjalankan shell script yang telah ditambahkan. *Disclaimer: Pastikan terminal kamu mendukung script bash/sh (contoh gunakan Git Bash / WSL di Windows).*

1. Pindah ke folder deployment local.
   ```bash
   cd deployment/local
   ```
2. Berikan permission Eksekusi (khusus pertama kali pada Linux/Mac/WSL)
   ```bash
   chmod +x deploy.sh
   ```
3. Eksekusi file dan tunggu proses background.
   ```bash
   ./deploy.sh
   ```

### Eksekusi Manual (Langkah demi Langkah)
Bila ingin menjalan command step-by-step:

1. Arahkan pada path config dockercompose:
   ```bash
   cd deployment/local
   ```
2. Nyalakan layanan (Akan mencetak image/pull bila belum ada)
   ```bash
   docker-compose up -d --build
   ```
3. Lakukan penginstalan Pustaka dependency PHP dan NPM:
   ```bash
   docker-compose exec app composer install
   docker-compose exec app npm install
   ```
4. Setup konfigurasi App Anda (Copy .env, Key Generate, dan Database Migration)
   ```bash
   cp ../../.env.example ../../.env
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate
   ```

### Mengakses Aplikasi
- **Aplikasi Frontend/Web:** [http://localhost:8000](http://localhost:8000)
- **Kompilasi Frontend / HMR Live Vite Server:** Anda dapat menjalankan frontend service dari docker dan dev browser akan merespon live saat kamu edit JS/CSS (Berjalan di background host `localhost:5173`)
   ```bash
   docker-compose exec app npm run dev
   ```

---

## 3. Cara Menjalankan untuk Level Production

Untuk Deployment di Cloud VPS (Contoh AWS, DigitalOcean, Linode):

1. Pastikan repo telah tersinkron dan jalankan command ini:
   ```bash
   cd deployment/production
   ```
2. Build Image rilis terbaru dan jalankan (akan sedikit lebih lama karena *multi-stage*)
   ```bash
   docker-compose up -d --build
   ```
3. Akses container `app` dan jalankan migrasi yang aman:
   ```bash
   docker-compose exec app php artisan migrate --force
   ```
*Catatan:* Pada environment production kamu tidak perlu menjalankan command composer/npm di tahap container, source code kamu telah tercache & optimal hasil dari internal docker engine Build.

---
**Tips**: Untuk mematikan environment secara aman dan tidak meninggalkan zombie proses. Gunakan: `docker-compose down`.
