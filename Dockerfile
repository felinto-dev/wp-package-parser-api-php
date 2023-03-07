# Imagem base com PHP 8.2 e Apache
FROM php:8.2-apache

# Instalação do libzip-dev e extensão zip
RUN apt-get update && apt-get install -y \
    libzip-dev \
    && docker-php-ext-install zip

# Define o diretório de trabalho como /var/www/html
WORKDIR /var/www/html

# Copia os arquivos da aplicação para o diretório de trabalho
COPY . .

# Exposição dinâmica da porta através da variável de ambiente $PORT
EXPOSE $PORT

# Inicialização do servidor web Apache
CMD ["apache2-foreground"]
