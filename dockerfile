# Image de base PHP avec Apache
FROM php:8.2-apache

# Dépendances système pour intl + Mongo
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libssl-dev \
    && docker-php-ext-install intl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier tout le projet
COPY . /var/www/html/

# Apache : DocumentRoot = public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

# Autoriser .htaccess dans public
RUN printf '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' >> /etc/apache2/apache2.conf

# Permissions Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

WORKDIR /var/www/html