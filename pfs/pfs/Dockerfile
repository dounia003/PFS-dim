FROM php:8.2-apache

# Active mod_rewrite
RUN a2enmod rewrite

# Installe les extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Copie les fichiers dans le dossier web
COPY . /var/www/html/

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose le port HTTP
EXPOSE 80

CMD ["apache2-foreground"]
