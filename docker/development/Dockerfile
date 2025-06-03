# Stage 1: Composer Builder
FROM composer:2.8 AS composer-builder
WORKDIR /app

# Copiar archivos de Composer
COPY composer.json composer.lock ./

# Instalar dependencias PHP sin paquetes de desarrollo
RUN composer install --no-dev --optimize-autoloader --prefer-dist --ignore-platform-reqs --no-scripts


# Stage 2: Node.js Asset Builder
FROM node:22-slim AS asset-builder
WORKDIR /app

# Copiar y instalar dependencias de Node.js
COPY package.json package-lock.json ./
RUN npm install

# Copiar dependencias de PHP antes de compilar assets
COPY --from=composer-builder /app/vendor ./vendor

# Copiar el resto del código y compilar assets
COPY . .
RUN npm run build

# Stage 3: Production PHP-FPM
FROM php:8.2-fpm

WORKDIR /var/www/html

# Copiar el código fuente
COPY . .

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libzip-dev \
    unzip git libicu-dev libonig-dev libxml2-dev nano curl libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql intl zip mbstring exif pcntl bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# Copiar las dependencias de Composer
COPY --from=composer-builder /app/vendor /var/www/html/vendor

# Copiar la carpeta "public" compilada en la etapa de assets
COPY --from=asset-builder /app/public /var/www/html/public

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto en el que PHP-FPM escucha (por defecto 9000)
EXPOSE 9000

# Aumentar memoria
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

# Iniciar PHP-FPM
CMD ["php-fpm"]
