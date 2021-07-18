FROM php:7.3-fpm

ENV ACCEPT_EULA=Y

RUN apt-get update && apt-get install -y \
    openssl \
    libssl-dev \
    libcurl4-openssl-dev \
    build-essential \
    zip \
    unzip \
    git \
    curl \
    make \
    pkg-config \
    unixodbc-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN pecl install sqlsrv pdo_sqlsrv mongodb redis \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv mongodb redis

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/php.ini \
    && echo "extension=sqlsrv.so" >> /usr/local/etc/php/php.ini \
    && echo "extension=pdo_sqlsrv.so" >> /usr/local/etc/php/php.ini \
    && echo "extension=redis.so" >> /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN set -x \
addgroup -g 82 -S www-data \
adduser -u 82 -D -S -G www-data www-data

COPY . /src
ADD .env.example /src/.env
WORKDIR /src
RUN chmod -R 777 storage
CMD php -S 0.0.0.0:8000 -t public
