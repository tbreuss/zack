<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\EventDispatcher;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;
use tebe\zack\events\ControllerEvent;
use tebe\zack\events\RoutesEvent;

readonly class FileBasedRouter
{
    public function __construct(
        private string $routePath,
        private array $routeHandlers,
        private EventDispatcher\EventDispatcher $eventDispatcher,
    ) {}

    public function getRoutes(): Routing\RouteCollection
    {
        $routes = new Routing\RouteCollection();

        $finder = new Finder();

        $finder->files()
            ->in($this->routePath)
            ->name($this->getFileMatchRules())
            ->sort(function (\SplFileInfo $a, \SplFileInfo $b): int {
                // temporary solution to have named parameter routes in last place
                $a = str_replace(['[', ']'], '~', $a->getRealPath());
                $b = str_replace(['[', ']'], '~', $b->getRealPath());
                return strcmp($a, $b);
            });

        $catchAllRoute = false;

        foreach ($finder as $file) {
            if (!$catchAllRoute && ($parsedRoute = $this->parseCatchAllRoute($file)) !== null) {
                $routes->add($parsedRoute->name, $parsedRoute->route, $parsedRoute->priority);
                $catchAllRoute = true;
            } elseif (($parsedRoute = $this->parseCatchAllParamsRoute($file)) !== null) {
                $routes->add($parsedRoute->name, $parsedRoute->route, $parsedRoute->priority);
            } else {
                $parsedRoute = $this->parseRoute($file);
                $routes->add($parsedRoute->name, $parsedRoute->route, $parsedRoute->priority);
            }
        }

        $this->eventDispatcher->dispatch(new RoutesEvent($routes), 'zack.routes');

        return $routes;
    }

    private function parseCatchAllRoute(SplFileInfo $fileInfo): ?ParsedRoute
    {
        $count = substr_count($fileInfo->getRelativePathname(), '[...]');

        if ($count > 1) {
            throw new \Exception('Error parsing file name: ' . $fileInfo->getRelativePathname());
        } elseif ($count === 0) {
            return null;
        }

        $relativePathname = str_replace('[...]', '[path]', $fileInfo->getRelativePathname());
        [$filename, $method] = $this->getPathParts($relativePathname);
        [$controller, $contentType] = $this->matchController($fileInfo->getExtension());

        $defaults = [
            '_controller' => $controller,
            '_path' => $fileInfo->getRealPath(),
            '_contentType' => $contentType,
        ];

        $requirements = [
            'path' => '.+',
        ];

        return new ParsedRoute(
            name: $this->getName('catch-all', $method),
            route: new Route(
                path: $this->getRoute($filename),
                defaults: $defaults,
                requirements: $requirements,
                methods: $this->matchMethods($method),
            ),
            priority: PHP_INT_MIN,
        );
    }

    private function parseCatchAllParamsRoute(SplFileInfo $fileInfo): ?ParsedRoute
    {
        $status = preg_match_all('/\[\.{3}([a-z]+)]/', $fileInfo->getRelativePathname(), $matches);

        if ($status === false) {
            throw new \Exception('Error parsing file name: ' . $fileInfo->getRelativePathname());
        } elseif ($status > 1) {
            throw new \Exception('Error parsing file name: ' . $fileInfo->getRelativePathname());
        } elseif ($status === 0) {
            return null;
        }

        $relativePathname = str_replace('...', '', $fileInfo->getRelativePathname());
        [$controller, $contentType] = $this->matchController($fileInfo->getExtension());

        $defaults = [
            '_controller' => $controller,
            '_path' => $fileInfo->getRealPath(),
            '_contentType' => $contentType,
        ];

        $requirements = [
            $matches[1][0] => '.+',
        ];

        [$filename, $method] = $this->getPathParts($relativePathname);

        return new ParsedRoute(
            name: $this->getName($filename, $method),
            route: new Route(
                path: $this->getRoute($filename),
                defaults: $defaults,
                requirements: $requirements,
                methods: $this->matchMethods($method),
            ),
            priority: 0, // TODO use priority
        );
    }

    private function parseRoute(SplFileInfo $fileInfo): ParsedRoute
    {
        $relativePath = $fileInfo->getRelativePathname();

        [$filename, $method, $extension] = $this->getPathParts($relativePath);
        [$controller, $contentType] = $this->matchController($extension);

        return new ParsedRoute(
            name: $this->getName($filename, $method),
            route: new Route(
                path: $this->getRoute($filename),
                defaults: [
                    '_controller' => $controller,
                    '_path' => $this->getPath($relativePath),
                    '_contentType' => $contentType,
                ],
                requirements: [],
                methods: $this->matchMethods($method),
            ),
            priority: 0, // TODO use priority
        );
    }

    private function getPathParts(string $relativePath): array
    {
        $relativePathParts = explode('.', $relativePath);

        if (count($relativePathParts) === 2) {
            [$filename, $extension] = $relativePathParts;
            return [$filename, 'any', $extension];
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

    private function matchController(string $extension): array
    {
        $event = new ControllerEvent($extension);
        $this->eventDispatcher->dispatch($event, 'zack.controller');

        if (($controller = $event->getController()) !== null) {
            return [$controller, null]; // todo
        }

        foreach ($this->routeHandlers as $extensionToMatch => [$controller, $contentType]) {
            if ($extensionToMatch === $extension) {
                return [$controller, $contentType];
            }
        }

        throw new \Exception('Unsupported file type: ' . $extension);
    }

    private function matchMethods(string $method): array
    {
        return match ($method) {
            'any' => ['DELETE', 'GET', 'HEAD', 'OPTIONS', 'PATCH', 'POST', 'PUT'],
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
        return $this->routePath . '/' . $relativePath;
    }

    private function getFileMatchRules(): string
    {
        $extensions = join(
            separator: ',',
            array: array_keys($this->routeHandlers),
        );

        return '*.{' . $extensions . '}';
    }
}
