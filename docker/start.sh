#!/bin/sh
set -e

echo "==> Starting UniVote..."

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "==> Caching configuration..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "==> Running migrations..."
php artisan migrate --force

# Create storage symlink
echo "==> Linking storage..."
php artisan storage:link || true

# Fix permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Start PHP-FPM in background
echo "==> Starting PHP-FPM..."
php-fpm -D

# Start Nginx in foreground
echo "==> Starting Nginx on port 10000..."
nginx -g "daemon off;"
