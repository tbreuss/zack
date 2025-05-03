ARG PHP_VERSION=8.4


FROM php:$PHP_VERSION-cli AS base


FROM base AS composer
COPY --from=composer:lts /usr/bin/composer /usr/bin/composer


FROM base AS builder

RUN apt-get update; \
    apt-get install -y --no-install-recommends \
        libicu-dev \
        libfreetype-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    ; \
    rm -rf /var/lib/apt/lists/*;

RUN docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j$(nproc) gd intl;

RUN pecl channel-update pecl.php.net; \
    pecl install xdebug; \
    docker-php-ext-enable xdebug;


FROM base AS server 

RUN apt-get update; \
    apt-get install -y --no-install-recommends \
        git \
        libfreetype6 \
        libjpeg62-turbo \
        libpng16-16 \
        unzip \
    ; \
    rm -rf /var/lib/apt/lists/*;

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY --from=builder /usr/local/lib/php/extensions /usr/local/lib/php/extensions
COPY --from=builder /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN groupadd -g 1000 zack && useradd -m -u 1000 -g zack zack
RUN mkdir /app  && chown -R zack:zack /app
USER zack

WORKDIR /app
VOLUME /app

EXPOSE 8888

CMD ["php", "-S", "0.0.0.0:8888", "-t", "/app/web"]
