<?php declare(strict_types=1);

namespace tebe\zack\routing;

use tebe\zack\Config;
use tebe\zack\event\ControllerEvent;
use tebe\zack\event\RoutesEvent;
use Symfony\Component\EventDispatcher;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;

class FileBasedRouter
{
    public function __construct(
        private Config $config,
        private EventDispatcher\EventDispatcher $dispatcher,
    ) {}

    public function getRoutes(): Routing\RouteCollection
    {
        $routes = new Routing\RouteCollection();

        $finder = new Finder();

        $finder->files()
            ->in($this->config->routePath)
            ->sort(function (\SplFileInfo $a, \SplFileInfo $b): int {
                // temporary solution to have named parameter routes in last place
                $a = str_replace(['[', ']'], '~', $a->getRealPath());
                $b = str_replace(['[', ']'], '~', $b->getRealPath());
                return strcmp($a, $b);
            });

        $catchAllRoute = false;

        foreach ($finder as $file) {
            if (!$catchAllRoute) {
                [$status, $name, $route, $priority] = $this->parseCatchAllRoute($file);

                if ($status) {
                    $routes->add($name, $route, $priority);
                    $catchAllRoute = true;
                    continue;
                }    
            }

            [$status, $name, $route, $priority] = $this->parseCatchAllParamsRoute($file);

            if ($status) {
                $routes->add($name, $route, $priority);
                continue;
            }

            [$name, $route, $priority] = $this->parseRoute($file);

            $routes->add($name, $route, $priority);
        }

        $this->dispatcher->dispatch(new RoutesEvent($routes), 'zack.routes');

        return $routes;
    }

    private function parseCatchAllRoute(SplFileInfo $fileInfo): array
    {
        $count = substr_count($fileInfo->getRelativePathname(), '[...]');

        if ($count > 1) {
            throw new \Exception('Error parsing file name: ' . $fileInfo->getRelativePathname());
        }

        if ($count === 0) {
            return [false, null, null, 0];
        }

        $relativePathname = str_replace('[...]', '[path]', $fileInfo->getRelativePathname());
        [$filename, $method] = $this->getPathParts($relativePathname);

        $defaults = [
            '_controller' => $this->matchController($fileInfo->getExtension()),
            '_path' => $fileInfo->getRealPath(),
        ];

        $requirements = [
            'path' => '.+',
        ];

        return [
            true, 
            $this->getName('catch-all', $method), 
            new Route(
                path: $this->getRoute($filename),
                defaults: $defaults, 
                requirements: $requirements, 
                methods: $this->matchMethods($method),
            ),
            PHP_INT_MIN,
        ];
    }

    private function parseCatchAllParamsRoute(SplFileInfo $fileInfo): array
    {
        $status = preg_match_all('/\[\.{3}([a-z]+)\]/', $fileInfo->getRelativePathname(), $matches);

        if ($status === false) {
            throw new \Exception('Error parsing file name: ' . $fileInfo->getRelativePathname());
        }

        if ($status > 1) {
            throw new \Exception('Error parsing file name: ' . $fileInfo->getRelativePathname());
        }
        
        if ($status === 0) {
            return [false, null, null, 0];
        }

        $relativePathname = str_replace('...', '', $fileInfo->getRelativePathname());

        $defaults = [
            '_controller' => $this->matchController($fileInfo->getExtension()),
            '_path' => $fileInfo->getRealPath(),
        ];

        $requirements = [
            $matches[1][0] => '.+',
        ];

        [$filename, $method] = $this->getPathParts($relativePathname);

        return [
            true, 
            $this->getName($filename, $method), 
            new Route(
                path: $this->getRoute($filename),
                defaults: $defaults, 
                requirements: $requirements, 
                methods: $this->matchMethods($method),
            ),
            0, // TODO use priority
        ];
    }

    private function parseRoute(SplFileInfo $fileInfo): array
    {
        $relativePath = $fileInfo->getRelativePathname();

        [$filename, $method, $extension] = $this->getPathParts($relativePath);

        return [
            $this->getName($filename, $method),
            new Route(
                path: $this->getRoute($filename), 
                defaults: [
                    '_controller' => $this->matchController($extension),
                    '_path' => $this->getPath($relativePath),
                ], 
                methods: $this->matchMethods($method),
            ),
            0, // TODO use priority
        ];
    }

    private function getPathParts(string $relativePath): array
    {
        $relativePathParts = explode('.', $relativePath);

        if (count($relativePathParts) === 2) {
            [$filename, $extension] = $relativePathParts;
            return [$filename, 'get', $extension];
        } elseif (count($relativePathParts) === 3) {
            return [$filename, $method, $extension] = $relativePathParts;
        } else {
            throw new \Exception('Invalid file name format: ' . $relativePath);
        }
    }

    private function getName(string $filename, string $method): string
    {
        return str_replace(['[', ']'], '', $filename . '/' . $method);
    }

    private function getRoute(string $filename): string
    {
        $route = '/' . $filename;
        if ($filename === 'index' || (($pos = strrpos($filename, 'index')) !== false)) {
            $route = '/' . rtrim(substr($filename, 0, strlen($filename) - 5), '/');
        }
        return str_replace(['[', ']'], ['{', '}'], $route);
    }

    private function matchController(string $extension): string
    {
        $event = new ControllerEvent($extension);
        $this->dispatcher->dispatch($event, 'zack.controller');

        if (($controller = $event->getController()) !== null) {
            return $controller;
        }

        $controller = match ($extension) {
            'htm', 'html' => HtmlRouteHandler::class,
            'json' => JsonRouteHandler::class,
            'markdown', 'md' => MarkdownRouteHandler::class,
            'php' => PhpRouteHandler::class,
            default => throw new \Exception('Unsupported file type: ' . $extension),
        };

        return $controller;
    }

    private function matchMethods(string $method): array
    {
        return match ($method) {
            'delete' => ['DELETE'],
            'get' => ['GET'],
            'head' => ['HEAD'],
            'options' => ['OPTIONS'],
            'patch' => ['PATCH'],
            'post' => ['POST'],
            'put' => ['PUT'],
            default => throw new \Exception('Unsupported method: ' . $method),
        };
    }

    private function getPath(string $relativePath): string
    {
        return $this->config->routePath . '/' . $relativePath;
    }
}
