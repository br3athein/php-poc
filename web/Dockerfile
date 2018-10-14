FROM php:7.2.2-apache

RUN set -x \
  && docker-php-source extract \
  && docker-php-ext-install \
  mysqli \
  && docker-php-source delete

COPY ./php /var/www/html
