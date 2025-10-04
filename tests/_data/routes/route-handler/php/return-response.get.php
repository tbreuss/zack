<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

return new Response('Return response value', 200, [
    'Content-Type' => 'text/html; charset=UTF-8',
]);
