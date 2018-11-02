FROM php:7.2-fpm
LABEL maintainer="vladimir.pogarsky@roonyx.tech"

# set-up php config
COPY /docker/php/php.ini /usr/local/etc/php/

# Installing dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev

# Installing extensions
RUN docker-php-ext-install pdo mbstring zip exif pcntl opcache

# Installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Changing Workdir
WORKDIR /var/www/app

# Give access control under app directory to www-data user
RUN chown -R www-data:www-data .

# Copying application code
COPY --chown=www-data:www-data . ./

# Create directory for composer cache
RUN mkdir /var/www/.composer && chown www-data:www-data /var/www/.composer

# Set app user
USER www-data

# Create .env file for APP_KEY
RUN touch -a .env

# Install composer parallel packages installer plugin
RUN composer global require hirak/prestissimo

# Determine is it dev build
ARG DEV

# Install dependencies
RUN if [ $DEV -eq 1 ]; then cd src && sudocomposer install; else cd src && composer install --no-dev --optimize-autoloader; fi

RUN chmod 777 -R ./src/storage \
    && chgrp -R www-data ./src/storage ./src/bootstrap/cache \
    && chmod -R ug+wrx ./src/storage ./src/bootstrap/cache