# Copied from https://github.com/nfqakademija/docker/blob/master/php/Dockerfile

FROM php:7.3.3-fpm

LABEL maintainer "Aurelijus Banelis <aurelijus@banelis.lt>"

WORKDIR /php

# Get composer: https://getcomposer.org/download/
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN ln -s /php/composer.phar /usr/bin/composer

# Install depenedencies and PHP extensions
RUN apt-get update \
 && apt-get install -y ffmpeg libzip-dev bash-completion procps nano libicu-dev g++ libpng-dev libcurl4-gnutls-dev git wget gzip unzip \
 && docker-php-ext-install -j$(nproc) zip mysqli pdo_mysql intl calendar exif gd gettext pcntl sockets opcache \
 && rm -rf /var/lib/apt/lists/*

# xDebug helpers (do not use this in real production)
ADD enable_xdebug.sh /enable_xdebug.sh
ADD disable_xdebug.sh /disable_xdebug.sh
RUN pecl install xdebug-2.7.0 && \
    chmod +x /enable_xdebug.sh && \
    chmod +x /disable_xdebug.sh && \
    touch /usr/local/etc/php/conf.d/custom-xdebug.ini && \
    chmod 777 /usr/local/etc/php/conf.d/custom-xdebug.ini

# Add Symfony extension
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv ~/.symfony/bin/symfony /usr/local/bin/symfony

# Not root user
RUN useradd -c 'PHP user' -m -d /home/php -s /bin/bash php
USER php
ENV HOME /home/php

# Configure git, so there would not be errors running "symfony new my_project"
RUN git config --global user.email "docker.fake.user@example.com" && git config --global user.name "Docker fake user"

# xDebug configuration
ENV PHP_IDE_CONFIG serverName=nfqKickStartDocker

ADD configs/custom.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /code
VOLUME /code