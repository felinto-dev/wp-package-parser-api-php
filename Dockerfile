FROM webdevops/php-nginx:8.2-alpine
ENV WEB_DOCUMENT_ROOT=/app/public
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV PHP_DISMOD=bz2,calendar,exiif,ffi,intl,gettext,ldap,mysqli,imap,pdo_pgsql,pgsql,soap,sockets,sysvmsg,sysvsm,sysvshm,shmop,xsl,gd,apcu,vips,yaml,imagick,mongodb,amqp
WORKDIR /app
COPY composer.json composer.lock .
RUN composer install --no-interaction --optimize-autoloader --no-dev
COPY . .
RUN chown -R application:application .