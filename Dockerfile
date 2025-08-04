# Use the official PHP image
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Install application dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install

# Expose ports for Laravel and Vite
EXPOSE 8000 5173

# Start both Laravel and Vite development servers
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=8000 & npm run dev"]