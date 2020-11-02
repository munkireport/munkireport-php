FROM php:8.2-apache

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

RUN arch="$(dpkg --print-architecture)" && args="--with-libdir=lib/x86_64-linux-gnu/" && \
    case "$arch" in \
        *arm*) args="" ;; \
    esac && \
    docker-php-ext-configure ldap "$args" && \
    docker-php-ext-install -j$(nproc) curl pdo_mysql soap ldap zip

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp
ENV SITENAME MunkiReport
ENV MODULES ard, bluetooth, disk_report, munkireport, managedinstalls, munkiinfo, network, security, warranty
ENV INDEX_PAGE ""
ENV AUTH_METHODS NOAUTH

COPY . $APP_DIR

WORKDIR $APP_DIR

COPY --from=composer:2.2.6 /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-dev && \
    composer dumpautoload -o

RUN mkdir -p app/db

RUN php please migrate

RUN chown -R www-data app/db

ENV LARAVEL_LOG = /var/munkireport/storage/logs/laravel.log

RUN touch $LARAVEL_LOG && chmod a+w $LARAVEL_LOG

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf

RUN sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf

RUN a2enmod rewrite

EXPOSE 80
