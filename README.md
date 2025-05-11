# Zack!

Zack! is a compact microframework, built on the [HttpKernel Component](https://symfony.com/doc/current/components/http_kernel.html) of Symfony, that emphasizes file-based routing.
It includes various route handlers for handling HTML, JSON, [Markdown](https://commonmark.thephpleague.com/), and PHP files with ease.
Additionally, Zack! integrates [Twig](https://twig.symfony.com/), a powerful template engine, making it a good choice for small, easily manageable website and API projects.

## Requirements

- PHP: 8.2 / 8.3 / 8.4
- Composer: 2.x

## Development Environment

### Create Docker Image

Create Docker image based on the latest supported PHP version

    docker build -t zack https://github.com/tbreuss/zack.git

Optionally you can also use an older PHP version

    docker build --build-arg PHP_VERSION=8.2 -t zack https://github.com/tbreuss/zack.git
    docker build --build-arg PHP_VERSION=8.3 -t zack https://github.com/tbreuss/zack.git

### Run Website

Clone project

    git clone https://github.com/tbreuss/zack.git

Change directory

    cd zack

Install packages

    docker run --rm -it -v .:/app zack composer install

Run website

    docker run --rm -v .:/app -p 8888:8888 zack php -S 0.0.0.0:8888 -t /app/website/web

## Testing

### PHP-CS-Fixer

Fix code style issue using [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)

    ./bin/coding-style.sh

### Functional Tests

Run functional tests

    ./bin/functional.sh localhost:9330
