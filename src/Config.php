<?php declare(strict_types=1);

namespace tebe\zack;

readonly class Config
{
    public string $routePath;
    public string $rootPath;
    public string $twigTemplatePath;
    public bool|string $twigCachePath;
    public bool $twigDebug;

    public function __construct(array $config = [])
    {
        $this->init($config);
    }
    
    private function init(array $config): void
    {
        foreach ($config as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new \InvalidArgumentException("Invalid option: $key");
            }
        }

        $rootPath = $config['rootPath'] ?? throw new \InvalidArgumentException('rootPath is required');

        $this->routePath = $config['routePath'] ?? $rootPath . '/routes';
        $this->rootPath = $rootPath;
        $this->twigTemplatePath = $config['twigTemplatePath'] ?? $rootPath . '/views';
        $this->twigCachePath = $config['twigCachePath'] ?? $rootPath . '/cache/twig';
        $this->twigDebug = $config['twigDebug'] ?? false;
    }
}
