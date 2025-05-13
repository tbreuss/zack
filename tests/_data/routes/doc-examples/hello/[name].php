<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');

return new Response('Hello ' . $name . '!', 200);
