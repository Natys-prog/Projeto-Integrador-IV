# 🚀 Quick Start Guide

## One-Command Setup

Choose your preferred method:

### 🐧 Linux/Mac (Recommended)
```bash
./setup.sh
```

### 🪟 Windows
```batch
setup.bat
```

### 📦 Using npm
```bash
cd laravel-app && npm run setup
```

### 🎼 Using Make
```bash
make setup
```

### 🎯 Using Composer
```bash
cd laravel-app && composer setup
```

## After Setup

1. **Start the server:**
   ```bash
   cd laravel-app
   php artisan serve
   ```

2. **Visit your app:** http://127.0.0.1:8000

3. **Initialize database:** http://127.0.0.1:8000/init-db

## What the Setup Does

✅ **Checks for required tools** (PHP, Composer, Node.js)  
✅ **Installs missing dependencies** automatically  
✅ **Runs `composer install`** for PHP packages  
✅ **Runs `npm install`** for Node.js packages  
✅ **Builds frontend assets** with Vite  
✅ **Generates Laravel app key**  
✅ **Sets proper permissions**  
✅ **Optimizes for performance**  

## Available Commands After Setup

| Command | Description |
|---------|-------------|
| `php artisan serve` | Start Laravel server |
| `npm run dev` | Start asset watching |
| `npm run serve-dev` | Server + asset watching |
| `npm run build` | Build production assets |
| `make serve` | Start via Make |
| `make dev` | Development mode via Make |

## Docker Alternative

If you prefer Docker:
```bash
docker-compose up -d
```

Access:
- Laravel: http://localhost:8000
- phpMyAdmin: http://localhost:8080

## Troubleshooting

**Permission issues:**
```bash
chmod -R 755 laravel-app/storage laravel-app/bootstrap/cache
```

**Clear caches:**
```bash
cd laravel-app
php artisan config:clear && php artisan cache:clear
```

**Rebuild assets:**
```bash
cd laravel-app
rm -rf node_modules package-lock.json
npm install && npm run build
```