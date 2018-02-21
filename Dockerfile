FROM php:7-apache

RUN apt-get update && \
    apt-get install --no-install-recommends -y libldap2-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libmcrypt-dev \
    git \
    libxml2-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install -j$(nproc) curl pdo_mysql soap ldap zip mcrypt

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.5.2

RUN curl -s -f -L -o /tmp/installer.php https://raw.githubusercontent.com/composer/getcomposer.org/da290238de6d63faace0343efbdd5aa9354332c5/web/installer \
 && php -r " \
    \$signature = '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410'; \
    \$hash = hash('SHA384', file_get_contents('/tmp/installer.php')); \
    if (!hash_equals(\$signature, \$hash)) { \
        unlink('/tmp/installer.php'); \
        echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
        exit(1); \
    }" \
 && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
 && composer --ansi --version --no-interaction \
 && rm -rf /tmp/* /tmp/.htaccess
 
ENV MR_SITENAME MunkiReport
ENV MR_MODULES ard, bluetooth, disk_report, munkireport, managedinstalls, munkiinfo, network, security, warranty

RUN mkdir /var/munkireport

WORKDIR /var/munkireport

RUN git clone https://github.com/munkireport/munkireport-php.git . && \
    git checkout -b wip remotes/origin/wip

RUN composer install --no-dev && \
    composer require adldap2/adldap2 --update-no-dev && \
    composer require onelogin/php-saml --update-no-dev && \
    composer dumpautoload -o

COPY docker/docker_config.php config.php

RUN mkdir -p app/db && \
    touch app/db/db.sqlite && \
    chmod -R 777 app/db

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN a2enmod rewrite

EXPOSE 80