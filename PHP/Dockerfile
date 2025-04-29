FROM php:8.3.0-fpm-alpine

RUN apk update && apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    mysql-client \
    bash

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html