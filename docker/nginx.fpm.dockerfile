FROM nginx:alpine

COPY /docker/fpm/default.conf /etc/nginx/conf.d/
COPY /docker/nginx/nginx.conf /etc/nginx/nginx.conf

# Create www-data user for nginx
RUN addgroup -g 1000 -S www-data \
 && adduser -u 1000 -D -S -G www-data www-data

# Changing Workdir
WORKDIR /var/www/app