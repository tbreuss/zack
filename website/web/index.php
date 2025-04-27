<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use tebe\zack\Zack;

$config = require __DIR__ . '/../config/config.php';
$dispatcher = require __DIR__ . '/../config/event-dispatcher.php';

(new Zack($config, $dispatcher))->run();
