<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/../vendor/autoload.php';

$options = [
    'rootPath' => dirname(__DIR__),
    'twigCachePath' => false,
];

(new \tebe\zack\Zack($options))->run();
