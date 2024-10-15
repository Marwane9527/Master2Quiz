# Utilisez une image PHP avec Apache
FROM php:8.0-apache

# Installez les paquets nécessaires pour les extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Copiez le code de votre application dans le conteneur
COPY . /var/www/html/

# Configurez les permissions si nécessaire
RUN chown -R www-data:www-data /var/www/html

# Exposez le port 80
EXPOSE 80
