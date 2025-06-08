<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

$name = $request->attributes->get('name');
$age = $request->attributes->get('age');

echo "Hello $name! You are $age years old.";
