#!/bin/sh
set -e

cd /var/www/html

# Only the web container runs migrations (set RUN_MIGRATIONS=1 on it).
if [ "$RUN_MIGRATIONS" = "1" ]; then
    php artisan migrate --force
    php artisan storage:link 2>/dev/null || true
fi

# Faster boot: cache config, routes and views from the real environment.
php artisan optimize

exec "$@"
