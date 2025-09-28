<?php declare(strict_types=1);

namespace tebe\zack\config;

readonly class MainConfig
{
    public string $logPath;
    public string $name;
    public string $routePath;
    public string $basePath;
    public string $zackPath;
    /** @var string[] */
    public array $coreFileExtensions;
    /** @var array<string, string> */
    public array $additionalFileTypes;
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

        $this->coreFileExtensions = [
            'htm',
            'html',
            'markdown',
            'md',
            'php',
        ];

        $this->additionalFileTypes = array_merge([
            'csv' => 'text/csv',
            'json' => 'application/json; charset=UTF-8',
            'txt' => 'text/plain',
            'xml' => 'application/xml',
        ], $config['additionalFileTypes'] ?? []);

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
