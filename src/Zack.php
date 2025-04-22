<?php declare(strict_types=1);

namespace tebe\zack;

use tebe\zack\event\ResponseEvent;
use tebe\zack\routing\HtmlRouteHandler;
use tebe\zack\routing\PhpRouteHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Twig;

class Zack 
{
    private Config $config;
    private DependencyInjection\ContainerBuilder $container;

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

        $this->container = $this->getContainer();

        $request = Request::createFromGlobals();
        $request->attributes->add(['twig' => $this->container->get('twig')]);

        $requestStack = new RequestStack();
        $routes = $this->getRoutes();

        $context = new Routing\RequestContext();
        $matcher = new Routing\Matcher\UrlMatcher($routes, $context);

        $controllerResolver = new HttpKernel\Controller\ControllerResolver();
        $argumentResolver = new HttpKernel\Controller\ArgumentResolver();

        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new HttpKernel\EventListener\RouterListener($matcher, $requestStack));
        $dispatcher->addSubscriber(new HttpKernel\EventListener\ErrorListener($this->errorHandler(...)));

        $httpKernel = new HttpKernel\HttpKernel(
            $dispatcher,
            $controllerResolver,
            $requestStack,
            $argumentResolver
        );

        $response = $httpKernel->handle($request);

        $this->dispatcher->dispatch(new ResponseEvent($response, $request), 'response');

        $response->send();
    }

    private function errorHandler(\Symfony\Component\ErrorHandler\Exception\FlattenException $exception): Response
    {
        $content = $this->container->get('twig')->render('error.html.twig', [
            'title' => 'Error',
            'exception' => $exception,
        ]);
        
        return new Response($content, $exception->getStatusCode());
    }

    public function getContainer(): DependencyInjection\ContainerBuilder
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
