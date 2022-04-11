FROM php:8.0

RUN apt-get update
RUN apt-get install -y libmemcached-dev zlib1g-dev
RUN apt-get install zip -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis
RUN docker-php-ext-enable redis.so


RUN pecl install memcached
RUN docker-php-ext-enable memcached