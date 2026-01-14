# ================================
# Laravel + Vite Production Image
# ================================

FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    nginx \
    nodejs \
    npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Create .env and install PHP dependencies
RUN echo "APP_NAME=Laravel" > .env \
 && echo "APP_ENV=production" >> .env \
 && echo "APP_KEY=" >> .env \
 && echo "APP_DEBUG=false" >> .env \
 && echo "APP_URL=http://localhost" >> .env \
 && echo "LOG_CHANNEL=stack" >> .env \
 && echo "DB_CONNECTION=sqlite" >> .env \
 && echo "DB_DATABASE=/var/www/database/database.sqlite" >> .env \
 && composer install --no-dev --optimize-autoloader \
 && php artisan key:generate \
 && php artisan storage:link


# Build Vite assets
RUN npm install && npm run build

# Set correct permissions
RUN chmod -R 777 storage bootstrap/cache

# Copy Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

# Expose port
EXPOSE 80

# Start services
CMD service nginx start && php-fpm
