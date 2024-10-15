# Utilise une image officielle de PHP avec Apache
FROM php:8.1-apache

# Copier le contenu de ton projet dans le répertoire /var/www/html
COPY . /var/www/html/

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Définir le répertoire de travail
WORKDIR /var/www/html

# Exposer le port 80 pour le serveur web
EXPOSE 80
