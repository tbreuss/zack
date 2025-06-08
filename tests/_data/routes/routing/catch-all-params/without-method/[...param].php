<?php declare(strict_types=1);

/** @var Symfony\Component\HttpFoundation\Request $request */

$value = $request->get('param');

return 'Catch all params ' . $value;
