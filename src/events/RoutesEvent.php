<?php declare(strict_types=1);

namespace tebe\zack\events;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Routing\RouteCollection;

class RoutesEvent extends Event
{
    public function __construct(
        private readonly RouteCollection $routes,
    ) {}

    public function getRoutes(): RouteCollection
    {
        return $this->routes;
    }
}
