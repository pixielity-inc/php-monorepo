FROM dunglas/frankenphp:php8.4

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    librdkafka-dev \
    autoconf \
    g++ \
    make \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    opcache \
    redis \
    zip \
    intl \
    sockets

# Install Kafka extension (rdkafka)
RUN pecl install rdkafka && \
    docker-php-ext-enable rdkafka

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory to Laravel root
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod +x /var/www/html/bin/artisan

# Expose port
EXPOSE 8000

# Start Octane with FrankenPHP (artisan is in bin/ subdirectory)
CMD ["bin/artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
