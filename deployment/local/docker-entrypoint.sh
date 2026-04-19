#!/bin/bash
set -e

# Change ownership of standard laravel directories to www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

# Run composer if vendor directory doesn't exist or is empty
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
    echo "Running composer install..."
    su www-data -s /bin/bash -c "composer install --no-interaction"
fi

# Run NPM if node_modules doesn't exist
if [ -x "$(command -v npm)" ]; then
    if [ ! -d "node_modules" ] || [ -z "$(ls -A node_modules)" ]; then
        echo "Running npm install..."
        su www-data -s /bin/bash -c "npm install"
    fi
    echo "Running npm run build for Vite assets..."
    su www-data -s /bin/bash -c "npm run build"
fi

# Generate Laravel Key if missing
if ! grep -q "^APP_KEY=base64:" .env; then
    su www-data -s /bin/bash -c "php artisan key:generate --no-interaction"
fi

# Run migrations
echo "Running migrations..."
su www-data -s /bin/bash -c "php artisan migrate --force"

exec "$@"
