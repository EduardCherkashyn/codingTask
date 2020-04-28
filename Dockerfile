# Basic setup to be used by other setups #
FROM alpine:3.8 AS base

WORKDIR /app

RUN adduser -D -g '' www-data

RUN apk --update add \
  nginx \
  git \
  php7-fpm \
  php7-pdo \
  php7-pdo_mysql \
  php7-zip \
  php7-json \
  php7-openssl \
  php7-mcrypt \
  php7-ctype \
  php7-fileinfo\
  php7-zlib \
  php7-curl \
  php7-phar \
  php7-xml \
  php7-opcache \
  php7-intl \
  php7-bcmath \
  php7-dom \
  php7-xmlreader \
  php7-mbstring \
  php7-iconv \
  php7-simplexml \
  php7-tokenizer \
  php7-session \
  php7-xmlwriter \
  curl \
  supervisor \
  nodejs \
  nodejs-npm \
  && npm install yarn -g  \
  && rm -rf /var/cache/apk/* \
  &&  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && chown www-data:www-data /app \
  && mkdir -p /run/nginx

USER www-data

RUN composer global require "hirak/prestissimo:^0.3" --no-update \
    && composer global install --no-dev

USER root

EXPOSE 80

CMD ["/usr/bin/supervisord"]

# Setup for local development only #
FROM base AS develop

RUN apk update \
    && echo "y" | apk add alpine-sdk mc vim git wget php7-dev autoconf \
    && wget https://xdebug.org/files/xdebug-2.6.0.tgz \
    && tar -xvzf xdebug-2.6.0.tgz \
    && cd xdebug-2.6.0 \
    && phpize \
    && ./configure \
    && make && make install \
    && cd .. \
    # && [ -d "xdebug-2.6.0" ] && rm -rf ./xdebug-2.6.0 \
    # && [ -f "xdebug-2.6.0.tgz" ] && rm xdebug-2.6.0.tgz \
    # && [ -f "package.xml" ] && rm package.xml \ \
    && php -i | grep xdebug \

    # ast php extension for phan
    && wget https://github.com/nikic/php-ast/archive/v0.1.6.tar.gz \
    && tar -xvzf v0.1.6.tar.gz \
    && cd php-ast-0.1.6 \
    && phpize \
    && ./configure \
    && make && make install \
    && cd ..

# Setup for CI #
FROM base AS testing

WORKDIR /app

RUN mkdir -p /etc/nginx \
    &&  mkdir -p /var/run/php-fpm \
    &&  mkdir -p /var/log/supervisor \
    &&  rm /etc/nginx/nginx.conf

ADD docker/nginx/nginx.conf /etc/nginx/nginx.conf
ADD docker/nginx/conf.d /etc/nginx/conf.d
ADD docker/supervisor/supervisor.ini /etc/supervisor.d/supervisor.ini

ADD . /app
ADD ./public/index.php.dist /app/public/index.php
ADD ./.env /app/.env

RUN composer install --no-interaction --no-progress \
    &&  bower update --allow-root \
    &&  yarn install \
    &&  ./node_modules/.bin/encore dev

RUN mkdir -p /app/var/cache /app/var/logs && chmod -R 777 /app/var/cache /app/var/logs










