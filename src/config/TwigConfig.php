<?php declare(strict_types=1);

namespace tebe\zack\config;

use Twig\Cache\CacheInterface;

readonly class TwigConfig
{
    public array|string $templatePath;
    public bool|string|CacheInterface $cache;
    public bool $debug;
    public string $charset;
    public bool $strictVariables;
    public bool|string $autoescape;
    public ?bool $autoReload;
    public int $optimizations;
    public bool $useYield;

    public function __construct(array $config, string $basePath)
    {
        $this->templatePath = $config['templatePath'] ?? $basePath . '/views';
        $this->cache = $config['cache'] ?? $basePath . '/cache/twig';
        $this->debug = $config['debug'] ?? false;
        $this->charset = $config['charset'] ?? 'UTF-8';
        $this->autoescape = $config['autoescape'] ?? 'html';
        $this->autoReload = $config['autoReload'] ?? null;
        $this->strictVariables = $config['strictVariables'] ?? false;
        $this->optimizations = $config['optimizations'] ?? -1;
        $this->useYield = $config['useYield'] ?? false;
    }
}
