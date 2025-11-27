#!/bin/sh

echo "🚀 Starting Nomadiq Travel Backend..."

# Auto-configure database for Railway if variables are missing
if [ -z "$DB_HOST" ] && [ -n "$MYSQLHOST" ]; then
    echo "🔄 Detected Railway MySQL variables, mapping to Laravel defaults..."
    export DB_HOST="$MYSQLHOST"
    export DB_PORT="$MYSQLPORT"
    export DB_DATABASE="$MYSQLDATABASE"
    export DB_USERNAME="$MYSQLUSER"
    export DB_PASSWORD="$MYSQLPASSWORD"
fi

# Force MySQL connection if not set
if [ -z "$DB_CONNECTION" ]; then
    echo "⚠️  DB_CONNECTION not set, defaulting to 'mysql'..."
    export DB_CONNECTION=mysql
fi

echo "Environment Check:"
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_PORT: $DB_PORT"
echo "DB_DATABASE: $DB_DATABASE"

# Don't exit on error - we want to try to start the web server even if some steps fail
set +e

# Wait for database to be ready (with timeout)
echo "⏳ Waiting for database connection..."
COUNTER=0
MAX_TRIES=30
until php artisan db:show 2>/dev/null || [ $COUNTER -eq $MAX_TRIES ]; do
    echo "Database not ready yet, attempt $((COUNTER+1))/$MAX_TRIES..."
    COUNTER=$((COUNTER+1))
    sleep 2
done

if [ $COUNTER -eq $MAX_TRIES ]; then
    echo "⚠️  Database connection timeout, continuing anyway..."
else
    echo "✅ Database connection established!"
fi

# Run migrations (don't fail if migrations error)
echo "🔄 Running database migrations..."
php artisan migrate --force 2>&1 || {
    echo "⚠️  Migration failed, but continuing to start the server..."
}

# Clear and optimize (don't fail on errors)
echo "⚡ Optimizing application..."
php artisan config:clear 2>&1 || true
php artisan route:clear 2>&1 || true
php artisan view:clear 2>&1 || true

php artisan config:cache 2>&1 || echo "⚠️  Config cache failed"
php artisan route:cache 2>&1 || echo "⚠️  Route cache failed"
php artisan view:cache 2>&1 || echo "⚠️  View cache failed"

# Ensure storage directories exist and have correct permissions
echo "📁 Setting up storage..."
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
chown -R www-data:www-data /var/www/storage
chmod -R 775 /var/www/storage

# Start Supervisor (this should NOT fail)
echo "🌐 Starting web server..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
