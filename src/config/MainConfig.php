<?php declare(strict_types=1);

namespace tebe\zack\config;

use tebe\zack\routing\GenericRouteHandler;
use tebe\zack\routing\HtmlRouteHandler;
use tebe\zack\routing\MarkdownRouteHandler;
use tebe\zack\routing\PhpRouteHandler;

readonly class MainConfig
{
    public string $logPath;
    public string $name;
    public string $routePath;
    public string $basePath;
    public string $zackPath;
    /** @var array|string[][] */
    public array $routeHandlers;
    public LoggerConfig $logger;
    public PhpConfig $php;
    public TwigConfig $twig;

    public function __construct(array $config = [])
    {
        $this->zackPath = dirname(__DIR__);
        $this->basePath = $config['basePath'] ?? throw new \InvalidArgumentException('basePath is required');
        $this->logPath = $config['logPath'] ?? $this->basePath . '/logs';
        $this->name = $config['name'] ?? 'My application';
        $this->routeHandlers = array_merge([
            // concrete handlers
            'markdown' => [MarkdownRouteHandler::class, null],
            'md' => [MarkdownRouteHandler::class, null],
            'htm' => [HtmlRouteHandler::class, null],
            'html' => [HtmlRouteHandler::class, null],
            'php' => [PhpRouteHandler::class, null],
            // generic handler
            'json' => [GenericRouteHandler::class, 'application/json; charset=UTF-8'], // charset must be specified for JSON responses
            'txt' => [GenericRouteHandler::class, 'text/plain'],
            'xml' => [GenericRouteHandler::class, 'application/xml'],
        ], $config['routeHandlers'] ?? []);
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
