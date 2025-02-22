#!/usr/bin/env sh

set -eu

envsubst '$CGI_HOST $CGI_PORT $CGI_READ_TIMEOUT $NGINX_PUBLIC_DIR $NGINX_CLIENT_MAX_BODY_SIZE' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf
envsubst '$NGINX_GZIP' < /etc/nginx/nginx.template > /etc/nginx/nginx.conf

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- nginx "$@"
fi

exec "$@"
