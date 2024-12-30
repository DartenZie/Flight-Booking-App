# Use the official PHP image with Apache
FROM php:8.1-apache

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy the project files to the container
COPY . /var/www/html/

# Set permissions for Apache
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Expose port 8080 for Apache
EXPOSE 8080

# Add custom Apache configuration for proper routing
COPY apache.conf /etc/apache2/sites-available/000-default.conf
