<?php declare(strict_types=1);

return array_merge(require __DIR__ . '/config-prod.php', [
    'twig' => [
        'cache' => false,
    ],
]);
