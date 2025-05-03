<?php declare(strict_types=1);

namespace tebe\zack\event;

use Symfony\Contracts\EventDispatcher\Event;

class ControllerEvent extends Event
{
    public function __construct(
        private string $extension,
        private ?string $controller = null,
    ) {}

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getResolvedController(): ?string
    {
        return $this->controller;
    }

    public function setResolvedController(string $controller): void
    {
        $this->controller = $controller;
    }
}
