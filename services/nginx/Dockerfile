FROM nginx:alpine

COPY /services/nginx/config/default.conf /etc/nginx/conf.d/
COPY /services/nginx/config/nginx.conf /etc/nginx/nginx.conf

# Create www-data user for nginx
RUN addgroup -g 1000 -S www-data \
 && adduser -u 1000 -D -S -G www-data www-data

# Changing Workdir
WORKDIR /var/www/app