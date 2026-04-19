# Menggunakan PHP 8.3 FPM image resmi (sangat direkomendasikan untuk stabilitas Laravel 12)
FROM php:8.3-fpm

# Definisikan folder utama aplikasi dalam container
WORKDIR /var/www

# Install dependensi sistem dasar untuk ekstensi PHP, image processing, dan utilitas umum
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libonig-dev

# Bersihkan system cache instalasi (apt) untuk merampingkan ukuran image 
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Ekstensi PHP wajib bagi Laravel dan PosgreSQL driver (pdo_pgsql)
RUN docker-php-ext-install pdo_pgsql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install Redis extension (pecl) untuk optimalisasi caching laravel
RUN pecl install redis && docker-php-ext-enable redis

# Install Node.js v20 (LTS) & NPM guna keperluan kompilasi Asset melalui Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Mengunduh dan install Composer secara mandiri dari Official Image buildnya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Membuat user group (www) tanpa root privilege agar lebih sinkron ownership-nya saat volume binding
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Seting permison file host copy - Opsional saat dev, best practice mencegah file conflict
COPY --chown=www:www . /var/www

# Ganti menjadi user 'www' sebagai pemicu aplikasi untuk keamanan dan kebersihan permissions
USER www

# Memberi keterangan port yang terekspos (9000 untuk FPM proxy, 5173 konektor HMR Vite)
EXPOSE 9000 5173

# Perintah Default container saat hidup (Run fastcgi proxy server)
CMD ["php-fpm"]
