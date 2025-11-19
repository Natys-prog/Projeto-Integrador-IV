#!/bin/bash

# =================================
# Laravel Project Setup Script
# =================================

set -e  # Exit on any error

echo "🚀 Starting Laravel Project Setup..."
echo "===================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if we're in the right directory
if [ ! -f "laravel-app/artisan" ]; then
    print_error "Laravel artisan file not found. Please run this script from the project root directory."
    exit 1
fi

print_status "Navigating to Laravel directory..."
cd laravel-app

# Check for required tools
print_status "Checking for required tools..."

# Check PHP
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed or not in PATH"
    print_status "Please install PHP 8.1 or higher"
    exit 1
else
    PHP_VERSION=$(php -v | head -n1 | cut -d' ' -f2 | cut -d'.' -f1,2)
    print_success "PHP $PHP_VERSION found"
fi

# Check Composer
if ! command -v composer &> /dev/null; then
    print_warning "Composer not found. Installing Composer..."
    
    # Download and install Composer
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer
    sudo chmod +x /usr/local/bin/composer
    
    if command -v composer &> /dev/null; then
        print_success "Composer installed successfully"
    else
        print_error "Failed to install Composer"
        exit 1
    fi
else
    print_success "Composer found"
fi

# Check Node.js
if ! command -v node &> /dev/null; then
    print_warning "Node.js not found. Installing Node.js..."
    
    # Install Node.js using NodeSource repository
    curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
    sudo apt-get install -y nodejs
    
    if command -v node &> /dev/null; then
        print_success "Node.js installed successfully"
    else
        print_error "Failed to install Node.js"
        exit 1
    fi
else
    NODE_VERSION=$(node -v)
    print_success "Node.js $NODE_VERSION found"
fi

# Check npm
if ! command -v npm &> /dev/null; then
    print_error "npm not found. Please install npm"
    exit 1
else
    NPM_VERSION=$(npm -v)
    print_success "npm $NPM_VERSION found"
fi

print_status "Installing PHP dependencies with Composer..."
composer install --no-dev --optimize-autoloader

print_status "Installing Node.js dependencies..."
npm install

print_status "Building frontend assets..."
npm run build

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    print_status "Generating application key..."
    php artisan key:generate
else
    print_success "Application key already exists"
fi

# Set proper permissions
print_status "Setting proper file permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Clear and cache config
print_status "Optimizing Laravel..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configurations for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

print_success "✅ Setup completed successfully!"
echo ""
echo "🎉 Your Laravel application is ready to run!"
echo ""
echo "To start the development server:"
echo "  cd laravel-app"
echo "  php artisan serve"
echo ""
echo "To access your application:"
echo "  http://127.0.0.1:8000"
echo ""
echo "Database configuration:"
echo "  Host: $(grep DB_HOST .env | cut -d'=' -f2)"
echo "  Port: $(grep DB_PORT .env | cut -d'=' -f2)"
echo "  Database: $(grep DB_DATABASE .env | cut -d'=' -f2)"
echo ""
echo "Visit http://127.0.0.1:8000/init-db to initialize the database tables"