#!/bin/sh
set -e

echo "Starting deployment process..."

# Wait for database to be ready
echo "Waiting for database connection..."
until php artisan db:show 2>/dev/null; do
    echo "Database not ready yet, waiting..."
    sleep 2
done

echo "Database connection established!"

# Run migrations
echo "Running migrations..."
php artisan migrate --force || {
    echo "Migration failed, but continuing..."
}

# Clear and cache configuration
echo "Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Start Supervisor
echo "Starting web server..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
