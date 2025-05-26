<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');

return new Response("Hello $name!", 200);
