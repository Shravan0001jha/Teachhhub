# Use the official PHP image
FROM php:8.2-fpm

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
    supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Install application dependencies
RUN composer clear-cache && \
    composer install --no-dev --optimize-autoloader --verbose

# Verify PHP extensions and platform requirements
RUN composer check-platform-reqs

# Install npm dependencies
RUN npm install

# Copy supervisord configuration
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose ports for Laravel and Vite
EXPOSE 8080 5173

# Start supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]