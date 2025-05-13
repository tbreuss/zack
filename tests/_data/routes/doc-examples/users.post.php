<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

// Do something with body like saving it to a database

return new Response('{"updated": true}', 200, [
    'Content-Type' => 'application/json',
]);
