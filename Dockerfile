# Gunakan base image PHP resmi
FROM php:8.2-cli

# Install dependensi sistem yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    nodejs \
    npm

# Install ekstensi PHP yang umum digunakan
RUN docker-php-ext-install pdo pdo_pgsql zip

# Install Composer (untuk manajemen package PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Tentukan direktori kerja di dalam container
WORKDIR /app

# Salin semua file proyek Anda ke dalam direktori kerja
COPY . .

# Perintah selanjutnya (seperti build.sh) akan dijalankan oleh Render
