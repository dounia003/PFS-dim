FROM php:8.2.12-apache

# Active mod_rewrite
RUN a2enmod rewrite

# Installe les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Copie les fichiers du projet dans le répertoire web
COPY . /var/www/html/

# Donne les bons droits aux fichiers
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose le port HTTP
EXPOSE 80

# Démarre Apache
CMD ["apache2-foreground"]
