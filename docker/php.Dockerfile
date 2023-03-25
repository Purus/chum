FROM php:8.2-apache
# RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli && docker-php-ext-install pdo_mysql && docker-php-ext-install pdo
# RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql pdo mysqli

RUN apt-get update && apt-get upgrade -y

# WORKDIR /var/www/html

# COPY index.php index.php
# COPY . .
# EXPOSE 80