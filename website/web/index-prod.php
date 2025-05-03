<?php declare(strict_types=1);

ini_set('display_errors', '1');
require_once dirname(__DIR__) . '/vendor/autoload.php';

use tebe\zack\Zack;
use tebe\zack\Config;

$config = new Config([
    'rootPath' => dirname(__DIR__),
    'twigCache' => false,
]);

$dispatcher = require dirname(__DIR__) . '/config/event-dispatcher.php';

(new Zack($config, $dispatcher))->run();
