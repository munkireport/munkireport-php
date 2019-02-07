FROM php:7.3-apache

ENV APP_DIR /var/munkireport

RUN apt-get update && \
    apt-get install --no-install-recommends -y libldap2-dev \
    libcurl4-openssl-dev \
    libzip-dev \
    unzip \
    zlib1g-dev \
    libxml2-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install -j$(nproc) curl pdo_mysql soap ldap zip

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.8.0

RUN curl --silent --fail --location --retry 3 --output /tmp/installer.php --url https://raw.githubusercontent.com/composer/getcomposer.org/72bb6f65aa902c76c7ca35514f58cf79a293657d/web/installer \
 && php -r " \
    \$signature = '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8'; \
    \$hash = hash('sha384', file_get_contents('/tmp/installer.php')); \
    if (!hash_equals(\$signature, \$hash)) { \
        unlink('/tmp/installer.php'); \
        echo 'Integrity check failed, installer is either corrupt or worse.' . PHP_EOL; \
        exit(1); \
    }" \
 && php /tmp/installer.php --no-ansi --install-dir=/usr/bin --filename=composer --version=${COMPOSER_VERSION} \
 && composer --ansi --version --no-interaction \
 && rm -rf /tmp/* /tmp/.htaccess

ENV SITENAME MunkiReport
ENV MODULES ard, bluetooth, disk_report, munkireport, managedinstalls, munkiinfo, network, security, warranty
ENV INDEX_PAGE ""
ENV AUTH_METHODS NOAUTH

COPY . $APP_DIR

WORKDIR $APP_DIR

RUN composer install --no-dev && \
    composer dumpautoload -o

RUN mkdir -p app/db && \
    touch app/db/db.sqlite && \
    chmod -R 777 app/db

RUN php database/migrate.php

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN a2enmod rewrite

EXPOSE 80
