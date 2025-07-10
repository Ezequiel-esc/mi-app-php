FROM php:8.2-apache

# Instala dependencias necesarias para compilar pdo_pgsql
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copia los archivos del proyecto al contenedor
COPY . /var/www/html/

# Expone el puerto 80
EXPOSE 80
