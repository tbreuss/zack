<?php declare(strict_types=1);

require dirname(__DIR__, 3) . '/vendor/autoload.php';

use tebe\zack\Zack;

$config = require dirname(__DIR__) . '/config/config.php';

(new Zack($config))->run();
