<?php declare(strict_types=1);

namespace tebe\zack;

use tebe\zack\event\ContainerEvent;
use tebe\zack\event\ResponseEvent;
use tebe\zack\event\RoutesEvent;
use tebe\zack\routing\HtmlRouteHandler;
use tebe\zack\routing\JsonRouteHandler;
use tebe\zack\routing\PhpRouteHandler;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection;
use Symfony\Component\Finder\Finder;
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
    public function __construct(
        private Config $config,
        private EventDispatcher $dispatcher = new EventDispatcher(),        
        private DependencyInjection\ContainerBuilder $container = new DependencyInjection\ContainerBuilder(),        
    ) { }

    public function run(): void
    {
        ini_set('display_errors', 1);
        error_reporting(-1);

        $this->initContainer();
        $this->dispatcher->dispatch(new ContainerEvent($this->container), 'container');

        $request = Request::createFromGlobals();
        $requestStack = new RequestStack();
        $routes = $this->getRoutes();
        $this->dispatcher->dispatch(new RoutesEvent($routes), 'routes');

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

        $request->attributes->add(['_container' => $this->container]);
        $request->attributes->add(['routes' => $routes]);

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

    public function initContainer(): void
    {
        $this->container->register('twig_loader', Twig\Loader\FilesystemLoader::class)
            ->addArgument($this->config->twigTemplatePath);

        $this->container->register('twig', Twig\Environment::class)
            ->addArgument(new DependencyInjection\Reference('twig_loader'))
            ->addArgument([
                'cache' => $this->config->twigCachePath,
                'debug' => $this->config->twigDebug,
            ]);

    }

    private function getRoutes(): RouteCollection
    {
        $routes = new RouteCollection();

        $finder = new Finder();

        $finder->files()->in($this->config->routePath);
        
        foreach ($finder as $file) {
            $relativePath = $file->getRelativePathname();
            if (substr_count($relativePath, '.') <> 2) {
                throw new \Exception('Invalid file name: ' . $relativePath);
            }

            [$filename, $method, $extension] = explode('.', $relativePath);

            $id = str_replace(['[', ']'], '', $filename . '/' . $method);

            $route = '/' . $filename;
            if ($filename === 'index' || (($pos = strrpos($filename, 'index')) !== false)) {
                $route = '/' . rtrim(substr($filename, 0, strlen($filename) - 5), '/');
            }
            $route = str_replace(['[', ']'], ['{', '}'], $route);

            $controller = match($file->getExtension()) {
                'json' => JsonRouteHandler::class,
                'php' => PhpRouteHandler::class,
                'html' => HtmlRouteHandler::class,
                default => throw new \Exception('Unsupported file type: ' . $file->getExtension()),
            };

            $methods = match($method) {
                'get' => ['GET'],
                'post' => ['POST'],
                'put' => ['PUT'],
                'delete' => ['DELETE'],
                default => throw new \Exception('Unsupported method: ' . $fileParts[1]),
            };

            $path = $this->config->routePath . '/' . $file->getRelativePathname();

            $routes->add($id, new Route($route, [
                '_controller' => $controller,
                '_path' => $path,
            ], methods: $methods));
        }

        return $routes;
    }
}
