FROM php:7.4-fpm

RUN docker-php-ext-install ctype
RUN docker-php-ext-install mysqli 
RUN docker-php-ext-install pdo_mysql
