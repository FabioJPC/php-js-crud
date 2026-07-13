FROM php:8.2-apache

WORKDIR /var/www/
 
COPY ./src /var/www/

COPY ./data /var/www/data

RUN apt-get update && apt-get install -y \
    sqlite3\ 
    libsqlite3-dev

RUN docker-php-ext-install pdo pdo_sqlite

RUN chown -R www-data:www-data /var/www/data

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!/var/www/public!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

RUN sed -ri 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

EXPOSE 80