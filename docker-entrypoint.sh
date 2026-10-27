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
if [ "$SEED_ON_DEPLOY" = "true" ] || [ ! -f "/var/www/html/database/.seeded" ]; then
    php artisan db:seed --force || true
    touch /var/www/html/database/.seeded
fi

# Ensure default admin account exists
php artisan tinker --execute="App\Models\User::updateOrCreate(['email' => 'admin@lrf-blida.dz'], ['name' => 'مدير النظام - الرابطة الجهوية بالبليدة', 'password' => bcrypt('password123'), 'role' => 'admin', 'is_active' => true, 'club_id' => null]); App\Models\User::updateOrCreate(['email' => 'admin@messari.dz'], ['name' => 'مسؤول الإدارة العامة - مساري', 'password' => bcrypt('Admin@2026!'), 'role' => 'admin', 'is_active' => true, 'club_id' => null]);"

php artisan storage:link || true
php artisan filament:assets || true

echo "Starting Masari Platform on port ${PORT:-10000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
