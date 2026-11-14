#!/bin/sh
set -e

echo "Ejecutando migraciones de Laravel..."
php artisan migrate --force

echo "Optimizando caché..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Iniciando servidor web..."
exec /start.sh
