FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    sqlite3 \ 
    libsqlite3-dev \
    dos2unix \ 
    && docker-php-ext-install pdo pdo_sqlite \ 
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
    
WORKDIR /var/www/html

COPY ./src .

COPY ./data/init.sql ./data/init.sql

COPY ./entrypoint.sh /usr/local/bin/entrypoint.sh

RUN dos2unix /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

RUN chown -R www-data:www-data ./data

EXPOSE 80

ENTRYPOINT [ "entrypoint.sh" ]