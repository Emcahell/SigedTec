#!/bin/sh
set -e

echo "=== Iniciando configuración de Laravel ==="

# Verificar y generar APP_KEY si no existe
if [ -z "$APP_KEY" ]; then
    echo "APP_KEY no definida, generando..."
    php artisan key:generate --force --no-interaction
fi

# Limpiar caché existente para evitar conflictos
echo "Limpiando caché..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --force --no-interaction

# Crear caché de configuración y rutas
echo "Creando caché de configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Iniciando servidor web ==="
exec /start.sh
