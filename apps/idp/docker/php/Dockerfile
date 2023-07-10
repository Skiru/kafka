FROM php:8.2.7-fpm-alpine3.18

RUN apk update && apk add --no-cache  \
    $PHPIZE_DEPS sqlite-dev zlib-dev librdkafka-dev && \
    pecl install rdkafka && \
    docker-php-ext-install pdo pdo_sqlite && \
    docker-php-ext-enable rdkafka

RUN echo extension=rdkafka.so >> $PHP_INI_DIR/php.ini-development
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
