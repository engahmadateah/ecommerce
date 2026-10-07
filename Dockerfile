# ---- 1) build the CSS/JS ----
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---- 2) PHP dependencies ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

# ---- 3) the app ----
FROM php:8.3-apache
RUN apt-get update && apt-get install -y --no-install-recommends \
        libzip-dev libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev libicu-dev libonig-dev unzip default-mysql-client \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install pdo_mysql gd zip intl bcmath opcache pcntl exif \
    && pecl install redis && docker-php-ext-enable redis \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
