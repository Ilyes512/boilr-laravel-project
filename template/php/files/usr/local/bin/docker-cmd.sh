#!/usr/bin/env bash

set -eu

laravel-bootstrap.sh

echo "Starting php-fpm..."
exec php-fpm
