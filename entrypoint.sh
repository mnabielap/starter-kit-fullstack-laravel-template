#!/bin/bash
set -e

# 1. Check for .env file
# If .env does not exist, copy the Docker env or example
if [ ! -f ".env" ]; then
    echo "Creating .env file..."
    if [ -f ".env.docker" ]; then
        cp .env.docker .env
    else
        cp .env.example .env
    fi
fi

# 2. Install Dependencies (if vendor missing)
if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies..."
    composer install --no-progress --no-interaction
fi

# 3. Generate App Key (if missing)
if grep -q "APP_KEY=base64:" .env; then
    echo "App Key exists."
else
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# 4. Create Storage Link (for public uploads)
if [ ! -L "public/storage" ]; then
    echo "Linking storage..."
    php artisan storage:link
fi

# 5. Database Migration
# Wait for MySQL to be ready (simple sleep approach)
echo "Waiting for Database connection..."
sleep 5

echo "Running Migrations..."
php artisan migrate --force

# 6. Start Apache
echo "Starting Laravel on port 5005..."
exec docker-php-entrypoint apache2-foreground