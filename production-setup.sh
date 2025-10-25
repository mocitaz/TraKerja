#!/bin/bash

# ========================================
# TraKerja Production Setup Script
# ========================================
# This script helps secure your Laravel application for production
# Run: bash production-setup.sh
# ========================================

set -e

echo "╔════════════════════════════════════════════════════════════════╗"
echo "║           TraKerja Production Security Setup                  ║"
echo "╚════════════════════════════════════════════════════════════════╝"
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if running as root
if [ "$EUID" -eq 0 ]; then 
   echo -e "${RED}[ERROR]${NC} Please don't run as root"
   exit 1
fi

echo -e "${YELLOW}[INFO]${NC} Starting production setup..."
echo ""

# 1. Check if .env exists
if [ ! -f .env ]; then
    echo -e "${YELLOW}[WARN]${NC} .env file not found. Creating from example..."
    cp .env.production.example .env
    echo -e "${GREEN}[OK]${NC} Created .env file"
else
    echo -e "${GREEN}[OK]${NC} .env file exists"
fi

# 2. Generate application key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    echo -e "${YELLOW}[INFO]${NC} Generating application key..."
    php artisan key:generate --force
    echo -e "${GREEN}[OK]${NC} Application key generated"
else
    echo -e "${GREEN}[OK]${NC} Application key already set"
fi

# 3. Check critical environment variables
echo -e "${YELLOW}[INFO]${NC} Checking environment configuration..."

if grep -q "APP_DEBUG=true" .env; then
    echo -e "${RED}[ERROR]${NC} APP_DEBUG is still true! Set to false in production"
    exit 1
fi

if grep -q "APP_ENV=local" .env; then
    echo -e "${RED}[ERROR]${NC} APP_ENV is still local! Set to production"
    exit 1
fi

if grep -q "DB_PASSWORD=root\|DB_PASSWORD=password\|DB_PASSWORD=$" .env; then
    echo -e "${RED}[ERROR]${NC} Weak or empty database password detected!"
    exit 1
fi

echo -e "${GREEN}[OK]${NC} Environment variables look good"

# 4. Set proper file permissions
echo -e "${YELLOW}[INFO]${NC} Setting file permissions..."

# Storage and bootstrap cache
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache

# .env file should be readable only by owner
chmod 600 .env

echo -e "${GREEN}[OK]${NC} File permissions set"

# 5. Clear and optimize caches
echo -e "${YELLOW}[INFO]${NC} Optimizing application..."

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

echo -e "${GREEN}[OK]${NC} Application optimized"

# 6. Run migrations
read -p "Do you want to run database migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}[INFO]${NC} Running migrations..."
    php artisan migrate --force
    echo -e "${GREEN}[OK]${NC} Migrations completed"
fi

# 7. Install composer dependencies (production only)
echo -e "${YELLOW}[INFO]${NC} Installing production dependencies..."
composer install --optimize-autoloader --no-dev
echo -e "${GREEN}[OK]${NC} Dependencies installed"

# 8. Build frontend assets
if [ -f "package.json" ]; then
    echo -e "${YELLOW}[INFO]${NC} Building frontend assets..."
    npm install
    npm run build
    echo -e "${GREEN}[OK]${NC} Assets built"
fi

# 9. Create queue tables if using database queue
if grep -q "QUEUE_CONNECTION=database" .env; then
    echo -e "${YELLOW}[INFO]${NC} Creating queue tables..."
    php artisan queue:table
    php artisan queue:failed-table
    php artisan migrate --force
    echo -e "${GREEN}[OK]${NC} Queue tables created"
fi

# 10. Security check
echo ""
echo -e "${YELLOW}[INFO]${NC} Running security checks..."

ISSUES=0

# Check debug mode
if grep -q "APP_DEBUG=true" .env; then
    echo -e "${RED}✗${NC} APP_DEBUG is enabled (CRITICAL)"
    ((ISSUES++))
else
    echo -e "${GREEN}✓${NC} APP_DEBUG is disabled"
fi

# Check environment
if grep -q "APP_ENV=production" .env; then
    echo -e "${GREEN}✓${NC} APP_ENV is set to production"
else
    echo -e "${RED}✗${NC} APP_ENV is not production (CRITICAL)"
    ((ISSUES++))
fi

# Check session encryption
if grep -q "SESSION_ENCRYPT=true" .env; then
    echo -e "${GREEN}✓${NC} Session encryption enabled"
else
    echo -e "${YELLOW}⚠${NC} Session encryption not enabled (RECOMMENDED)"
fi

# Check if using redis cache
if grep -q "CACHE_STORE=redis" .env; then
    echo -e "${GREEN}✓${NC} Redis cache configured"
else
    echo -e "${YELLOW}⚠${NC} Not using Redis cache (RECOMMENDED for production)"
fi

# Check SMTP configuration
if grep -q "MAIL_MAILER=smtp" .env && ! grep -q "MAIL_PASSWORD=null" .env; then
    echo -e "${GREEN}✓${NC} SMTP email configured"
else
    echo -e "${YELLOW}⚠${NC} SMTP email not properly configured"
fi

echo ""

if [ $ISSUES -eq 0 ]; then
    echo -e "${GREEN}╔════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${GREEN}║                    ✅ SETUP COMPLETE!                         ║${NC}"
    echo -e "${GREEN}╚════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${GREEN}Your application is ready for production!${NC}"
    echo ""
    echo "Next steps:"
    echo "1. Review your .env configuration"
    echo "2. Setup queue workers (see PRODUCTION_READINESS_AUDIT.md)"
    echo "3. Configure web server (Nginx/Apache)"
    echo "4. Enable HTTPS/SSL"
    echo "5. Setup monitoring and backups"
    echo ""
    echo "For detailed information, see: PRODUCTION_READINESS_AUDIT.md"
else
    echo -e "${RED}╔════════════════════════════════════════════════════════════════╗${NC}"
    echo -e "${RED}║                ⚠️  CRITICAL ISSUES FOUND!                     ║${NC}"
    echo -e "${RED}╚════════════════════════════════════════════════════════════════╝${NC}"
    echo ""
    echo -e "${RED}Found $ISSUES critical issue(s). Fix them before deploying!${NC}"
    echo ""
    exit 1
fi
