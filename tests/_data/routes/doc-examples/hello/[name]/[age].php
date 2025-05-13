<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

$name = $request->attributes->get('name');
$age = $request->attributes->get('age');

return new Response("Hello $name! You are $age years old.", 200);
