# Etapa 1: Compilar assets
FROM node:20-alpine AS build
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: Imagen final de PHP
FROM fflo/nginx-php-fpm:latest
WORKDIR /var/www/html

# Copiar el código fuente (sin node_modules, sin vendor)
COPY . /var/www/html

# Copiar los assets ya compilados desde la etapa de build
COPY --from=build /app/public/build /var/www/html/public/build

# Copiar configuración de nginx personalizada
COPY config/nginx/default.conf /etc/nginx/conf.d/default.conf

# Dar permisos de ejecución al script de entrada
RUN chmod +x /var/www/html/scripts/entrypoint.sh

# Crear directorios necesarios para evitar errores de caché
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

ENV COMPOSER_ALLOW_SUPERUSER 1

# Instalar dependencias de PHP (sin scripts para evitar errores de artisan durante el build)
RUN composer install --no-dev --no-interaction --optimize-autoloader --no-scripts

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 0
ENV REAL_IP_HEADER 1
ENV SKIP_COMPOSER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/var/www/html/scripts/entrypoint.sh"]
