FROM node:lts as frontend
COPY . /usr/src/app
WORKDIR /usr/src/app
RUN npm install && npm run production

FROM php:8.2-apache
MAINTAINER MunkiReport PHP Team <munkireport@noreply.users.github.com>
LABEL architecture="x86_64" \
	  io.k8s.display-name="MunkiReport" \
	  io.k8s.description="" \
	  License="MIT" \
	  version="v6.0.0-alpha"

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
COPY --from=frontend /usr/src/app/public/* $APACHE_DOCUMENT_ROOT/
WORKDIR $APP_DIR

COPY --from=composer:2.2.6 /usr/bin/composer /usr/local/bin/composer

RUN composer install --no-dev && \
    composer dumpautoload -o

RUN mkdir -p app/db

RUN php please migrate

RUN chown -R www-data app/db storage

RUN cp .env.example .env && php please key:generate

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf

RUN sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf

RUN a2enmod rewrite

EXPOSE 80
