FROM mcr.microsoft.com/devcontainers/php:0-8.2

# Enable mod rewrite
RUN a2enmod rewrite

# Install additional OS packages
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
  && apt-get -y install --no-install-recommends \
  libzip-dev

# Install PHP modules
RUN docker-php-ext-install zip