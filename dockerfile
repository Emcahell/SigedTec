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

# Copiar el código y los assets ya compilados
COPY . /var/www/html
COPY --from=build /app/public/build /var/www/html/public/build

ENV COMPOSER_ALLOW_SUPERUSER 1

# Instalar dependencias de PHP
RUN composer install --no-dev --no-interaction --optimize-autoloader

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 0
ENV REAL_IP_HEADER 1
ENV SKIP_COMPOSER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]
