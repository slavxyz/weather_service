#!/bin/sh
set -e

cd /var/www/html

# Install composer dependencies if missing
if [ ! -d vendor ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction
fi

# Start Apache
exec apache2-foreground
