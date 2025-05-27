{{$dockerTag := "" }}

{{- if eq PhpVersion "8.3"}}
    {{- $dockerTag = "1.4.1" }}
{{- else if eq PhpVersion "8.2"}}
    {{- $dockerTag = "1.3.0" }}
{{- else}}
    {{- $dockerTag = "1.0.2" }}
{{- end -}}

# syntax=docker/dockerfile:1

FROM ghcr.io/ilyes512/php{{ PhpVersion | replace "."  "" }}:builder_nodejs-{{$dockerTag}} AS builder-composer-cache

ARG COMPOSER_AUTH

COPY --link ./composer.json ./composer.lock /var/www/

RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-progress --prefer-dist --no-scripts

COPY --link ./package.json ./package-lock.json ./vite.config.js /var/www/
COPY --link ./resources /var/www/resources/
COPY --link ./public /var/www/public/

RUN --mount=type=cache,target=/root/.npm \
    npm ci

COPY --link ./ /var/www/

RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-progress --prefer-dist

RUN npm run build

RUN rm -rf package.json package-lock.json vite.config.js resources node_modules

FROM ghcr.io/ilyes512/php{{ PhpVersion | replace "."  "" }}:builder-{{$dockerTag}} AS builder-files-cache

ARG COMPOSER_AUTH
ARG TARGETARCH

COPY --link ./ /var/www
COPY --link --from=builder-composer-cache /var/www /var/www/

RUN chown -R www-data: /var/www/storage /var/www/bootstrap/cache
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --no-progress --prefer-dist

ENV FIXUID_VERSION=0.6.0

RUN curl -fsSLo /tmp/fixuid.tar.gz "https://github.com/boxboat/fixuid/releases/download/v$FIXUID_VERSION/fixuid-${FIXUID_VERSION}-linux-${TARGETARCH}.tar.gz" \
    && tar -xf /tmp/fixuid.tar.gz -C /usr/local/bin fixuid

FROM ghcr.io/ilyes512/php{{ PhpVersion | replace "."  "" }}:builder_nodejs-{{$dockerTag}} AS builder

ARG USER=code
ARG GROUP=code
ARG USER_UID=1000
ARG USER_GID=1000

ENV PATH="$PATH:/var/www/vendor/bin"

RUN groupadd --gid "$USER_GID" "$USER" \
    && useradd --uid "$USER_UID" --gid "$USER_GID" --create-home "$USER" --shell /bin/bash \
    && mkdir -p \
        /etc/fixuid \
        "/home/$USER/.composer" \
        "/home/$USER/.npm" \
    && chown -R "$USER_UID":"$USER_GID" \
        "/home/$USER/.composer" \
        "/home/$USER/.npm" \
    && printf "user: $USER\ngroup: $GROUP\npaths:\n  - /var/www\n  - /home/$USER\n  - /home/$USER/.composer\n  - /home/$USER/.npm\n\n" > /etc/fixuid/config.yml

COPY --link --from=builder-files-cache /var/www /var/www
COPY --link ./php/files/ /
COPY --link --from=builder-files-cache /usr/local/bin/fixuid /usr/local/bin/fixuid

ENV PHP_APP_ENV=local
ENV PHP_COMPOSER_INSTALL_DEV=true

RUN apt-get update \
    && apt-get install --assume-yes --no-install-recommends \
        mariadb-client \
    && apt-get autoremove --assume-yes \
    && apt-get clean --assume-yes \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/*

USER code

ENV COREPACK_DEFAULT_TO_LATEST=0

RUN corepack install \
    && corepack enable

USER root

ENTRYPOINT ["fixuid", "-q"]
CMD ["docker-cmd.sh"]

FROM builder AS runtime-files-cache

ARG COMPOSER_AUTH

RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --classmap-authoritative --no-dev --no-progress --prefer-dist

RUN rm -rf \
    package.json \
    package-lock.json \
    vite.config.js \
    phpunit.xml* \
    tests \
    .dockerignore \
    nginx \
    php

FROM ghcr.io/ilyes512/php{{ PhpVersion | replace "."  "" }}:runtime-{{$dockerTag}} AS runtime

COPY --link --from=runtime-files-cache /var/www /var/www

ENV PHP_APP_ENV=local
ENV PHP_COMPOSER_INSTALL_DEV=false

ENTRYPOINT ["docker-php-entrypoint"]
CMD ["docker-cmd.sh"]
