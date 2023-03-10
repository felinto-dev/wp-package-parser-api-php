# Define a imagem base
FROM php:8.2-apache

# Atualiza a lista de pacotes e instala as dependências necessárias
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        unzip \
        libapache2-mod-rewrite

# Instala a extensão necessária do PHP
RUN docker-php-ext-install zip

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia o código-fonte da aplicação para o diretório de trabalho
COPY . .

# Instala as dependências do Composer
RUN composer install

# Copia o arquivo de configuração do Apache
COPY apache2.conf /etc/apache2/apache2.conf

# Habilita o módulo do Apache para o PHP
RUN a2enmod php8.2
RUN a2enmod rewrite

# Define o arquivo principal a ser executado
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/conf-available/*.conf
