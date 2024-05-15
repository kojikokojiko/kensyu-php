FROM php:8.1-apache

COPY php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY apache2/apache2.conf /etc/apache2/
COPY apache2/sites-available/000-default.conf /etc/apache2/sites-available/
