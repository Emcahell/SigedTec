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

# Verificar y generar manifest de Vite si no existe
if [ ! -f public/build/manifest.json ]; then
    echo "Generando manifest de Vite..."
    CSS_FILE=$(ls public/build/assets/*.css 2>/dev/null | head -1)
    JS_FILE=$(ls public/build/assets/*.js 2>/dev/null | head -1)
    if [ -n "$CSS_FILE" ] && [ -n "$JS_FILE" ]; then
        CSS_NAME=$(basename "$CSS_FILE")
        JS_NAME=$(basename "$JS_FILE")
        echo "{\"resources/css/app.css\": {\"file\": \"assets/$CSS_NAME\"}, \"resources/js/app.js\": {\"file\": \"assets/$JS_NAME\"}}" > public/build/manifest.json
        echo "Manifest de Vite generado correctamente"
    else
        echo "ADVERTENCIA: No se encontraron archivos CSS/JS en public/build/assets/"
    fi
fi

# Crear caché de configuración y rutas
echo "Creando caché de configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Iniciando servidor web ==="
exec /start.sh
