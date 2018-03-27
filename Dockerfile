FROM php:7.2-apache

ENV APP_DIR /var/munkireport

RUN apt-get update && \
    apt-get install --no-install-recommends -y libldap2-dev \
    libcurl4-openssl-dev \
    zlib1g-dev \
    libmcrypt-dev \
    libxml2-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN pecl install mcrypt-1.0.1 && docker-php-ext-enable mcrypt

RUN docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install -j$(nproc) curl pdo_mysql soap ldap zip

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV COMPOSER_VERSION 1.6.3

RUN curl -s -f -L -o /tmp/installer.php https://raw.githubusercontent.com/composer/getcomposer.org/b107d959a5924af895807021fcef4ffec5a76aa9/web/installer \
 && php -r " \
    \$signature = '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061'; \
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

COPY . $APP_DIR

WORKDIR $APP_DIR

RUN composer install --no-dev && \
    composer require adldap2/adldap2 --update-no-dev && \
    composer require onelogin/php-saml --update-no-dev && \
    composer dumpautoload -o

COPY docker/docker_config.php config.php

RUN mkdir -p app/db && \
    touch app/db/db.sqlite && \
    chmod -R 777 app/db

RUN php database/migrate.php

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN a2enmod rewrite

EXPOSE 80