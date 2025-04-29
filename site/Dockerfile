FROM php:8.2-apache

# Installer les extensions nécessaires pour MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer le module rewrite d'Apache
RUN a2enmod rewrite

# Configurer Apache pour exécuter les fichiers PHP
RUN echo '<FilesMatch \.php$>\n\
    SetHandler application/x-httpd-php\n\
</FilesMatch>' > /etc/apache2/conf-available/docker-php.conf \
    && a2enconf docker-php

# Copier le code source dans le conteneur
COPY . /var/www/html/

# Définir les permissions correctes pour Apache
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html
