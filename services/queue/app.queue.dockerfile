FROM php:7.2-fpm
LABEL maintainer="vladimir.pogarsky@roonyx.team"

# set-up php config
COPY /docker/php/php.ini /usr/local/etc/php/

# Installing dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install iconv mbstring zip exif pcntl opcache mysqli pdo pdo_mysql

# Changing Workdir
WORKDIR /var/www/app

# Set app user
USER www-data

# Determine is it dev build
ARG DEV

ENTRYPOINT php artisan queue:work --timeout=300 --tries=2