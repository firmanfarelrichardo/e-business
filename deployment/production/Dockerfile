# ======================================================================
# MULTI-STAGE BUILD PATTERN UNTUK HIGHEST PERFORMANCE DAN LEAN IMAGE
# ======================================================================

# --------------------------
# TAHAP 1: BUILDER
# --------------------------
FROM php:8.3-fpm AS builder

WORKDIR /var/www

# Instal dependensi dasar build dan PostgreSQL C/C++ client
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo_pgsql zip

# Instal Node.js (digunakan untuk membuild frontend assets vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Pindahkan dan gunakan Composer dari Base Image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Teknik "Layer Caching" - Pindah dependencies file terlebih dahulu sebelum Source agar caching optimal saat code kita berubah 
COPY composer.json composer.lock package.json package-lock.json ./

# Install vendor Composer TANPA module development (--no-dev) & TANPA generate autoloader untuk kecepatan install layer
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Install dependency NPM (hanya dilakukan di tahap builder)
RUN npm install

# Pindahkan file code source project semuanya secara utuh (kecuali list dari .dockerignore)
COPY . .

# Buat Autoloader yang optimal berdasarkan classmap dan compile file statis prod Vite
RUN composer dump-autoload --optimize \
    && npm run build

# --------------------------
# TAHAP 2: FINAL PRODUCTION IMAGE
# --------------------------
FROM php:8.3-fpm

WORKDIR /var/www

# Hanya install environment core production tanpa build tool (node dll) -> Sangat menghemat space image
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo_pgsql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Pindahkan seluruh source yang TELAH DIBUILD dari tahap "builder" (Tahap 1)
COPY --from=builder /var/www /var/www

# Atur Ijin Files ketat: Milik root system production default nginx (www-data)
RUN chown -R www-data:www-data /var/www \
    # Folder storage & cache diberi akses RW/R executable
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Bind akses operasi pada environment container ke id yang telah dilimit aksesnya
USER www-data

# Inform port spesifik FPM
EXPOSE 9000

CMD ["php-fpm"]
