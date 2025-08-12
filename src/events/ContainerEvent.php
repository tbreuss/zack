<?php declare(strict_types=1);

namespace tebe\zack\events;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Contracts\EventDispatcher\Event;

class ContainerEvent extends Event
{
    public function __construct(
        private readonly ContainerBuilder $container,
    ) {}

    public function getContainer(): ContainerBuilder
    {
        return $this->container;
    }
}
