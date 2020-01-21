FROM php:7.2.16-fpm

# Essentials
RUN apt-get update && buildDeps="libpq-dev libzip-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev openssh-server libxrender1 libfontconfig1 libxext6 unzip" && apt-get install -y $buildDeps git nano wget --no-install-recommends
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ && \
    docker-php-ext-install pdo pdo_mysql zip bcmath gd

# OPcache
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

RUN apt-get update && \
    apt-get install -y libxml2-dev && \
    docker-php-ext-install xml

RUN apt-get install -y zlib1g-dev libicu-dev g++ \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

# Composer
RUN wget https://getcomposer.org/composer.phar && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer

# Symfony installer
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony

# Configuration files
COPY docker/php/config/*.ini /usr/local/etc/php/conf.d/
COPY docker/php/config/dev/*.ini /usr/local/etc/php/dev-conf.d/

WORKDIR /app
