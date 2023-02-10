FROM php:8.1-fpm

ARG USER_ID=1001
ARG GROUP_ID=1002

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev libxml2-dev libpq-dev zip libzip-dev zip librabbitmq-dev \
    && docker-php-ext-install intl soap opcache pdo pdo_pgsql pgsql \
    && pecl install apcu amqp \
    && docker-php-ext-enable apcu amqp \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip \
	&& docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

WORKDIR /var/www/symfony_docker

RUN pecl install -o -f redis \
&&  rm -rf /tmp/pear \
&&  docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

RUN usermod -u ${USER_ID} www-data && groupmod -g ${GROUP_ID} www-data

USER www-data

CMD composer install && php-fpm

