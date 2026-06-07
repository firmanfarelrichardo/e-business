#!/bin/bash
set -e

# Change ownership of standard laravel directories to www-data
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec "$@"
