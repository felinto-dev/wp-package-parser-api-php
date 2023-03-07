FROM php:8.2-fpm AS base
WORKDIR /app

## Install OS dependencies
RUN apt update \
	&& export DEBIAN_FRONTEND=noninteractive \
	&& apt install -y --no-install-recommends libzip-dev \
	&& apt purge -y --auto-remove

## Install PHP depedencies
RUN docker-php-ext-install zip

FROM composer AS build
WORKDIR /app

COPY composer.json .
COPY composer.lock .
RUN composer install --no-dev --no-scripts --ignore-platform-reqs

COPY . .
RUN composer dumpautoload --optimize

FROM base AS final

COPY --from=build /app /app

CMD ["./worker.sh"]
