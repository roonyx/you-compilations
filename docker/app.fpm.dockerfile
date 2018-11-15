FROM php:7.2-fpm
LABEL maintainer="vladimir.pogarsky@roonyx.team"


# set-up php config
COPY /docker/php/php.ini /usr/local/etc/php/

# Installing dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install iconv mbstring zip exif pcntl opcache mysqli pdo pdo_mysql

# Installing composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Changing Workdir
WORKDIR /var/www/app

# Add entrypoint script
COPY ./docker/entrypoint/app.fpm.entrypoint.sh /entry_point.sh

# Give access control under app directory to www-data user
RUN chown -R www-data:www-data .

# Copying application code
COPY --chown=www-data:www-data ./src/ ./

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
RUN if [ $DEV -eq 1 ]; then sudo composer install; else composer install --no-dev --optimize-autoloader; fi

RUN chmod 775 -R storage \
    && chgrp -R www-data storage bootstrap/cache \
    && chmod -R ug+wrx storage bootstrap/cache

ENTRYPOINT ["/entry_point.sh"]

# Add healthcheck
HEALTHCHECK --interval=10s --timeout=3s \
    CMD \
    SCRIPT_NAME=/ping \
    SCRIPT_FILENAME=/ping \
    REQUEST_METHOD=GET \
    cgi-fcgi -bind -connect 127.0.0.1:9000 || exit 1

CMD ["php-fpm"]

EXPOSE 9000