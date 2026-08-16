FROM php:8.4.24-fpm-alpine3.24

# Install system dependencies and PHP extensions for gRPC
RUN apk update && apk add --no-cache bash build-base curl ca-certificates gcc g++ make libtool autoconf zlib-dev openssl-dev openldap-dev libssh2-dev curl-dev libxml2 zlib libpng libjpeg-turbo libwebp libxpm libzip openssl openldap-clients libssh2 git gettext busybox-extras libcap unzip

RUN pecl install grpc protobuf && docker-php-ext-enable grpc protobuf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html