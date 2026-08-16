FROM php:8.4.24-cli

# Install dependencies and get the RoadRunner binary
RUN apt-get update && apt-get install -y git unzip \
    && docker-php-ext-install sockets \
    && pecl install protobuf \
    && docker-php-ext-enable protobuf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:latest /usr/bin/rr /usr/bin/rr

WORKDIR /var/www/html