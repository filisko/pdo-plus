FROM php:7.0-cli
RUN pecl install xdebug-2.9.8 \
    && docker-php-ext-enable xdebug

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        sqlite3 \
        libsqlite3-dev \
        && docker-php-ext-install zip pdo_sqlite

RUN docker-php-ext-enable pdo_sqlite

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
