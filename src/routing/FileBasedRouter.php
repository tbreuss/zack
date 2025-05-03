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

        $catchAllFile = null;
        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();

            if (str_contains($relativePath, '[...]')) {
                $catchAllFile = $file;
                continue;
            }

            [$filename, $method, $extension] = $this->getPathParts($relativePath);

            $routes->add($this->getName($filename, $method), new Route($this->getRoute($filename), [
                '_controller' => $this->getController($extension),
                '_path' => $this->getPath($relativePath),
            ], methods: $this->getMethods($method)));
        }

        if ($catchAllFile) {
            $filename = '[...]';
            $extension = pathinfo($catchAllFile->getRelativePathname(), PATHINFO_EXTENSION);
            $routes->add('catch-all', new Route('/{path}', [
                '_controller' => $this->getController($extension),
                '_path' => $catchAllFile->getRealPath(),
            ], requirements: [
                'path' => '.+',
            ]));
        }

        $this->dispatcher->dispatch(new RoutesEvent($routes), 'routes');

        return $routes;
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
            'json' => JsonRouteHandler::class,
            'php' => PhpRouteHandler::class,
            'html' => HtmlRouteHandler::class,
            default => throw new \Exception('Unsupported file type: ' . $extension),
        };
    }

    private function getMethods(string $method): array
    {
        return match ($method) {
            'get' => ['GET'],
            'post' => ['POST'],
            'put' => ['PUT'],
            'delete' => ['DELETE'],
            default => throw new \Exception('Unsupported method: ' . $method),
        };
    }

    private function getPath(string $relativePath): string
    {
        return $this->config->routePath . '/' . $relativePath;
    }
}
