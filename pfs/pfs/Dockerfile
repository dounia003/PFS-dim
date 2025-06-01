FROM php:8.2.12-apache

# Installer les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Activer les modules Apache utiles
RUN a2enmod rewrite

# Copier le code de l'application
COPY . /var/www/html/

# Fixer les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

