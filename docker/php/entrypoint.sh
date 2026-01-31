#!/bin/sh
set -e

cd /var/www/html

if [ ! -d vendor ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction
fi

exec apache2-foreground
