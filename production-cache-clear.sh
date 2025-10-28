#!/bin/bash

echo "Clearing production cache..."

# Clear all Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# Clear compiled views
rm -rf storage/framework/views/*

# Clear config cache
rm -rf bootstrap/cache/config.php

echo "Production cache cleared successfully!"
