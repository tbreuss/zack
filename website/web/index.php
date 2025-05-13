<?php declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';
$dispatcher = require dirname(__DIR__) . '/config/event-dispatcher.php';

(new tebe\zack\Zack($config, $dispatcher))->run();
