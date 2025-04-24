# Dockerfile
FROM php:8.2

# Install system packages required for building/compiling PECL extensions
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    gdb \
    unzip \
    git \
    wget \
    nano \
    # optional: for debugging, etc.
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions needed for your app
RUN docker-php-ext-install pdo pdo_mysql

# 1. Install the OpenTelemetry extension from PECL (beta/alpha versions might change)
RUN pecl install opentelemetry \
    && docker-php-ext-enable opentelemetry

# 2. If you have a custom .ini for instrumentation, copy it in
#    We'll just do an inline echo in this example:
RUN echo "extension=opentelemetry.so\n" \
    "opentelemetry.instrumentation.enabled=1\n" \
    >> /usr/local/etc/php/conf.d/opentelemetry.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer


WORKDIR /app
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

EXPOSE 8080
COPY . .
RUN composer update
RUN composer install

CMD php -S 127.0.0.1:8080 -t public

