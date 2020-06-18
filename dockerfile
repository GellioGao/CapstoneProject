FROM php:7.4.6-apache
COPY APIs/ /var/www/html/

SHELL ["/bin/bash", "-c"]

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

ENV APACHE_DOCUMENT_ROOT /var/www/html/public/

# Enable the Apache HTTP Server modules.
RUN ln -s ../mods-available/{expires,headers,rewrite}.load /etc/apache2/mods-enabled/

# Replace AllowOverride None with AllowOverride All for the directory /var/www/. For .htaccess file.
RUN sed -e '/<Directory \/var\/www\/>/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' -i /etc/apache2/apache2.conf

# Change documentRoot to {APACHE_DOCUMENT_ROOT}
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80