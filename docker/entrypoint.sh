#!/bin/sh
set -e

cd /var/www/html


ROLE="${CONTAINER_ROLE:-app}"

if [ "$ROLE" = "app" ]; then
    if [ ! -f vendor/autoload.php ]; then
        composer install --no-interaction --prefer-dist
    fi

    if [ ! -f .env ]; then
        cp .env.example .env
    fi

    if ! grep -q '^APP_KEY=base64' .env; then
        php artisan key:generate --force
    fi
else
    echo "[$ROLE] Esperando a que app instale las dependencias..."
    until [ -f vendor/autoload.php ] && grep -q '^APP_KEY=base64' .env 2>/dev/null; do
        sleep 2
    done
fi


echo "[$ROLE] Esperando a MySQL en ${DB_HOST}:${DB_PORT}..."
until php -r 'new PDO("mysql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));' >/dev/null 2>&1; do
    sleep 2
done

if [ "$ROLE" = "app" ]; then
    php artisan migrate --force
fi

exec "$@"
