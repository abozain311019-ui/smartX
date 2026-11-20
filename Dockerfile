FROM php:8.2-apache

# Enable SQLite PDO & Apache rewrite
RUN apt-get update && apt-get install -y --no-install-recommends \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Copy website files into Apache web root
WORKDIR /var/www/html
COPY . /var/www/html/

# Ensure data directory exists and is writable for SQLite
RUN mkdir -p /var/www/html/data \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/data

# Render injects $PORT (default 10000); configure Apache to listen on it at startup
EXPOSE 10000
CMD ["sh", "-c", "sed -i \"s/80/${PORT:-10000}/g\" /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf && apache2-foreground"]
