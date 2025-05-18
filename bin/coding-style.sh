#!/bin/sh
# Run coding style test against a Docker container

set -eu

docker run --rm -v $(pwd):/code ghcr.io/php-cs-fixer/php-cs-fixer:${FIXER_VERSION:-3-php8.3} fix
