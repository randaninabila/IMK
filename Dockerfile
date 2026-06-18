# Stage 1: Build frontend
FROM node:22 AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# Stage 2: PHP + Laravel
FROM php:8.4-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

COPY --from=node_builder /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader

# 🔥 INI YANG KAMU BUTUHKAN
RUN php artisan storage:link || true

RUN php artisan config:clear || true
RUN php artisan route:clear || true

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=$PORT