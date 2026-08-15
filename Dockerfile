# STAGE 1: Build & Install Dependencies

FROM composer:2.7 AS builder

WORKDIR /app

# Copy dependency definition
COPY composer.json composer.lock ./

# Install dependencies tanpa dev-dependencies untuk optimasi image
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# Copy seluruh source code
COPY . .

# Jalankan dump-autoload teroptimasi
RUN composer dump-autoload --optimize

# STAGE 2: Production Runtime (PHP-FPM)
FROM php:8.3-fpm-alpine AS runner

WORKDIR /var/www/html

# Install dependensi sistem dan ekstensif PHP dasar yang dibutuhkan Laravel
RUN apk add --no-cache \
    sqlite-dev \
    oniguruma-dev \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring bcmath gd xml

# Salin aplikasi dari stage builder
COPY --from=builder /app /var/www/html

# Set permission direktori storage dan cache untuk user www-data
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

USER www-data

EXPOSE 9000

CMD ["php-fpm"]