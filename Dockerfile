FROM php:8.2-apache

# PDO MySQL for the data layer; GD for validating/handling uploaded images.
RUN docker-php-ext-install pdo_mysql \
    && apt-get update \
    && apt-get install -y --no-install-recommends libpng-dev libjpeg-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Project root is the web root; frontend lives at /public, admin at /admin.
WORKDIR /var/www/html

# Uploads dir must be writable by the Apache user.
RUN mkdir -p public/uploads && chown -R www-data:www-data public/uploads
