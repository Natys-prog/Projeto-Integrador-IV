# ==========================================
# DOCKERFILE OTIMIZADO - Multi-stage Build
# ==========================================

# =====================================
# Stage 1: Composer Dependencies
# =====================================
FROM composer:2.6 AS composer-stage

WORKDIR /app

# Copy composer files for better caching
COPY laravel-app/composer.json laravel-app/composer.lock* ./

# Install dependencies without dev packages and with optimizations
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    && composer clear-cache

# =====================================
# Stage 2: Node.js Dependencies (if needed)
# =====================================
FROM node:18-alpine AS node-stage

WORKDIR /app

# Copy package files
COPY laravel-app/package*.json ./

# Install Node dependencies
RUN npm ci --only=production && npm cache clean --force

# =====================================
# Stage 3: Production Runtime
# =====================================
FROM php:8.2-apache AS production

# Install system dependencies in one layer
RUN apt-get update && apt-get install -y \
    # Image processing
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    # Text processing  
    libonig-dev \
    libxml2-dev \
    # Archive handling
    libzip-dev \
    # Utilities
    unzip \
    git \
    curl \
    # Database clients
    default-mysql-client \
    # Process management
    supervisor \
    # Cleanup in same layer
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
    && a2enmod rewrite headers \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* /var/tmp/*

# Configure PHP for production
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=256'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=20000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'realpath_cache_size=4096K'; \
    echo 'realpath_cache_ttl=600'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Copy PHP configuration
RUN { \
    echo 'memory_limit=256M'; \
    echo 'upload_max_filesize=50M'; \
    echo 'post_max_size=50M'; \
    echo 'max_execution_time=300'; \
    echo 'date.timezone=America/Sao_Paulo'; \
    echo 'log_errors=On'; \
    echo 'error_log=/var/log/php_errors.log'; \
    } > /usr/local/etc/php/conf.d/laravel.ini

# Configure Apache
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy Composer dependencies from composer stage
COPY --from=composer-stage /app/vendor ./vendor/

# Copy application code
COPY laravel-app/ .

# Create application structure and set permissions in one layer
RUN mkdir -p \
        storage/logs \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache \
        storage/app/public \
        bootstrap/cache \
    && touch storage/logs/laravel.log \
    && chown -R www-data:www-data . \
    && chmod -R 755 . \
    && chmod -R 775 storage bootstrap/cache \
    && chmod +x artisan

# Copy initialization script
COPY docker/scripts/init-app.sh /usr/local/bin/init-app.sh
RUN chmod +x /usr/local/bin/init-app.sh

# Create supervisor configuration for multi-process management
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/api/health || exit 1

# Non-root user for security
USER www-data

# Expose port
EXPOSE 80

# Use initialization script as entrypoint
ENTRYPOINT ["/usr/local/bin/init-app.sh"]

# Default command
CMD ["apache2-foreground"]