#!/bin/sh
set -e

# Cache configuration & views
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Prepare SQLite if used as fallback
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_HOST" ]; then
    mkdir -p /var/www/html/database
    touch /var/www/html/database/database.sqlite
    export DB_CONNECTION=sqlite
    export DB_DATABASE=/var/www/html/database/database.sqlite
fi

# Run migrations and seeding if needed
php artisan migrate --force
if [ "$SEED_ON_DEPLOY" = "true" ]; then
    php artisan db:seed --force
fi

php artisan storage:link || true
php artisan filament:assets || true

echo "Starting Masari Platform on port ${PORT:-10000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
