# the different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target

ARG PHP_VERSION=7.3
ARG NGINX_VERSION=1.15

FROM php:${PHP_VERSION}-fpm-alpine AS symfony_base

# persistent / runtime deps
RUN apk add --no-cache \
		acl \
		file \
		gettext \
		git \
		openssl \
	;

ARG APCU_VERSION=5.1.17

RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		mysql-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip --with-libzip; \
	docker-php-ext-install -j$(nproc) \
		intl \
		pdo_mysql \
		zip \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .app-phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY symfony/docker/settings/php/php.ini /usr/local/etc/php/php.ini
COPY symfony/docker/settings/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY symfony/docker/settings/wait-for-it.sh /usr/local/bin/wait-for-it

RUN chmod +x  /usr/local/bin/wait-for-it
RUN chmod +x /usr/local/bin/docker-entrypoint

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1
# install Symfony Flex globally to speed up download of Composer packages (parallelized prefetching)
RUN set -eux; \
    composer self-update; \
	composer global require "symfony/flex" --prefer-dist --no-progress --no-suggest --classmap-authoritative; \
	composer clear-cache
ENV PATH="${PATH}:/root/.composer/vendor/bin"

WORKDIR /srv/app

# prevent the reinstallation of vendors at every changes in the source code
COPY symfony/composer.json symfony/composer.lock ./

RUN set -eux; \
	composer install --prefer-dist --no-autoloader --no-scripts --no-progress --no-suggest; \
	composer clear-cache

# copy only specifically what we need
COPY symfony/bin bin/
COPY symfony/config config/
COPY symfony/public public/
COPY symfony/src src/
COPY symfony/translations src/translations
COPY symfony/.env .env

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative; \
	composer run-script post-install-cmd; \
	chmod +x bin/console; sync

# build symfony base image
FROM symfony_base as symfony_ci

VOLUME /srv/app/var

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# build symfomy development image
FROM symfony_base as symfony_dev

COPY ./symfony ./

ARG XDEBUG_VERSION=2.7.2
RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-$XDEBUG_VERSION; \
	docker-php-ext-enable xdebug; \
	apk del .build-deps

# Install blackfire extension
# Please note that the Blackfire Probe is dependent on the session module.
# If it isn't present in your install, you will need # to enable it yourself.
RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/alpine/amd64/$version \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp \
    && mv /tmp/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini

VOLUME /srv/app/var

ENTRYPOINT ["docker-entrypoint"]
CMD ["php-fpm"]

# "nginx" stage
# depends on the "php" stage above
FROM nginx:${NGINX_VERSION}-alpine AS nginx_base

COPY symfony/docker/settings/nginx/conf.d/default.conf /etc/nginx/conf.d/default.conf

WORKDIR /srv/app

COPY --from=symfony_base /srv/app/public public/

FROM nginx_base as nginx_dev

FROM nginx_base as nginx_ci
