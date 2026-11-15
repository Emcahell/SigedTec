#!/bin/sh
set -e

echo "=== Iniciando configuración de Laravel ==="

# Directorios que Laravel / PHP-FPM deben poder escribir en runtime.
# Sin esto, Blade Icons (componentes Closure) fallan al compilar vistas con:
# tempnam(): file created in the system's temporary directory → 500
ensure_writable_dirs() {
    mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/testing \
        storage/logs \
        bootstrap/cache

    # 777 es lo correcto en contenedores efímeros de Render: artisan corre como root
    # y PHP-FPM suele correr con otro usuario; el chown del /start.sh de la imagen
    # base falla con "unknown user nginx.nginx".
    chmod -R 777 storage bootstrap/cache
}

ensure_writable_dirs

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

# Ejecutar seeders (crear usuario admin si no existe)
echo "Ejecutando seeders..."
php artisan db:seed --force --no-interaction

# Generar manifest de Vite (siempre, para asegurar que exista)
echo "Generando manifest de Vite..."
CSS_FILE=$(ls public/build/assets/*.css 2>/dev/null | head -1)
JS_FILE=$(ls public/build/assets/*.js 2>/dev/null | head -1)
if [ -n "$CSS_FILE" ] && [ -n "$JS_FILE" ]; then
    CSS_NAME=$(basename "$CSS_FILE")
    JS_NAME=$(basename "$JS_FILE")
    # Vite 8 puede emitir el manifest en public/build/.vite/manifest.json;
    # Laravel también busca public/build/manifest.json.
    MANIFEST_JSON="{\"resources/css/app.css\":{\"file\":\"assets/$CSS_NAME\",\"src\":\"resources/css/app.css\"},\"resources/js/app.js\":{\"file\":\"assets/$JS_NAME\",\"src\":\"resources/js/app.js\"}}"
    mkdir -p public/build/.vite
    printf '%s\n' "$MANIFEST_JSON" > public/build/manifest.json
    printf '%s\n' "$MANIFEST_JSON" > public/build/.vite/manifest.json
    echo "Manifest de Vite generado:"
    cat public/build/manifest.json
else
    echo "ADVERTENCIA: No se encontraron archivos CSS/JS en public/build/assets/"
fi

# Copiar configuración de nginx para servir archivos estáticos
echo "Configurando nginx para archivos estáticos..."
if [ -f config/nginx/default.conf ]; then
    cp config/nginx/default.conf /etc/nginx/conf.d/default.conf
    echo "Configuración de nginx copiada"
else
    echo "ADVERTENCIA: No se encontró config/nginx/default.conf"
fi

# Crear caché de configuración y rutas
echo "Creando caché de configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Artisan (root) acaba de crear archivos en storage/bootstrap/cache:
# reaplicar permisos para que PHP-FPM pueda seguir compilando componentes en runtime.
ensure_writable_dirs

echo "=== Iniciando servidor web ==="
exec /start.sh
