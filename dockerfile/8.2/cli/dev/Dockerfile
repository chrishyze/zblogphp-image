FROM php:8.2-cli

LABEL version="1.0"
LABEL org.opencontainers.image.authors="chrishyze@gmail.com"

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN set -ex &&\
    apt-get update &&\
    apt-get install -y wget unzip  &&\
    apt-get autoclean &&\
    mkdir -p /usr/local/bin &&\
    wget -O /usr/local/bin/composer https://getcomposer.org/download/latest-stable/composer.phar &&\
    chmod +x /usr/local/bin/composer &&\
    mkdir -p /usr/local/src &&\
    wget -O /usr/local/src/zblogphp.zip https://github.com/zblogcn/zblogphp/releases/latest/download/zblogphp.zip &&\
    mv /var/www/html /var/www/zblogphp &&\
    unzip -o /usr/local/src/zblogphp.zip -d /var/www/zblogphp &&\
    chown -R www-data:www-data /var/www/zblogphp &&\
    chmod -R 0755 /var/www/zblogphp
RUN install-php-extensions amqp apcu bcmath bz2 csv event exif gd gnupg grpc imagick imap ldap memcached mongodb mysqli oauth opcache pcntl pdo_mysql pdo_pgsql pgsql protobuf redis sockets ssh2 swoole xdebug yaml zip zstd

WORKDIR /var/www/zblogphp
