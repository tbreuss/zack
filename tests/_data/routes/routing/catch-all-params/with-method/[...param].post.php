<?php declare(strict_types=1);

$value = $request->get('param');

return new Symfony\Component\HttpFoundation\Response('POST catch all params ' . $value);
