<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config-prod.php';
$container = require dirname(__DIR__) . '/config/container.php';

(new tebe\zack\Zack(config: $config, container: $container))->run();
