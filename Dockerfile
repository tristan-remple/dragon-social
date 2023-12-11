FROM php:8.2-apache

#install dependencies
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip

#Enable mod_rewrite
RUN a2enmod rewrite

#Install PHP Extensions
RUN docker-php-ext-install pdo_mysql zip

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy the application code
COPY . /var/www/html

#set the working directory
WORKDIR /var/www/html

#Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Install project dependencies
RUN composer install

#set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# create assets link
RUN php artisan storage:link
