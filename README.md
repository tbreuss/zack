# Zack!

Zack! is a tiny little framework based on [Symfony's HTTP-Kernel](https://symfony.com/doc/current/components/http_kernel.html) using file-based routing, inspired by Javascript Librarie and Frameworks like [Nitro](https://nitro.build/guide/routing).

## Supported PHP versions

- PHP 8.2 / 8.3 / 8.4

## Commands

    docker run --rm -it -v .:/app:z herbie bash

    docker run --rm         -e XDEBUG_MODE=debug         -e XDEBUG_CONFIG="client_host=172.17.0.1"         -e XDEBUG_SESSION_START=True         -p 8888:8888         -v .:/app:z         herbie php -S 0.0.0.0:8888 -t /app/website/web
