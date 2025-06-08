<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

$value = $request->get('param');

return 'POST catch all params ' . $value;
