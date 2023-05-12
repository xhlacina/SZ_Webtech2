FROM php:8.2.5-apache
#FROM mysql:latest

# Install necessary packages
RUN apt-get update && \
    apt-get install -y \
    curl \
    unzip \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libpq-dev \
    git

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    intl \
    gd \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Enable Apache modules
RUN a2enmod rewrite

# Copy project files to container
#COPY init.sql /docker-entrypoint-initdb.d/
#COPY . /var/www/html/
#
## Set working directory
#WORKDIR /var/www/html/

# Expose port and start Apache server
EXPOSE 80
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]