# Usa la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copia los archivos del proyecto al servidor web
COPY . /var/www/html/

# Instala la extensión PDO para PostgreSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Expone el puerto 80 para HTTP
EXPOSE 80
