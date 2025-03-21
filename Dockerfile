# Dockerfile
FROM php:8.2-apache

# Install system packages required for building/compiling PECL extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    unzip \
    git \
    wget \
    nano \
    # optional: for debugging, etc.
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions needed for your app
RUN docker-php-ext-install pdo pdo_mysql

# 1. Install the OpenTelemetry extension from PECL (beta/alpha versions might change)
RUN pecl install opentelemetry-beta \
    && docker-php-ext-enable opentelemetry

# 2. If you have a custom .ini for instrumentation, copy it in
#    We'll just do an inline echo in this example:
RUN echo "extension=opentelemetry.so\n" \
    "opentelemetry.instrumentation.enabled=1\n" \
    >> /usr/local/etc/php/conf.d/opentelemetry.ini

# Enable Apache mod_rewrite if Slim needs pretty URLs
RUN a2enmod rewrite

# 4. Copy your custom vhost config, replacing default 000-default.conf
COPY apache_vhost.conf /etc/apache2/sites-available/000-default.conf
