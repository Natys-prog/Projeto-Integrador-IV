# Use PHP 8.2 com Apache (corrigido de 8.1 para 8.2)
FROM php:8.2-apache

# Definir diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    libsqlite3-dev \
    nodejs \
    npm

# Limpar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar Apache para Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/laravel-app/public\n\
    <Directory /var/www/html/laravel-app/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copiar arquivos do projeto
COPY . .

# Configurar permissões iniciais
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Navegar para o diretório Laravel
WORKDIR /var/www/html/laravel-app

# Instalar dependências PHP (sem --no-dev primeiro para resolver dependências)
RUN composer install --optimize-autoloader

# Instalar dependências Node.js (verificar se package.json existe)
RUN if [ -f "package.json" ]; then npm install && npm run build; else echo "No package.json found, skipping npm install"; fi

# Configurar Laravel - Copiar .env.example se existir, senão criar básico
RUN if [ -f ".env.example" ]; then cp .env.example .env; else \
    echo "APP_NAME=\"LinkeSupermarket\"\n\
APP_ENV=production\n\
APP_KEY=\n\
APP_DEBUG=false\n\
APP_TIMEZONE=America/Sao_Paulo\n\
APP_URL=http://localhost\n\
\n\
APP_LOCALE=pt_BR\n\
APP_FALLBACK_LOCALE=pt_BR\n\
APP_FAKER_LOCALE=pt_BR\n\
\n\
LOG_CHANNEL=stack\n\
LOG_STACK=single\n\
LOG_DEPRECATIONS_CHANNEL=null\n\
LOG_LEVEL=debug\n\
\n\
DB_CONNECTION=sqlite\n\
DB_DATABASE=/var/www/html/laravel-app/database/database.sqlite\n\
\n\
SESSION_DRIVER=file\n\
SESSION_LIFETIME=120\n\
SESSION_ENCRYPT=false\n\
SESSION_PATH=/\n\
SESSION_DOMAIN=null\n\
\n\
BROADCAST_CONNECTION=log\n\
FILESYSTEM_DISK=local\n\
QUEUE_CONNECTION=database\n\
\n\
CACHE_STORE=file\n\
CACHE_PREFIX=\n\
\n\
MEMCACHED_HOST=127.0.0.1\n\
\n\
REDIS_CLIENT=phpredis\n\
REDIS_HOST=127.0.0.1\n\
REDIS_PASSWORD=null\n\
REDIS_PORT=6379\n\
\n\
MAIL_MAILER=log\n\
MAIL_HOST=127.0.0.1\n\
MAIL_PORT=2525\n\
MAIL_USERNAME=null\n\
MAIL_PASSWORD=null\n\
MAIL_ENCRYPTION=null\n\
# MAIL_FROM_ADDRESS=\"suporte@example.com\"\n\
MAIL_FROM_NAME=\"\${APP_NAME}\"" > .env; fi

# Gerar chave da aplicação
RUN php artisan key:generate

# Criar diretórios necessários
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache} bootstrap/cache

# Criar arquivo de banco SQLite
RUN touch database/database.sqlite

# Configurar permissões finais
RUN chown -R www-data:www-data storage bootstrap/cache database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache \
    && chmod 664 database/database.sqlite

# Executar migrações (com verificação se existem)
RUN php artisan migrate --force || echo "No migrations to run"

# Executar seeders (com verificação se existem)
RUN php artisan db:seed --force || echo "No seeders to run"

# Limpar e otimizar cache
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan cache:clear

# Otimizações para produção
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Configurar permissões finais
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/laravel-app/storage \
    && chmod -R 775 /var/www/html/laravel-app/bootstrap/cache

# Expor porta 80
EXPOSE 80

# Script de inicialização
RUN echo '#!/bin/bash\n\
# Verificar se o banco existe e tem dados\n\
if [ ! -s /var/www/html/laravel-app/database/database.sqlite ]; then\n\
    echo "Inicializando banco de dados..."\n\
    cd /var/www/html/laravel-app\n\
    php artisan migrate --force\n\
    php artisan db:seed --force\n\
fi\n\
\n\
# Iniciar Apache\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

# Comando para iniciar Apache
CMD ["/start.sh"]