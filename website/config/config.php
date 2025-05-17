<?php declare(strict_types=1);

return [
    'basePath' => dirname(__DIR__),
    'name' => 'Zack!',
    'twig' => [
        'cache' => false,
    ],
    'logger' => [
        'minLevel' => 'warning',
        'output' => dirname(__DIR__) . '/logs/logger.log',
    ],
];
