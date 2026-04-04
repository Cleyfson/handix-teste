#!/bin/sh
set -e

# Fix storage and cache permissions so PHP-FPM can write regardless of
# the host user who cloned the repository.
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache 2>/dev/null || true

exec "$@"
