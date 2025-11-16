{{$dockerTag := "" }}

{{- if eq PhpVersion "8.3"}}
    {{- $dockerTag = "1.4.2" }}
{{- else if eq PhpVersion "8.2"}}
    {{- $dockerTag = "1.4.3" }}
{{- else}}
    {{- $dockerTag = "1.1.2" }}
{{- end -}}

FROM ghcr.io/ilyes512/php{{ PhpVersion | replace "."  "" }}:builder_nodejs-{{$dockerTag}} AS builder

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

# https://hub.docker.com/_/alpine
FROM alpine:3.22.2 AS files

COPY --link ./nginx/files /files/
COPY --link --from=builder /var/www/public/ /files/var/www/public/

RUN chmod -R go-w /files/var/www/public

# https://hub.docker.com/_/nginx
FROM nginx:1.29.3-alpine3.22-slim

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
