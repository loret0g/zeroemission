# Usamos la imagen oficial de PHP 8.2 con Apache
FROM php:8.2-apache

# Instala extensiones que puedan hacer falta:
# - libzip-dev y unzip, para que Composer pueda manejar dependencias que usan zip
# - mysqli, pdo, pdo_mysql para conectar a MySQL
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Activamos mod_rewrite (si necesitas .htaccess)
RUN a2enmod rewrite

# Ajustamos Apache para que permita .htaccess en el directorio /var/www/html/public
# Esta parte es opcional, depende de si usas .htaccess o no.
# Ajustamos la configuración base de Apache:
# - Cambiamos la DocumentRoot a /var/www/html/public
# - Permitimos override All para que .htaccess funcione
RUN sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Establecemos nuestro directorio de trabajo dentro del contenedor
WORKDIR /var/www/html

# Copiamos TODO el proyecto al contenedor
COPY . .

# Descargar e instalar Composer dentro del contenedor
RUN curl -sS https://getcomposer.org/installer | php
RUN php composer.phar install --no-dev --optimize-autoloader

# Exponemos el puerto 80 (el puerto por defecto de Apache)
EXPOSE 80

# Comando para iniciar Apache en primer plano
CMD ["apache2-foreground"]