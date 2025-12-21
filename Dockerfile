# Use PHP 8.2 with Apache
FROM php:8.2-apache

# 1. Install System Dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Install PHP Extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. Configure Apache
# Enable mod_rewrite for Laravel
RUN a2enmod rewrite

# Change Apache port from 80 to 5005
RUN sed -i 's/80/5005/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Change DocumentRoot to /var/www/html/public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# 4. Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set Working Directory
WORKDIR /var/www/html

# 6. Copy Application Code
COPY . .

# 7. Set Permissions
# Give write access to the web server for storage and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Expose Port
EXPOSE 5005

# 9. Setup Entrypoint
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]