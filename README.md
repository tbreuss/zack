# Zack!

Zack! is a tiny little framework based on [Symfony's HTTP-Kernel](https://symfony.com/doc/current/components/http_kernel.html) using file-based routing, inspired by Javascript Librarie and Frameworks like [Nitro](https://nitro.build/guide/routing).
It ships with the Twig template engine and HTML, JSON, Markdown and PHP route handler out of the box.
It is a great fit for small projects, MVPs, or even as a microservice.

## Supported PHP versions

- PHP 8.2 / 8.3 / 8.4

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

    docker run -it --rm -v $(pwd):/code ghcr.io/php-cs-fixer/php-cs-fixer:${FIXER_VERSION:-3-php8.3} fix

### Acceptance Tests

Start built-in web server

    php -S localhost:9330 -t tests/_data/web

Run acceptance tests using [Hurl](https://hurl.dev/)

    cat tests/acceptance/* | docker run --rm -i --network="host" ghcr.io/orange-opensource/hurl:latest --variable host=http://localhost:9330 --test -v --color
