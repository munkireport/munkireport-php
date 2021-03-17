FROM php:7.4-apache

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

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

ENV SITENAME MunkiReport
ENV MODULES ard, bluetooth, disk_report, munkireport, managedinstalls, munkiinfo, network, security, warranty
ENV INDEX_PAGE ""
ENV AUTH_METHODS NOAUTH

COPY . $APP_DIR

WORKDIR $APP_DIR

RUN cp "php/upload.ini" "$PHP_INI_DIR/conf.d/"

RUN ./build/setup_composer.sh

RUN composer install --no-dev && \
    composer dumpautoload -o

RUN mkdir -p app/db

RUN touch app/db/db.sqlite

RUN php please migrate --force

RUN chown -R www-data app/db storage

RUN cp .env.example .env && php please key:generate

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf

RUN sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf

RUN a2enmod rewrite

EXPOSE 80
