<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

$name = $request->attributes->get('name');

echo "Hello $name!";
