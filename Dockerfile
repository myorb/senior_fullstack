FROM php:7.4.6-fpm

RUN apt-get update && \
    apt-get install -y \
    zlib1g-dev \
    libicu-dev \
    git \
    zip

RUN docker-php-ext-install intl
RUN docker-php-ext-install ctype
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-install pdo_mysql
