FROM php:8.3-apache

COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY apache2/sites-available/000-default.conf /etc/apache2/sites-available/

