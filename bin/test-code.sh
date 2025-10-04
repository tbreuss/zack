#!/bin/sh
# Run functional test against a Docker container of our project
# Example:
# $ test-code.sh localhost:9876
# In this case http://localhost:9876 is where our server is reachable and exposed

set -eu

wait_for_url () {
    echo "Testing $1..."
    printf 'GET %s\nHTTP 200' "http://$1" | docker run --rm -i --network="host" ghcr.io/orange-opensource/hurl:latest --variable host="http://$1" --retry "$2" --retry-interval 1s > /dev/null
    return 0
}

echo "Starting app instance"
php -d xdebug.mode=coverage -S "$1" -t tests/_data/web &

echo "Starting app instance to be ready"
wait_for_url "$1" 5

echo "Running Hurl tests"
cat tests/functional/*.hurl | docker run --rm -i --network="host" ghcr.io/orange-opensource/hurl:latest --variable host="http://$1" --test -v --color

echo "Stopping app instance"
