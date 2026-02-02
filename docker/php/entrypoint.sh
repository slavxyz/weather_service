#!/bin/sh
set -e

cd /var/www/html

if [ ! -d vendor ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction
fi

php bin/console doctrine:migrations:migrate --no-interaction 
php bin/console doctrine:fixtures:load --no-interaction 

exec apache2-foreground
