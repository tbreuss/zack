<?php declare(strict_types=1);

namespace tebe\zack\config;

readonly class MainConfig
{
    public string $logPath;
    public string $routePath;
    public string $rootPath;
    public string $zackPath;
    public LoggerConfig $logger;
    public PhpConfig $php;
    public TwigConfig $twig;

    public function __construct(array $config = [])
    {
        $this->zackPath = dirname(__DIR__);
        $this->rootPath = $config['rootPath'] ?? throw new \InvalidArgumentException('rootPath is required');
        $this->routePath = $config['routePath'] ?? $this->rootPath . '/routes';
        $this->logPath = $config['logPath'] ?? $this->rootPath . '/logs';

        $this->logger = new LoggerConfig(
            $config['logger'] ?? [],
        );

        $this->php = new PhpConfig(
            $config['php'] ?? [],
            $this->logPath,
        );

        $this->twig = new TwigConfig(
            $config['twig'] ?? [],
            $this->rootPath,
        );
    }
}
