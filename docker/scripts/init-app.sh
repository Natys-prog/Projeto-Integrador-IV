#!/bin/bash

# ==========================================
# Laravel Application Initialization Script
# ==========================================

set -euo pipefail

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration from environment variables
APP_ENV=${APP_ENV:-production}
DB_HOST=${DB_HOST:-database}
DB_PORT=${DB_PORT:-3306}
DB_DATABASE=${DB_DATABASE:-laravel_epi}
DB_USERNAME=${DB_USERNAME:-laravel}
DB_PASSWORD=${DB_PASSWORD}

# Seeder configuration
RUN_MIGRATIONS=${RUN_MIGRATIONS:-true}
RUN_SEEDERS=${RUN_SEEDERS:-false}
SEEDER_CLASS=${SEEDER_CLASS:-DatabaseSeeder}
FORCE_SEED=${FORCE_SEED:-false}

# Advanced options
OPTIMIZE_APP=${OPTIMIZE_APP:-true}
CLEAR_CACHE=${CLEAR_CACHE:-true}
STORAGE_LINK=${STORAGE_LINK:-true}

# Logging function
log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] ✅${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] ⚠️${NC} $1"
}

log_error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ❌${NC} $1"
}

# Wait for database connection
wait_for_database() {
    log "Waiting for database connection at ${DB_HOST}:${DB_PORT}..."
    
    max_attempts=30
    attempt=0
    
    while [ $attempt -lt $max_attempts ]; do
        if php artisan db:show --database=mysql > /dev/null 2>&1; then
            log_success "Database connection established!"
            return 0
        fi
        
        attempt=$((attempt + 1))
        log "Attempt $attempt/$max_attempts - Waiting for database..."
        sleep 2
    done
    
    log_error "Failed to connect to database after $max_attempts attempts"
    exit 1
}

# Generate application key if not set
generate_app_key() {
    if [ -z "${APP_KEY:-}" ] || [ "$APP_KEY" = "base64:CHANGEME" ]; then
        log "Generating application key..."
        php artisan key:generate --force
        log_success "Application key generated"
    else
        log "Application key already set"
    fi
}

# Clear application cache
clear_application_cache() {
    if [ "$CLEAR_CACHE" = "true" ]; then
        log "Clearing application cache..."
        php artisan cache:clear --quiet || true
        php artisan config:clear --quiet || true
        php artisan route:clear --quiet || true
        php artisan view:clear --quiet || true
        log_success "Application cache cleared"
    fi
}

# Run database migrations
run_migrations() {
    if [ "$RUN_MIGRATIONS" = "true" ]; then
        log "Running database migrations..."
        
        if [ "$FORCE_SEED" = "true" ]; then
            php artisan migrate:fresh --force
            log_success "Database recreated with fresh migrations"
        else
            php artisan migrate --force
            log_success "Database migrations completed"
        fi
    else
        log "Skipping database migrations (RUN_MIGRATIONS=false)"
    fi
}

# Run database seeders
run_seeders() {
    if [ "$RUN_SEEDERS" = "true" ]; then
        log "Running database seeders..."
        log "Seeder class: $SEEDER_CLASS"
        
        if [ "$SEEDER_CLASS" = "DatabaseSeeder" ]; then
            php artisan db:seed --force
        else
            php artisan db:seed --class="$SEEDER_CLASS" --force
        fi
        
        log_success "Database seeding completed"
    else
        log "Skipping database seeders (RUN_SEEDERS=false)"
    fi
}

# Create storage link
create_storage_link() {
    if [ "$STORAGE_LINK" = "true" ]; then
        if [ ! -L "public/storage" ]; then
            log "Creating storage link..."
            php artisan storage:link
            log_success "Storage link created"
        else
            log "Storage link already exists"
        fi
    fi
}

# Optimize application
optimize_application() {
    if [ "$OPTIMIZE_APP" = "true" ]; then
        log "Optimizing application..."
        php artisan config:cache --quiet
        php artisan route:cache --quiet
        php artisan view:cache --quiet
        
        # Only optimize autoloader in production
        if [ "$APP_ENV" = "production" ]; then
            composer dump-autoload --optimize --quiet
        fi
        
        log_success "Application optimized"
    fi
}

# Set proper permissions
set_permissions() {
    log "Setting proper permissions..."
    
    # Ensure storage and cache directories are writable
    chmod -R 775 storage bootstrap/cache
    
    # Ensure log files are writable
    touch storage/logs/laravel.log
    chmod 664 storage/logs/laravel.log
    
    log_success "Permissions set correctly"
}

# Health check endpoint
setup_health_check() {
    log "Setting up health check..."
    
    # Create a simple health check route if it doesn't exist
    if ! grep -q "health" routes/api.php; then
        cat >> routes/api.php << 'EOF'

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'database' => \DB::connection()->getPdo() ? 'connected' : 'disconnected'
    ]);
});
EOF
        log_success "Health check endpoint created"
    else
        log "Health check endpoint already exists"
    fi
}

# Main initialization function
main() {
    log "🚀 Starting Laravel application initialization..."
    log "Environment: $APP_ENV"
    log "Database: ${DB_USERNAME}@${DB_HOST}:${DB_PORT}/${DB_DATABASE}"
    log "Migrations: $RUN_MIGRATIONS"
    log "Seeders: $RUN_SEEDERS"
    
    # Wait for dependencies
    wait_for_database
    
    # Generate app key
    generate_app_key
    
    # Clear cache first
    clear_application_cache
    
    # Database operations
    run_migrations
    run_seeders
    
    # Application setup
    create_storage_link
    set_permissions
    setup_health_check
    
    # Final optimizations
    optimize_application
    
    log_success "🎉 Laravel application initialization completed successfully!"
    log "Starting main application process..."
    
    # Execute the main command passed as arguments
    exec "$@"
}

# Run main function with all arguments
main "$@"