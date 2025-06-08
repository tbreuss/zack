<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

$value1 = $request->get('param1');
$value2 = $request->get('param2');

echo $value1 . ' ' . $value2;
