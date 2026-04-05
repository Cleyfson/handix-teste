#!/bin/sh
set -e

cd /var/www/html

echo "🔧 Preparing Laravel environment..."

# Garante estrutura (importante por causa do volume)
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Corrige permissões
chown -R appuser:appgroup storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Instala dependências automaticamente
if [ ! -d "vendor" ]; then
    echo "📦 Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

echo "🚀 Starting PHP-FPM..."

exec "$@"