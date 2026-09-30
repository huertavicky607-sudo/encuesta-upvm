# 1. Usar imagen oficial de PHP con Apache
FROM php:8.3-apache

# 2. Instalar extensiones de MySQL
RUN docker-php-ext-install mysqli pdo_mysql

# 3. Copiar todos los archivos a Apache
COPY . /var/www/html/

# 4. Configurar el puerto para Render
ENV PORT 10000
RUN sed -i 's/80/${PORT}/g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/ports.conf