FROM php:8.2.5-apache

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

# Expose port and start Apache server
EXPOSE 80
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]