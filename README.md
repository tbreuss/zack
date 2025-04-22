# zack

    docker run --rm -it -v .:/app:z herbie bash

    docker run --rm         -e XDEBUG_MODE=debug         -e XDEBUG_CONFIG="client_host=172.17.0.1"         -e XDEBUG_SESSION_START=True         -p 8888:8888         -v .:/app:z         herbie php -S 0.0.0.0:8888 -t /app/website/web
