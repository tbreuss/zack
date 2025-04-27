<?php declare(strict_types=1);

namespace tebe\zack;

readonly class Config
{
    public string $routePath;
    public string $rootPath;
    // Twig
    public array|string $twigTemplatePath;
    public bool|string|\Twig\Cache\CacheInterface $twigCache;
    public bool $twigDebug;
    public string $twigCharset;
    public bool $twigStrictVariables;
    public bool|string $twigAutoescape;
    public ?bool $twigAutoReload;
    public int $twigOptimizations;
    public bool $twigUseYield;

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
        // Twig
        $this->twigTemplatePath = $config['twigTemplatePath'] ?? $rootPath . '/views';
        $this->twigCache = $config['twigCache'] ?? $rootPath . '/cache/twig';
        $this->twigDebug = $config['twigDebug'] ?? false;
        $this->twigCharset = $config['twigCharset'] ?? 'UTF-8';
        $this->twigAutoescape = $config['twigAutoescape'] ?? 'html';
        $this->twigAutoReload = $config['twigAutoReload'] ?? null;
        $this->twigStrictVariables = $config['twigStrictVariables'] ?? false;
        $this->twigOptimizations = $config['twigOptimizations'] ?? -1;
        $this->twigUseYield = $config['twigUseYield'] ?? false;
    }
}
