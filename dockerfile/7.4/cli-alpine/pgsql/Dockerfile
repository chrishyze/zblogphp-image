FROM php:7.4-cli-alpine

LABEL version="1.0"
LABEL org.opencontainers.image.authors="chrishyze@gmail.com"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -ex &&\
    apk update &&\
    apk add --no-cache wget unzip  &&\
    mkdir -p /usr/local/bin &&\
    wget -O /usr/local/bin/composer https://getcomposer.org/download/latest-stable/composer.phar &&\
    chmod +x /usr/local/bin/composer &&\
    mkdir -p /usr/local/src &&\
    wget -O /usr/local/src/zblogphp.zip https://github.com/zblogcn/zblogphp/releases/latest/download/zblogphp.zip &&\
    mv /var/www/html /var/www/zblogphp &&\
    unzip -o /usr/local/src/zblogphp.zip -d /var/www/zblogphp &&\
    chown -R www-data:www-data /var/www/zblogphp &&\
    chmod -R 0755 /var/www/zblogphp
RUN install-php-extensions gd imagick mcrypt opcache pdo_pgsql pgsql redis

WORKDIR /var/www/zblogphp
