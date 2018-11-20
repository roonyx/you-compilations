#!/bin/sh

echo "Database starting...";
sleep 5s;

# Clear the cache
echo "Cache is cleaning...";
php artisan cache:clear --no-interaction

# Run migrations
echo "Migrations is running...";
if [ "$APP_ENV" = "production" ]; then
    php artisan migrate --force --no-interaction;
else
    php artisan migrate:refresh --seed --no-interaction;
fi

echo "Service is complete..."

# Run
exec "$@"