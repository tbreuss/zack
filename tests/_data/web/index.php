<?php declare(strict_types=1);

require dirname(__DIR__, 3) . '/vendor/autoload.php';

use tebe\zack\Zack;

$config = require dirname(__DIR__) . '/config/config.php';
$dispatcher = require dirname(__DIR__) . '/config/event-dispatcher.php';

(new Zack($config, $dispatcher))->run();
