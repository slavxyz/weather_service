FROM php:8.3-apache

# Enable Apache rewrite (required for Symfony)
RUN a2enmod rewrite

# System deps
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_mysql \
    zip

# Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache virtual host
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
