<?php declare(strict_types=1);

$name = $request->attributes->get('name');

return $this->html('hello.html.twig', [
    'title' => $name,
]);
