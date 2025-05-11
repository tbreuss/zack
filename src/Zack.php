<?php declare(strict_types=1);

namespace tebe\zack;

use tebe\zack\event\ContainerEvent;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ErrorHandler;
use Symfony\Component\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use tebe\zack\routing\FileBasedRouter;
use Twig;

class Zack
{
    public function __construct(
        private Config $config,
        private EventDispatcher\EventDispatcher $dispatcher = new EventDispatcher\EventDispatcher(),
        private DependencyInjection\ContainerBuilder $container = new DependencyInjection\ContainerBuilder(),
    ) {}

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

        $kernel = $this->container->get('httpkernel');

        $response = $kernel->handle($request);
        $response->send();

        $kernel->terminate($request, $response);
    }

    private function errorHandler(ErrorHandler\Exception\FlattenException $exception): HttpFoundation\Response
    {
        $content = $this->container->get('twig')->render('error.html.twig', [
            'exception' => $exception,
        ]);

        return new HttpFoundation\Response($content, $exception->getStatusCode());
    }

    public function initContainer(): void
    {
        $routes = (new FileBasedRouter($this->config, $this->dispatcher))->getRoutes();

        $this->container->register('twig_loader', Twig\Loader\FilesystemLoader::class)
            ->addArgument($this->getTwigPaths());

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
            ->setArguments([
                new Reference('matcher'),
                new Reference('request_stack'),
                null, // context
                null, // logger
                null, // projectDir
                false, // debug
            ]);

        $this->container->register('listener.response', HttpKernel\EventListener\ResponseListener::class)
            ->setArguments(['UTF-8']);
        $this->container->register('listener.exception', HttpKernel\EventListener\ErrorListener::class)
            ->setArguments([$this->errorHandler(...)]);

        // use the injected dispatcher
        $this->dispatcher->addSubscriber($this->container->get('listener.router'));
        $this->dispatcher->addSubscriber($this->container->get('listener.response'));
        $this->dispatcher->addSubscriber($this->container->get('listener.exception'));

        $this->container->register('httpkernel', HttpKernel\HttpKernel::class)
            ->setArguments([
                $this->dispatcher,
                new Reference('controller_resolver'),
                new Reference('request_stack'),
                new Reference('argument_resolver'),
            ]);

        $this->dispatcher->dispatch(new ContainerEvent($this->container), 'zack.container');
    }

    private function getTwigPaths(): array
    {
        $twigPaths = [];
        if (is_dir($this->config->twigTemplatePath)) {
            $twigPaths[] = $this->config->twigTemplatePath;
        }
        $twigPaths[] = $this->config->zackPath . '/views';

        return $twigPaths;
    }
}
