FROM node:20.10 as frontend
COPY . /usr/src/app
WORKDIR /usr/src/app
RUN npm install && npm run build

FROM php:8.3-apache
MAINTAINER MunkiReport PHP Team <munkireport@noreply.users.github.com>
LABEL architecture="x86_64" \
	  io.k8s.display-name="MunkiReport" \
	  io.k8s.description="" \
	  License="MIT" \
	  version="v6.0.0-alpha"

#RUN arch="$(dpkg --print-architecture)" && args="--with-libdir=lib/x86_64-linux-gnu/" && \
#    case "$arch" in \
#        *arm*) args="" ;; \
#    esac && \
#    docker-php-ext-configure ldap "$args" && \
#    docker-php-ext-install -j$(nproc) curl pdo_mysql soap ldap zip

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
    docker-php-ext-install -j$(nproc) curl pdo_mysql soap ldap zip opcache

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /tmp

ENV APP_DIR /var/munkireport
ENV APACHE_DOCUMENT_ROOT /var/munkireport/public
ENV AUTH_METHODS LOCAL
ENV APP_URL http://localhost:8080
ENV LOG_CHANNEL stderr

COPY --chown=www-data:www-data . $APP_DIR
COPY --chown=www-data:www-data --from=frontend /usr/src/app/public/ $APACHE_DOCUMENT_ROOT/
WORKDIR $APP_DIR

COPY --from=composer:2.6 /usr/bin/composer /usr/local/bin/composer
COPY build/composer-local.example.json $APP_DIR/composer.local.json

USER www-data

RUN composer install --no-dev && \
    composer dumpautoload -o && \
    composer clear-cache

# You should not use this directory for SQLite as Laravel defines one. However, it is provided for backwards compatibility.
RUN mkdir -p app/db && \
	touch app/db/db.sqlite

RUN php please migrate

RUN chown -R www-data app/db storage

RUN cp .env.example .env
RUN php please ziggy:generate --types

RUN rm -rf /var/www/html && \
    ln -s /var/munkireport/public /var/www/html

RUN sed -i 's/ServerTokens OS/ServerTokens Prod/' /etc/apache2/conf-available/security.conf

RUN sed -i 's/ServerSignature On/ServerSignature Off/' /etc/apache2/conf-available/security.conf
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN a2enmod rewrite
COPY build/docker-php-entrypoint /usr/local/bin/docker-php-entrypoint

EXPOSE 8080
