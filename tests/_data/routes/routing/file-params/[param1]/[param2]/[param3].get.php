<?php declare(strict_types=1);

$value1 = $request->get('param1');
$value2 = $request->get('param2');
$value3 = $request->get('param3');

return new Symfony\Component\HttpFoundation\Response($value1 . ' ' . $value2 . ' ' . $value3);
