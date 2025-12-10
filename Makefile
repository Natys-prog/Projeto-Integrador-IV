# ==========================================
# LARAVEL EPI MANAGEMENT SYSTEM - MAKEFILE
# ==========================================

.DEFAULT_GOAL := help
SHELL := /bin/bash

# Colors for terminal output
RED := \033[31m
GREEN := \033[32m
YELLOW := \033[33m
BLUE := \033[34m
PURPLE := \033[35m
CYAN := \033[36m
WHITE := \033[37m
RESET := \033[0m

# Project configuration
PROJECT_NAME := laravel-epi
DOCKER_COMPOSE := docker-compose
APP_CONTAINER := $(PROJECT_NAME)-app
DB_CONTAINER := $(PROJECT_NAME)-mysql

# =====================================
# Help and Information
# =====================================
.PHONY: help
help: ## Show this help message
	@echo -e "$(CYAN)Laravel EPI Management System$(RESET)"
	@echo -e "$(CYAN)==============================$(RESET)"
	@echo ""
	@echo -e "$(YELLOW)Available Commands:$(RESET)"
	@awk 'BEGIN {FS = ":.*##"} /^[a-zA-Z_-]+:.*##/ { printf "  $(GREEN)%-20s$(RESET) %s\n", $$1, $$2 }' $(MAKEFILE_LIST)
	@echo ""

.PHONY: status
status: ## Show services status
	@echo -e "$(BLUE)Service Status:$(RESET)"
	@$(DOCKER_COMPOSE) ps

# =====================================
# Environment Setup
# =====================================
.PHONY: init
init: ## Initialize project with default settings
	@echo -e "$(YELLOW)Initializing Laravel EPI Management System...$(RESET)"
	@if [ ! -f .env ]; then \
		cp .env.example .env; \
		echo -e "$(GREEN)✓ Created .env file$(RESET)"; \
	else \
		echo -e "$(YELLOW)⚠ .env file already exists$(RESET)"; \
	fi
	@mkdir -p docker/data/{mysql,redis,storage,logs}
	@echo -e "$(GREEN)✓ Created data directories$(RESET)"
	@echo -e "$(GREEN)✓ Initialization completed!$(RESET)"

.PHONY: setup
setup: init build up key migrate ## Complete setup (init + build + up + key + migrate)
	@echo -e "$(GREEN)🎉 Setup completed! Application available at: http://localhost:8080$(RESET)"

# =====================================
# Docker Management
# =====================================
.PHONY: build
build: ## Build Docker images
	@echo -e "$(YELLOW)Building Docker images...$(RESET)"
	@$(DOCKER_COMPOSE) build --no-cache
	@echo -e "$(GREEN)✓ Build completed$(RESET)"

.PHONY: up
up: ## Start all services
	@echo -e "$(YELLOW)Starting services...$(RESET)"
	@$(DOCKER_COMPOSE) up -d
	@echo -e "$(GREEN)✓ Services started$(RESET)"
	@make status

.PHONY: down
down: ## Stop all services
	@echo -e "$(YELLOW)Stopping services...$(RESET)"
	@$(DOCKER_COMPOSE) down
	@echo -e "$(GREEN)✓ Services stopped$(RESET)"

.PHONY: restart
restart: ## Restart all services
	@echo -e "$(YELLOW)Restarting services...$(RESET)"
	@$(DOCKER_COMPOSE) restart
	@echo -e "$(GREEN)✓ Services restarted$(RESET)"

.PHONY: rebuild
rebuild: down build up ## Rebuild and restart services
	@echo -e "$(GREEN)✓ Rebuild completed$(RESET)"

# =====================================
# Application Management
# =====================================
.PHONY: key
key: ## Generate application key
	@echo -e "$(YELLOW)Generating application key...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan key:generate
	@echo -e "$(GREEN)✓ Application key generated$(RESET)"

.PHONY: migrate
migrate: ## Run database migrations
	@echo -e "$(YELLOW)Running database migrations...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate --force
	@echo -e "$(GREEN)✓ Migrations completed$(RESET)"

.PHONY: migrate-fresh
migrate-fresh: ## Fresh migration (destroys existing data)
	@echo -e "$(RED)⚠ WARNING: This will destroy all existing data!$(RESET)"
	@read -p "Are you sure? (y/N): " confirm && [ "$$confirm" = "y" ]
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan migrate:fresh --force
	@echo -e "$(GREEN)✓ Fresh migration completed$(RESET)"

.PHONY: seed
seed: ## Run database seeders
	@echo -e "$(YELLOW)Running database seeders...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan db:seed --force
	@echo -e "$(GREEN)✓ Seeding completed$(RESET)"

.PHONY: seed-class
seed-class: ## Run specific seeder class (usage: make seed-class SEEDER=ClassName)
	@if [ -z "$(SEEDER)" ]; then \
		echo -e "$(RED)❌ Please specify SEEDER class: make seed-class SEEDER=ClassName$(RESET)"; \
		exit 1; \
	fi
	@echo -e "$(YELLOW)Running seeder: $(SEEDER)$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan db:seed --class=$(SEEDER) --force
	@echo -e "$(GREEN)✓ Seeder $(SEEDER) completed$(RESET)"

# =====================================
# Seeder Management with Parameters
# =====================================
.PHONY: seed-users
seed-users: ## Seed only users
	@make seed-class SEEDER=UsuarioSeeder

.PHONY: seed-departments
seed-departments: ## Seed only departments
	@make seed-class SEEDER=DepartamentosSeeder

.PHONY: seed-positions
seed-positions: ## Seed only positions/cargos
	@make seed-class SEEDER=CargosSeeder

.PHONY: seed-epi-types
seed-epi-types: ## Seed only EPI types
	@make seed-class SEEDER=TiposEpiSeeder

.PHONY: seed-epis
seed-epis: ## Seed only EPIs
	@make seed-class SEEDER=EpiSeeder

.PHONY: seed-employees
seed-employees: ## Seed only employees
	@make seed-class SEEDER=FuncionariosSeeder

.PHONY: seed-all
seed-all: migrate seed ## Run migrations and all seeders

# =====================================
# Development Tools
# =====================================
.PHONY: shell
shell: ## Access application shell
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) bash

.PHONY: shell-db
shell-db: ## Access database shell
	@$(DOCKER_COMPOSE) exec $(DB_CONTAINER) mysql -u laravel -p laravel_epi

.PHONY: tinker
tinker: ## Access Laravel Tinker
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan tinker

.PHONY: logs
logs: ## Show application logs
	@$(DOCKER_COMPOSE) logs -f $(APP_CONTAINER)

.PHONY: logs-db
logs-db: ## Show database logs
	@$(DOCKER_COMPOSE) logs -f $(DB_CONTAINER)

.PHONY: logs-all
logs-all: ## Show all services logs
	@$(DOCKER_COMPOSE) logs -f

# =====================================
# Cache Management
# =====================================
.PHONY: cache-clear
cache-clear: ## Clear application cache
	@echo -e "$(YELLOW)Clearing application cache...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan cache:clear
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan config:clear
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan route:clear
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan view:clear
	@echo -e "$(GREEN)✓ Cache cleared$(RESET)"

.PHONY: optimize
optimize: ## Optimize application
	@echo -e "$(YELLOW)Optimizing application...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan config:cache
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan route:cache
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan view:cache
	@echo -e "$(GREEN)✓ Application optimized$(RESET)"

# =====================================
# Testing
# =====================================
.PHONY: test
test: ## Run PHP tests
	@echo -e "$(YELLOW)Running tests...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan test
	@echo -e "$(GREEN)✓ Tests completed$(RESET)"

.PHONY: test-coverage
test-coverage: ## Run tests with coverage
	@echo -e "$(YELLOW)Running tests with coverage...$(RESET)"
	@$(DOCKER_COMPOSE) exec $(APP_CONTAINER) php artisan test --coverage
	@echo -e "$(GREEN)✓ Test coverage completed$(RESET)"

# =====================================
# Cleanup and Maintenance
# =====================================
.PHONY: clean
clean: ## Clean containers and volumes
	@echo -e "$(YELLOW)Cleaning up...$(RESET)"
	@$(DOCKER_COMPOSE) down -v --remove-orphans
	@docker system prune -f
	@echo -e "$(GREEN)✓ Cleanup completed$(RESET)"

.PHONY: clean-all
clean-all: ## Clean everything including images
	@echo -e "$(RED)⚠ WARNING: This will remove all Docker images, containers, and volumes!$(RESET)"
	@read -p "Are you sure? (y/N): " confirm && [ "$$confirm" = "y" ]
	@$(DOCKER_COMPOSE) down -v --rmi all --remove-orphans
	@docker system prune -af
	@echo -e "$(GREEN)✓ Complete cleanup finished$(RESET)"

.PHONY: backup-db
backup-db: ## Backup database
	@echo -e "$(YELLOW)Creating database backup...$(RESET)"
	@mkdir -p backups
	@$(DOCKER_COMPOSE) exec $(DB_CONTAINER) mysqldump -u laravel -psecret laravel_epi > backups/backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo -e "$(GREEN)✓ Database backup created$(RESET)"

.PHONY: restore-db
restore-db: ## Restore database (usage: make restore-db FILE=backup_file.sql)
	@if [ -z "$(FILE)" ]; then \
		echo -e "$(RED)❌ Please specify backup FILE: make restore-db FILE=backup_file.sql$(RESET)"; \
		exit 1; \
	fi
	@echo -e "$(YELLOW)Restoring database from $(FILE)...$(RESET)"
	@$(DOCKER_COMPOSE) exec -T $(DB_CONTAINER) mysql -u laravel -psecret laravel_epi < $(FILE)
	@echo -e "$(GREEN)✓ Database restored$(RESET)"

# =====================================
# Quick Development Profiles
# =====================================
.PHONY: dev-start
dev-start: ## Start with development settings and seed data
	@echo -e "$(YELLOW)Starting development environment...$(RESET)"
	@RUN_SEEDERS=true FORCE_SEED=true APP_ENV=local APP_DEBUG=true $(DOCKER_COMPOSE) up -d
	@echo -e "$(GREEN)✓ Development environment started with sample data$(RESET)"

.PHONY: prod-start
prod-start: ## Start with production settings
	@echo -e "$(YELLOW)Starting production environment...$(RESET)"
	@RUN_SEEDERS=false APP_ENV=production APP_DEBUG=false $(DOCKER_COMPOSE) up -d
	@echo -e "$(GREEN)✓ Production environment started$(RESET)"

# =====================================
# Queue and Scheduler Management
# =====================================
.PHONY: queue-start
queue-start: ## Start queue workers
	@echo -e "$(YELLOW)Starting queue workers...$(RESET)"
	@QUEUE_WORKERS=2 $(DOCKER_COMPOSE) --profile queue up -d queue
	@echo -e "$(GREEN)✓ Queue workers started$(RESET)"

.PHONY: scheduler-start  
scheduler-start: ## Start task scheduler
	@echo -e "$(YELLOW)Starting task scheduler...$(RESET)"
	@$(DOCKER_COMPOSE) --profile scheduler up -d scheduler
	@echo -e "$(GREEN)✓ Task scheduler started$(RESET)"

# =====================================
# Information
# =====================================
.PHONY: info
info: ## Show project information
	@echo -e "$(CYAN)Laravel EPI Management System$(RESET)"
	@echo -e "$(CYAN)=============================$(RESET)"
	@echo -e "$(WHITE)Project:$(RESET) $(PROJECT_NAME)"
	@echo -e "$(WHITE)App URL:$(RESET) http://localhost:8080"
	@echo -e "$(WHITE)API URL:$(RESET) http://localhost:8080/api"
	@echo -e "$(WHITE)Database:$(RESET) localhost:3306"
	@echo -e "$(WHITE)Compose File:$(RESET) docker-compose.yml"
	@echo ""
	@echo -e "$(YELLOW)Quick Commands:$(RESET)"
	@echo -e "  $(GREEN)make setup$(RESET)     - Complete initial setup"
	@echo -e "  $(GREEN)make dev-start$(RESET) - Start with sample data"
	@echo -e "  $(GREEN)make prod-start$(RESET)- Start for production"
	@echo -e "  $(GREEN)make seed-all$(RESET)  - Reset and seed database"