<?php declare(strict_types=1);

require dirname(__DIR__, 3) . '/vendor/autoload.php';

$config = require dirname(__DIR__) . '/config/config.php';

(new tebe\zack\Zack($config))->run();
