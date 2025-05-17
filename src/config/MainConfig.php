<?php declare(strict_types=1);

namespace tebe\zack\config;

readonly class MainConfig
{
    public string $logPath;
    public string $name;
    public string $routePath;
    public string $basePath;
    public string $zackPath;
    public LoggerConfig $logger;
    public PhpConfig $php;
    public TwigConfig $twig;

    public function __construct(array $config = [])
    {
        $this->zackPath = dirname(__DIR__);
        $this->basePath = $config['basePath'] ?? throw new \InvalidArgumentException('basePath is required');
        $this->logPath = $config['logPath'] ?? $this->basePath . '/logs';
        $this->name = $config['name'] ?? 'My application';
        $this->routePath = $config['routePath'] ?? $this->basePath . '/routes';

        $this->logger = new LoggerConfig(
            $config['logger'] ?? [],
        );

        $this->php = new PhpConfig(
            $config['php'] ?? [],
            $this->logPath,
        );

        $this->twig = new TwigConfig(
            $config['twig'] ?? [],
            $this->basePath,
        );
    }
}
