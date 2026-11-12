#!/bin/sh
set -e

cd /var/www/html

# Con el código montado como volumen, vendor puede no existir
if [ ! -f vendor/autoload.php ]; then
    composer install --no-interaction --prefer-dist
fi

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64' .env; then
    php artisan key:generate --force
fi

# Esperar a que MySQL acepte conexiones
echo "Esperando a MySQL en ${DB_HOST}:${DB_PORT}..."
until mysqladmin ping -h"${DB_HOST}" -P"${DB_PORT}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent 2>/dev/null; do
    sleep 2
done

php artisan migrate --force

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

exec "$@"
