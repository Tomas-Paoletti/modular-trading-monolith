FROM php:8.3-fpm-alpine

ARG UID=1000
ARG GID=1000


RUN apk add --no-cache icu-libs libzip git unzip \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers icu-dev libzip-dev \
    && docker-php-ext-install pdo_mysql intl bcmath zip pcntl opcache \
    && pecl install redis-6.1.0 \
    && docker-php-ext-enable redis \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh


RUN addgroup -g ${GID} app && adduser -D -u ${UID} -G app app

WORKDIR /var/www/html
USER app

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
