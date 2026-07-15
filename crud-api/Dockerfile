FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    sqlite3 \ 
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \ 
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
    
WORKDIR /var/www/

COPY ./src /var/www/

COPY ./data /var/www/data

RUN chown -R www-data:www-data /var/www/data

EXPOSE 80