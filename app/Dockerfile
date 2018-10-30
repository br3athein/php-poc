FROM php:7-fpm-alpine

RUN set -x \
    && docker-php-source extract \
    && docker-php-ext-install \
    mysqli \
    pdo \
    pdo_mysql \
    && docker-php-source delete

COPY ./* /app/

ENV APACHE_DOCUMENT_ROOT=/app/public
