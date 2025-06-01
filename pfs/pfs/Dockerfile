FROM php:8.2.12-apache

# Active le module rewrite
RUN a2enmod rewrite

# Installe les extensions PHP nécessaires (dont PDO MySQL)
RUN docker-php-ext-install pdo pdo_mysql

# Copie tout le projet dans le répertoire web Apache
COPY . /var/www/html/

# Donne les bons droits
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]

