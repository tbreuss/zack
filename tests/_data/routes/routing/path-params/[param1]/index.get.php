<?php declare(strict_types=1);

$value = $request->get('param1');

return new Symfony\Component\HttpFoundation\Response($value);
