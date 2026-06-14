FROM php:8.4-cli-bookworm

# Install PHP extensions, Node.js and NPM
RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libpq-dev \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN npm install && npm run build

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
