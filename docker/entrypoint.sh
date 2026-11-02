#!/bin/sh
# Start-up container: siapkan env runtime, migrate, cache config, lalu jalankan Apache.
set -e
cd /var/www/html

# Render memberi port lewat $PORT (default 10000)
PORT="${PORT:-10000}"
sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# APP_KEY: 32 karakter mentah (tombol Generate di dashboard) dipakai apa adanya;
# base64 32 byte tanpa prefix (generateValue Blueprint, 44 karakter) diberi "base64:"
if [ -n "$APP_KEY" ] && [ "${APP_KEY#base64:}" = "$APP_KEY" ] && [ "${#APP_KEY}" -eq 44 ]; then
    export APP_KEY="base64:${APP_KEY}"
fi

# Aiven MySQL wajib SSL: tempel isi ca.pem di env DB_SSL_CA_PEM
if [ -n "$DB_SSL_CA_PEM" ]; then
    printf '%s\n' "$DB_SSL_CA_PEM" > /var/www/html/storage/db-ca.pem
    export MYSQL_ATTR_SSL_CA=/var/www/html/storage/db-ca.pem
fi

# Diagnosa koneksi DB tanpa membocorkan password (panjang + 12 karakter awal sha256)
echo "[entrypoint] DB ${DB_USERNAME:-?}@${DB_HOST:-?}:${DB_PORT:-?}/${DB_DATABASE:-?} password_len=${#DB_PASSWORD} password_sha=$(printf '%s' "$DB_PASSWORD" | sha256sum | cut -c1-12) ssl_ca=${MYSQL_ATTR_SSL_CA:-none}"

php artisan config:clear >/dev/null 2>&1 || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data storage bootstrap/cache public/images

exec "$@"
