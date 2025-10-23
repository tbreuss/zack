<?php declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';
require dirname(__DIR__, 2) . '/.php-code-coverage.php';

$config = require dirname(__DIR__) . '/config/config-dev.php';
$container = require dirname(__DIR__) . '/config/container.php';

(new tebe\zack\Zack(config: $config, container: $container))->run();
