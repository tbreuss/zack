<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

return new Response('{"ping": "pong"}', 200, [
    'Content-Type' => 'application/json',
]);
