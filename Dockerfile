FROM php:8.3-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libsqlite3-dev unzip \
    && docker-php-ext-install pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts \
    --optimize-autoloader

COPY . .

RUN composer dump-autoload --no-dev --optimize --no-scripts \
    && php artisan package:discover --ansi \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf \
    && mkdir -p /var/lib/sims storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && touch /var/lib/sims/database.sqlite \
    && chown -R www-data:www-data /var/lib/sims storage bootstrap/cache

ENV APP_ENV=production \
    APP_DEBUG=false \
    DB_CONNECTION=sqlite \
    DB_DATABASE=/var/lib/sims/database.sqlite

VOLUME ["/var/lib/sims"]
EXPOSE 80

CMD ["apache2-foreground"]
