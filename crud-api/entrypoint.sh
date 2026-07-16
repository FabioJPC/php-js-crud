#!/bin/sh

if [ ! -f /var/www/html/data/database.db ]; then
    echo "Database not found. Initializing..."
    sqlite3 /var/www/html/data/database.db < /var/www/html/data/init.sql
    chown -R www-data:www-data /var/www/html/data
else
    echo "A database was found, skiping creation and seeding"
fi

exec apache2-foreground