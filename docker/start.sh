#!/usr/bin/env bash

set -e

cd /var/www/html
composer install

mkdir -p /var/www/html/var/log
mkdir -p /var/www/html/cache

apache2-foreground
