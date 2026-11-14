FROM fflo/nginx-php-fpm:latest

# Instalar Node.js para compilar assets
RUN apt-get update && apt-get install -y nodejs npm

# Compilar assets
RUN npm install && npm run build

COPY . /var/www/html

WORKDIR /var/www/html

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
