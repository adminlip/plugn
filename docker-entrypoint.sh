#!/bin/bash
set -e

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
MAX_RETRIES=30
RETRY_INTERVAL=2
retries=0

until php -r "
try {
    \$pdo = new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306}', '${DB_USER:-plugnuser}', '${DB_PASSWORD:-plugn}');
    echo 'connected';
    exit(0);
} catch (PDOException \$e) {
    exit(1);
}
" 2>/dev/null; do
    retries=$((retries + 1))
    if [ "$retries" -ge "$MAX_RETRIES" ]; then
        echo "ERROR: MySQL not ready after $((MAX_RETRIES * RETRY_INTERVAL)) seconds. Aborting."
        exit 1
    fi
    echo "  MySQL not ready yet (attempt $retries/$MAX_RETRIES)..."
    sleep $RETRY_INTERVAL
done
echo "MySQL is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis to be ready..."
retries=0
until redis-cli -h "${REDIS_HOST:-redis}" -p "${REDIS_PORT:-6379}" ping 2>/dev/null | grep -q PONG; do
    retries=$((retries + 1))
    if [ "$retries" -ge "$MAX_RETRIES" ]; then
        echo "ERROR: Redis not ready after $((MAX_RETRIES * RETRY_INTERVAL)) seconds. Aborting."
        exit 1
    fi
    echo "  Redis not ready yet (attempt $retries/$MAX_RETRIES)..."
    sleep $RETRY_INTERVAL
done
echo "Redis is ready!"

# Run Yii initialization
echo "Running Yii init (${APP_ENV:-Dev-Server-Docker})..."
./init --env="${APP_ENV:-Dev-Server-Docker}" --overwrite=All

# Run database migrations
echo "Running database migrations..."
./yii migrate --interactive=0

# Start cron service
echo "Starting cron service..."
service cron start || true

# Configure and start Nginx
echo "Starting Nginx..."
service nginx restart

# Execute any additional commands passed as arguments
if [ $# -gt 0 ]; then
    exec "$@"
else
    echo "Starting PHP-FPM in foreground..."
    exec php-fpm
fi