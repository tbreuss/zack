<?php declare(strict_types=1);

namespace tebe\zack;

use tebe\zack\config\MainConfig;
use tebe\zack\events\ContainerEvent;
use tebe\zack\routing\FileBasedRouter;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\ErrorHandler;
use Symfony\Component\EventDispatcher;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;
use Twig;
use Twig\Extra\Markdown\MarkdownExtension;
use Twig\Extra\Markdown\DefaultMarkdown;
use Twig\Extra\Markdown\MarkdownRuntime;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

class Zack
{
    private MainConfig $config;

    public function __construct(
        array $config,
        private EventDispatcher\EventDispatcher $dispatcher = new EventDispatcher\EventDispatcher(),
        private DependencyInjection\ContainerBuilder $container = new DependencyInjection\ContainerBuilder(),
    ) {
        $this->config = new MainConfig($config);
    }

    public function run(): void
    {
        error_reporting($this->config->php->errorLevel);
        ini_set('display_errors', $this->config->php->displayErrors ? '1' : '0');
        ini_set('display_startup_errors', $this->config->php->displayStartupErrors ? '1' : '0');
        ini_set('log_errors', $this->config->php->logErrors ? '1' : '0');
        ini_set('error_log', $this->config->php->errorLog);

        mb_internal_encoding('UTF-8');

        set_error_handler(function (int $severity, string $message, string $file = '', int $line = 0) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }, $this->config->php->errorLevel);

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
        $routes = (new FileBasedRouter($this->config->routePath, $this->dispatcher))->getRoutes();

        $this->container->set('config', $this->config);

        $this->container->register('logger', HttpKernel\Log\Logger::class)
            ->setArguments([
                $this->config->logger->minLevel,
                $this->config->logger->output,
                null, // formatter
                new Reference('request_stack'),
                false, // debug
            ]);

        $this->container->register('twig_loader', Twig\Loader\FilesystemLoader::class)
            ->addArgument($this->getTwigPaths());

        $this->container->register('twig', Twig\Environment::class)
            ->addArgument(new DependencyInjection\Reference('twig_loader'))
            ->addArgument([
                'debug' => $this->config->twig->debug,
                'charset' => $this->config->twig->charset,
                'strict_variables' => $this->config->twig->strictVariables,
                'autoescape' => $this->config->twig->autoescape,
                'cache' => $this->config->twig->cache,
                'auto_reload' => $this->config->twig->autoReload,
                'optimizations' => $this->config->twig->optimizations,
                'use_yield' => $this->config->twig->useYield,
            ])
            ->addMethodCall('addExtension', [new MarkdownExtension()])
            ->addMethodCall('addGlobal', ['config', $this->config])
            ->addMethodCall('addRuntimeLoader', [new class implements RuntimeLoaderInterface {
                public function load($class)
                {
                    if (MarkdownRuntime::class === $class) {
                        return new MarkdownRuntime(new DefaultMarkdown());
                    }
                    return null;
                }
            }]);

        $this->container->register('context', Routing\RequestContext::class);
        $this->container->register('matcher', Routing\Matcher\UrlMatcher::class)
            ->setArguments([$routes, new Reference('context')]);

        $this->container->register('request_stack', HttpFoundation\RequestStack::class);
        $this->container->register('controller_resolver', HttpKernel\Controller\ControllerResolver::class)
            ->setArguments([new Reference('logger')]);
        $this->container->register('argument_resolver', HttpKernel\Controller\ArgumentResolver::class);

        $this->container->register('listener.router', HttpKernel\EventListener\RouterListener::class)
            ->setArguments([
                new Reference('matcher'),
                new Reference('request_stack'),
                new Reference('context'),
                new Reference('logger'),
                null, // projectDir (used when displaying welcom screen)
                false, // debug (displays welcome screen if true)
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
                true, // handleAllThrowables
            ]);

        $this->dispatcher->dispatch(new ContainerEvent($this->container), 'zack.container');
    }

    private function getTwigPaths(): array
    {
        $twigPaths = [];
        if (is_dir($this->config->twig->templatePath)) {
            $twigPaths[] = $this->config->twig->templatePath;
        }
        $twigPaths[] = $this->config->zackPath . '/views';

        return $twigPaths;
    }
}
