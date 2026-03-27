#!/bin/sh
set -e

echo "========================================="
echo " GlowBook — Starting deployment..."
echo "========================================="

cd /var/www/html

# Create .env from .env.example if it doesn't exist (needed by artisan commands)
if [ ! -f .env ]; then
    echo "[start.sh] .env not found, creating from .env.example..."
    cp .env.example .env
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ]; then
    echo "[start.sh] APP_KEY not set, generating..."
    php artisan key:generate --force
fi

# Log to stderr so errors are visible in Railway/Docker logs
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

# Set PORT default
PORT="${PORT:-80}"
echo "[start.sh] Listening on port: $PORT"

# Replace $PORT in nginx config via envsubst
export PORT
envsubst '${PORT}' < /etc/nginx/nginx.conf > /tmp/nginx.conf
cp /tmp/nginx.conf /etc/nginx/nginx.conf

# Storage permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Create storage link
php artisan storage:link --force 2>/dev/null || true

# Laravel optimizations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "[start.sh] Running database migrations..."
php artisan migrate --force

# Create supervisor log directory
mkdir -p /var/log/supervisor

echo "========================================="
echo " GlowBook — Ready! Port $PORT"
echo "========================================="

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
