<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

$value = $request->get('param1');

return new Symfony\Component\HttpFoundation\Response($value);
