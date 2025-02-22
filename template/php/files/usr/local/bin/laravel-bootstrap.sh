#!/usr/bin/env bash

set -eu

echo "Running container in $PHP_APP_ENV env"

if [ "$PHP_APP_ENV" != local -a "$PHP_APP_ENV" != testing ]; then
    echo "Running Laravel cache commands..."
    php artisan optimize:clear
    php artisan package:discover
    php artisan optimize
    php artisan view:cache
    php artisan event:cache
fi

if [ -z "${KUBERNETES_SERVICE_HOST+x}" ] && [ "$PHP_APP_ENV" = local -o "$PHP_APP_ENV" = testing ]; then
    echo "Fixing storage and cache permissions..."
    chmod -R a+w storage bootstrap/cache
fi

if [ "$PHP_COMPOSER_INSTALL_DEV" = true ] && [ "$PHP_APP_ENV" = local -o "$PHP_APP_ENV" = testing ]; then
    echo "Installing dev dependencies..."
    composer install --no-progress --prefer-dist --no-interaction --no-ansi
fi
