<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\Routing\Route;

class ParsedRoute
{
    public function __construct(
        public readonly string $name,
        public readonly Route $route,
        public readonly int $priority = 0,
    ) {}
}
