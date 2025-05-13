<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\Routing\Route;

readonly class ParsedRoute
{
    public function __construct(
        public string $name,
        public Route $route,
        public int $priority = 0,
    ) {}
}
