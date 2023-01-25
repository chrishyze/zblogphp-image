FROM chrishyze/extended-php:8.1-fpm

LABEL version="1.0"
LABEL org.opencontainers.image.authors="chrishyze@gmail.com"

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
    chmod -R 0755 /var/www/zblogphp &&\
    cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini &&\
    echo -e '\nextension=gd.so\n\nextension=imagick.so\n\nextension=mysqli.so\n\nextension=opcache.so\n\nextension=pdo_mysql.so\n\nextension=redis.so\n' >> /usr/local/etc/php/php.ini
WORKDIR /var/www/zblogphp
