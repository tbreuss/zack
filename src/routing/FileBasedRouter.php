<?php declare(strict_types=1);

namespace tebe\zack\routing;

use tebe\zack\Config;
use tebe\zack\event\RoutesEvent;
use Symfony\Component\EventDispatcher;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;

class FileBasedRouter
{
    public function __construct(
        private Config $config,
        private EventDispatcher\EventDispatcher $dispatcher,
    ) { }

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

        $catchAllRoute = null;

        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();

            if (!$catchAllRoute && $this->isCatchAllRoute($relativePath)) {
                $catchAllRoute = $file;
                continue;
            }

            [$status, $params] = $this->catchAllParams($relativePath);
            if ($status) {
                [$path, $param, $method, $extension] = $params;
                $routes->add($this->getName($path, $method), new Route($path, [
                    '_controller' => $this->getController($extension),
                    '_path' => $this->getPath($relativePath),
                ], requirements: [
                    $param => '.+',
                ], methods: $this->getMethods($method)));
                continue;
            }

            [$filename, $method, $extension] = $this->getPathParts($relativePath);

            $routes->add($this->getName($filename, $method), new Route($this->getRoute($filename), [
                '_controller' => $this->getController($extension),
                '_path' => $this->getPath($relativePath),
            ], methods: $this->getMethods($method)));
        }

        if ($catchAllRoute) {
            $extension = pathinfo($catchAllRoute->getRelativePathname(), PATHINFO_EXTENSION);
            $routes->add('catch-all', new Route('/{path}', [
                '_controller' => $this->getController($extension),
                '_path' => $catchAllRoute->getRealPath(),
            ], requirements: [
                'path' => '.+',
            ]));
        }

        $this->dispatcher->dispatch(new RoutesEvent($routes), 'routes');

        return $routes;
    }

    private function isCatchAllRoute(string $relativePath): bool
    {
        return str_contains($relativePath, '[...]');
    }

    private function catchAllParams(string $relativePath): array
    {
        // See https://regex101.com/r/btDcBq/1
        $status = preg_match('/([A-Za-z0-9-_]+\/)*(\[\.{3})([a-z]+)(\]{1})(\.[a-z]+)*\.([a-z]+)/', $relativePath, $matches);
        
        if ($status > 0) {
            return [true, [
                $matches[1] . '{' . $matches[3] . '}',
                $matches[3],
                trim($matches[5] ?: 'get', '.'),
                $matches[6],
            ]];
        }

        return [false, []];
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

    private function getController(string $extension): string
    {
        return match ($extension) {
            'html' => HtmlRouteHandler::class,
            'json' => JsonRouteHandler::class,
            'php' => PhpRouteHandler::class,
            default => throw new \Exception('Unsupported file type: ' . $extension),
        };
    }

    private function getMethods(string $method): array
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
