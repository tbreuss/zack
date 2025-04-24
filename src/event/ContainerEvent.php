<?php declare(strict_types=1);

namespace tebe\zack\event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ContainerEvent extends Event
{
    public function __construct(
        private ContainerBuilder $container,
    ) {
    }

    public function getContainer(): ContainerBuilder
    {
        return $this->container;
    }
}
