FROM php:7.4-fpm-alpine

# Install dev dependencies
RUN apk update \
    && apk upgrade --available \
    && apk add --virtual build-deps \
        linux-headers \
        autoconf \
        build-base \
        icu-dev \
        libevent-dev \
        openssl-dev \
        zlib-dev \
        libzip \
        libzip-dev \
        zlib \
        zlib-dev \
        bzip2 \
        git \
        libpng \
        libpng-dev \
        libjpeg \
        libjpeg-turbo-dev \
        libwebp-dev \
        freetype \
        freetype-dev \
        curl \
        wget \
        bash

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) \
    intl \
    gd \
    bcmath \
    sockets \
    zip \
    mysqli pdo pdo_mysql

RUN docker-php-ext-enable pdo_mysql

RUN mkdir -p /app/backend
WORKDIR /app/backend
COPY . /app/backend
COPY ./docker/backend/db.php /app/backend/config/

RUN composer install

#CMD ["php-fpm"]