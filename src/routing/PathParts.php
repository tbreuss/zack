<?php declare(strict_types=1);

namespace tebe\zack\routing;

class PathParts
{
    public function __construct(
        public readonly string $filename,
        public readonly string $extension,
        public readonly string $method = 'any',
    ) {}
}
