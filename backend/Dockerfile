FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpq-dev \
    zip \
    unzip \
    icu-dev \
    libzip-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    intl \
    opcache \
    zip

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 www && adduser -u 1000 -G www -s /bin/sh -D www

WORKDIR /var/www/backend

COPY --chown=www:www . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

RUN chown -R www:www /var/www/backend

USER www

EXPOSE 9000

CMD ["php-fpm"]
