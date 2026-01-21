# Etapa 1: Vendor (Composer)
FROM composer:latest as vendor

# Directorio de trabajo
WORKDIR /app

# Copia los archivos de dependencias de Composer
COPY composer.json composer.lock ./

# Instala las dependencias optimizadas para producción (sin dependencias de desarrollo)
# Aunque para un entorno de pruebas podrían requerirse dependencias de desarrollo,
# aquí se utiliza una configuración estándar optimizada.
RUN composer install --no-dev --ignore-platform-reqs --no-interaction --prefer-dist


# Etapa 2: Aplicación
FROM php:8.2-fpm-alpine

# Instalación de dependencias del sistema necesarias para PHP y la aplicación
RUN apk add --no-cache \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    zip \
    unzip \
    git \
    curl \
    mysql-client

# Configuración e instalación de extensiones PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql intl zip bcmath opcache pcntl

# Copia el binario de Composer desde la etapa vendor
COPY --from=vendor /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo de la aplicación
WORKDIR /var/www/html

# Copia el código fuente de la aplicación
# Nota: En desarrollo con docker-compose normalmente se montan volúmenes,
# pero para un build de producción se copia todo el código fuente.
COPY . .

# Copia la carpeta vendor generada en la etapa 1
COPY --from=vendor /app/vendor ./vendor

# Asigna permisos correctos al usuario www-data
RUN chown -R www-data:www-data /var/www/html
