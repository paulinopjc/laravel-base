FROM php:8.3-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl libzip-dev unzip zip libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer and install dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Laravel specific permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose port 80 and start Apache
EXPOSE 80
CMD ["apache2-foreground"]
