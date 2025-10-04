#!/bin/sh
# Run static code analysis against a Docker container

set -eu

docker run --rm -v .:/app ghcr.io/phpstan/phpstan analyse
