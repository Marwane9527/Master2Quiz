# Utilisez une image PHP avec PostgreSQL
FROM php:8.0-apache

# Installez les extensions PDO et PDO_PGSQL
RUN docker-php-ext-install pdo pdo_pgsql

# Copiez le code de votre application dans le conteneur
COPY . /var/www/html/

# Configurez les permissions si nécessaire
RUN chown -R www-data:www-data /var/www/html

# Exposez le port 80
EXPOSE 80
