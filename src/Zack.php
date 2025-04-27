<?php declare(strict_types=1);

namespace tebe\zack;

use tebe\zack\event\ContainerEvent;
use tebe\zack\event\ResponseEvent;
use tebe\zack\event\RoutesEvent;
use tebe\zack\routing\HtmlRouteHandler;
use tebe\zack\routing\JsonRouteHandler;
use tebe\zack\routing\PhpRouteHandler;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ErrorHandler;
use Symfony\Component\EventDispatcher;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Route;
use Twig;

class Zack 
{
    public function __construct(
        private Config $config,
        private EventDispatcher\EventDispatcher $dispatcher = new EventDispatcher(),        
        private DependencyInjection\ContainerBuilder $container = new DependencyInjection\ContainerBuilder(),        
    ) { }

    public function run(): void
    {
        error_reporting($this->config->phpErrorReporting);
        ini_set('display_errors', $this->config->phpDisplayErrors ? '1' : '0');
        ini_set('display_startup_errors', $this->config->phpDisplayStartupErrors ? '1' : '0');
        ini_set('log_errors', $this->config->phpLogErrors ? '1' : '0');
        ini_set('error_log', $this->config->phpErrorLog);

        $this->initContainer();

        $request = HttpFoundation\Request::createFromGlobals();
        $request->attributes->add(['_container' => $this->container]);

        $response = $this->container->get('httpkernel')->handle($request);
        $this->dispatcher->dispatch(new ResponseEvent($response, $request), 'response');

        $response->send();
    }

    private function errorHandler(ErrorHandler\Exception\FlattenException $exception): HttpFoundation\Response
    {
        $content = $this->container->get('twig')->render('error.html.twig', [
            'title' => 'Error',
            'exception' => $exception,
        ]);
        
        return new HttpFoundation\Response($content, $exception->getStatusCode());
    }

    public function initContainer(): void
    {
        $routes = $this->getRoutes();

        $this->container->register('twig_loader', Twig\Loader\FilesystemLoader::class)
            ->addArgument($this->config->twigTemplatePath);

        $this->container->register('twig', Twig\Environment::class)
            ->addArgument(new DependencyInjection\Reference('twig_loader'))
            ->addArgument([
                'debug' => $this->config->twigDebug,
                'charset' => $this->config->twigCharset,
                'strict_variables' => $this->config->twigStrictVariables,
                'autoescape' => $this->config->twigAutoescape,
                'cache' => $this->config->twigCache,
                'auto_reload' => $this->config->twigAutoReload,
                'optimizations' => $this->config->twigOptimizations,
                'use_yield' => $this->config->twigUseYield,
            ]);

        $this->container->register('context', Routing\RequestContext::class);
        $this->container->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments([$routes, new Reference('context')]);

        $this->container->register('request_stack', HttpFoundation\RequestStack::class);
        $this->container->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class);
        $this->container->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);
        
        $this->container->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([new Reference('matcher'), new Reference('request_stack')]);
        $this->container->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8']);
        $this->container->register('listener.exception', HttpKernel\EventListener\ErrorListener::class)
            ->setArguments([$this->errorHandler(...)]);

        $this->container->register('dispatcher', EventDispatcher\EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('listener.router')])
            ->addMethodCall('addSubscriber', [new Reference('listener.response')])
            ->addMethodCall('addSubscriber', [new Reference('listener.exception')]);

        $this->container->register('httpkernel', HttpKernel\HttpKernel::class)
            ->setArguments([
                new Reference('dispatcher'),
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ]);

        $this->dispatcher->dispatch(new ContainerEvent($this->container), 'container');
    }

    private function getRoutes(): Routing\RouteCollection
    {
        $routes = new Routing\RouteCollection();

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

        $this->dispatcher->dispatch(new RoutesEvent($routes), 'routes');

        return $routes;
    }
}
