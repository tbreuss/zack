<?php declare(strict_types=1);

namespace tebe\zack\config;

readonly class LoggerConfig
{
    public ?string $minLevel;
    public ?string $output;

    /**
     * @param mixed[] $config
     */
    public function __construct(array $config)
    {
        $this->minLevel = $config['minLevel'] ?? null;
        $this->output = $config['output'] ?? null;
    }
}
