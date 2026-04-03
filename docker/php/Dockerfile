FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    bash \
    curl \
    git \
    unzip \
    oniguruma-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    $PHPIZE_DEPS

# PHP extensions required by Laravel
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    xml \
    bcmath \
    intl \
    opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Create non-root user matching host uid/gid pattern
RUN addgroup -g 1000 appgroup && adduser -u 1000 -G appgroup -s /bin/bash -D appuser

USER appuser
