# Laravel Project Makefile

.PHONY: help setup install serve dev build clean test docker-up docker-down

# Default target
help:
	@echo "Available commands:"
	@echo "  setup      - Complete project setup (install dependencies, generate key, etc.)"
	@echo "  install    - Install PHP and Node.js dependencies"
	@echo "  serve      - Start Laravel development server"
	@echo "  dev        - Start development server with asset watching"
	@echo "  build      - Build production assets"
	@echo "  clean      - Clear all caches"
	@echo "  test       - Run tests"
	@echo "  docker-up  - Start Docker containers"
	@echo "  docker-down - Stop Docker containers"

# Complete setup
setup:
	@echo "🚀 Setting up Laravel project..."
	cd laravel-app && composer install --optimize-autoloader
	cd laravel-app && npm install
	cd laravel-app && npm run build
	cd laravel-app && php artisan key:generate
	cd laravel-app && php artisan config:cache
	cd laravel-app && php artisan route:cache
	cd laravel-app && php artisan view:cache
	@echo "✅ Setup complete!"

# Install dependencies only
install:
	@echo "📦 Installing dependencies..."
	cd laravel-app && composer install
	cd laravel-app && npm install
	@echo "✅ Dependencies installed!"

# Start development server
serve:
	@echo "🚀 Starting Laravel development server..."
	cd laravel-app && php artisan serve

# Start development with asset watching
dev:
	@echo "🚀 Starting development environment..."
	cd laravel-app && npx concurrently "php artisan serve" "npm run dev"

# Build production assets
build:
	@echo "🏗️  Building production assets..."
	cd laravel-app && npm run build

# Clear caches
clean:
	@echo "🧹 Clearing caches..."
	cd laravel-app && php artisan config:clear
	cd laravel-app && php artisan cache:clear
	cd laravel-app && php artisan route:clear
	cd laravel-app && php artisan view:clear
	@echo "✅ Caches cleared!"

# Run tests
test:
	@echo "🧪 Running tests..."
	cd laravel-app && php artisan test

# Docker commands
docker-up:
	@echo "🐳 Starting Docker containers..."
	docker-compose up -d

docker-down:
	@echo "🐳 Stopping Docker containers..."
	docker-compose down