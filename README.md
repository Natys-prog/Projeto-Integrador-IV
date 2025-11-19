# Projeto-Integrador-IV

Apresentação: https://docs.google.com/presentation/d/171NGLUDUXdxX8BBj_S2r95NhKpmYSSFS/edit?usp=sharing&ouid=105442219488268034996&rtpof=true&sd=true

## 🚀 Quick Setup (Recommended)

### Automatic Setup
**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

**Windows:**
```batch
setup.bat
```

**Using Make:**
```bash
make setup
```

**Using npm:**
```bash
cd laravel-app
npm run setup
```

**Using Composer:**
```bash
cd laravel-app
composer setup
```

## 🖥️ Running the Application

### Start Development Server
```bash
# Option 1: Laravel artisan
cd laravel-app
php artisan serve

# Option 2: npm script
cd laravel-app
npm run serve

# Option 3: Development mode (with asset watching)
cd laravel-app
npm run serve-dev

# Option 4: Make command
make serve
```

### Access Your Application
- **Laravel App:** http://127.0.0.1:8000
- **Original PHP App:** http://localhost:8000 (if running separately)

### Initialize Database
Visit: http://127.0.0.1:8000/init-db

## 🐳 Docker Setup (Alternative)

```bash
# Start all services (Laravel + MySQL + phpMyAdmin)
docker-compose up -d

# Access applications
# Laravel: http://localhost:8000
# phpMyAdmin: http://localhost:8080
```

## 📋 Requirements

- **PHP:** 8.2 or higher
- **Composer:** Latest version
- **Node.js:** 18 or higher
- **MySQL:** 5.7 or higher

## 🛠️ Manual Setup (if automatic setup fails)

### 1. Install Dependencies
```bash
cd laravel-app

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Build assets
npm run build
```

### 2. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Update database configuration in .env
```

### 3. Optimize Application
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🗄️ Database Configuration

Update your `.env` file with your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 📁 Project Structure

```
Projeto-Integrador-IV/
├── laravel-app/           # Laravel application
├── index.php             # Original PHP application
├── Database/             # Original database classes
├── Pages/               # Original PHP pages
├── setup.sh            # Linux/Mac setup script
├── setup.bat           # Windows setup script
├── docker-compose.yml  # Docker configuration
├── Dockerfile         # Docker image definition
└── Makefile          # Make commands
```

## 🎯 Available Commands

### npm Scripts (in laravel-app/)
- `npm run setup` - Complete setup
- `npm run serve` - Start Laravel server
- `npm run serve-dev` - Start with asset watching
- `npm run build` - Build production assets
- `npm run dev` - Development asset watching
- `npm run clear` - Clear all caches

### Make Commands
- `make setup` - Complete project setup
- `make serve` - Start development server
- `make dev` - Start with asset watching
- `make build` - Build production assets
- `make clean` - Clear caches
- `make docker-up` - Start Docker environment

### Composer Scripts (in laravel-app/)
- `composer setup` - Complete setup
- `composer dev` - Start development environment
- `composer test` - Run tests

## 🔧 Troubleshooting

### Common Issues

1. **Permission Errors:**
   ```bash
   chmod -R 755 laravel-app/storage
   chmod -R 755 laravel-app/bootstrap/cache
   ```

2. **Database Connection:**
   - Check MySQL is running
   - Verify credentials in `.env`
   - Ensure database exists

3. **Asset Build Errors:**
   ```bash
   cd laravel-app
   rm -rf node_modules package-lock.json
   npm install
   npm run build
   ```

## 📚 Legacy Information

**Original Setup (for reference):**

Linguagem: Principal PHP + Html
Banco: MYSQL

Starting original server:
```bash
php -S localhost:8000
```

Para criar a Base de dados (original):
```
localhost:8000/?init=1
```