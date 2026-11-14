FROM fflo/nginx-php-fpm:latest

# Instalar Node.js y npm usando apk (gestor de Alpine)
RUN apk add --no-cache nodejs npm

WORKDIR /var/www/html

# Copiar archivos necesarios para instalar dependencias primero (mejor caché)
COPY package*.json ./
RUN npm install

# Copiar el resto del código
COPY . .

# Compilar assets
RUN npm run build

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer install --no-dev --no-interaction --optimize-autoloader --working-dir=/var/www/html

ENV WEBROOT /var/www/html/public
ENV PHP_ERRORS_STDERR 1
ENV RUN_SCRIPTS 0
ENV REAL_IP_HEADER 1
ENV SKIP_COMPOSER 1

ENV APP_ENV production
ENV APP_DEBUG false
ENV LOG_CHANNEL stderr

CMD ["/start.sh"]
