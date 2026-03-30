FROM php:8.3-apache

# Activer les modules Apache nécessaires
RUN a2enmod rewrite

# Copier la configuration du VirtualHost
COPY docker/php-apache/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80
