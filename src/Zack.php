<?php declare(strict_types=1);

namespace tebe\zack;

use tebe\zack\event\ResponseEvent;
use tebe\zack\HtppKernel;
use tebe\zack\routing\HtmlRouteHandler;
use tebe\zack\routing\PhpRouteHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Twig;

class Zack 
{
    private Config $config;

    public function __construct(
        array $config = [],
        private EventDispatcher $dispatcher = new EventDispatcher(),        
    ) {
        $this->config = new Config($config);        
    }

    public function run(): void
    {
        ini_set('display_errors', 1);
        error_reporting(-1);

        $request = Request::createFromGlobals();

        $httpKernel = new HttpKernel(
            $this->config,
            $this->getContainer(),
            $this->getRoutes()
        );

        $response = $httpKernel->handle($request);

        $this->dispatcher->dispatch(new ResponseEvent($response, $request), 'response');

        $response->send();
    }

    private function getContainer(): DependencyInjection\ContainerBuilder
    {
        $container = new DependencyInjection\ContainerBuilder();

        $container->register('twig_loader', Twig\Loader\FilesystemLoader::class)
            ->addArgument($this->config->twigTemplatePath);

        $container->register('twig', Twig\Environment::class)
            ->addArgument(new DependencyInjection\Reference('twig_loader'))
            ->addArgument([
                'cache' => $this->config->twigCachePath,
                'debug' => $this->config->twigDebug,
            ]);

        $container->register('php_route_handler', PhpRouteHandler::class)
            ->addArgument(new DependencyInjection\Reference('twig'));

        return $container;
    }

    private function getRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $routes->add('index', new Route('/', [
            '_controller' => HtmlRouteHandler::class,
            '_path' => $this->config->routePath . '/index.get.html',
        ], methods: ['GET']));

        $routes->add('articles', new Route('/articles', [
            '_controller' => PhpRouteHandler::class,
            '_path' => $this->config->routePath . '/articles/index.get.php',
        ], methods: ['GET']));

        $routes->add('articles_id', new Route('/articles/{id}', [
            '_controller' => PhpRouteHandler::class,
            '_path' => $this->config->routePath . '/articles/[id]/index.get.php',
        ], methods: ['GET']));

        $routes->add('articles_id_comments_get', new Route('/articles/{id}/comments', [
            '_controller' => PhpRouteHandler::class,
            '_path' => $this->config->routePath . '/articles/[id]/comments.get.php',
        ], methods: ['GET']));

        $routes->add('articles_id_comments_post', new Route('/articles/{id}/comments', [
            '_controller' => PhpRouteHandler::class,
            '_path' => $this->config->routePath . '/articles/[id]/comments.post.php',
        ], methods: ['POST']));

        return $routes;
    }
}
