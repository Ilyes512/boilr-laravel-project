FROM ghcr.io/ilyes512/php84:builder_nodejs-1.0.0 AS builder

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

FROM alpine:3.21.3 AS files

COPY --link ./nginx/files /files/
COPY --link --from=builder /var/www/public/ /files/var/www/public/

RUN chmod -R go-w /files/var/www/public

FROM nginx:1.27.4-alpine3.21-slim

ARG NGINX_PUBLIC_DIR=/var/www/public
ENV NGINX_PUBLIC_DIR $NGINX_PUBLIC_DIR

ENV CGI_PORT 9000
ENV CGI_HOST php
ENV CGI_READ_TIMEOUT 300
ENV NGINX_GZIP off
ENV NGINX_CLIENT_MAX_BODY_SIZE 21M

WORKDIR $NGINX_PUBLIC_DIR

COPY --link --from=files /files /

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["nginx"]
