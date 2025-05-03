<?php declare(strict_types=1);

use tebe\zack\Config;

return new Config([
    'rootPath' => dirname(__DIR__, 1),
    'twigCache' => false,
    'phpDisplayErrors' => true,
]);
