#!/bin/bash
set -e

# Support Render custom port or default to 80
PORT_TO_USE="${PORT:-80}"
sed -i "s/Listen 80/Listen $PORT_TO_USE/g" /etc/apache2/ports.conf
sed -i "s/:80/:$PORT_TO_USE/g" /etc/apache2/sites-available/000-default.conf

# Production environment variables for Render
export APP_ENV=production
if [ -n "$RENDER_EXTERNAL_URL" ]; then
    export APP_URL="$RENDER_EXTERNAL_URL"
    export ASSET_URL="$RENDER_EXTERNAL_URL"
else
    export APP_URL="https://priceless-store.onrender.com"
    export ASSET_URL="https://priceless-store.onrender.com"
fi

# Ensure database directory and sqlite file exist
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Ensure storage directories exist
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Storage link
php artisan storage:link || true

# Run database migrations and seeds
php artisan migrate --force || true
php artisan db:seed --force || true

# Clear any stale local caches
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Starting Apache on port $PORT_TO_USE with APP_URL=$APP_URL..."
exec apache2-foreground
