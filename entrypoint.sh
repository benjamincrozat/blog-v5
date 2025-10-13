#!/usr/bin/env sh
set -e

php artisan optimize

exec supervisord -c /etc/supervisord.conf
