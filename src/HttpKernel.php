<?php declare(strict_types=1);

namespace tebe\zack;

use Symfony\Component\HttpKernel\Controller;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing;

class HttpKernel implements HttpKernelInterface
{
    public function __construct(
        private Config $config,
        private ContainerBuilder $container,
        private RouteCollection $routes,
    ){} 

    public function handle(Request $request, int $type = self::MAIN_REQUEST, bool $catch = true): Response
    {
        $context = new Routing\RequestContext();
        $context->fromRequest($request);        
        $matcher = new Routing\Matcher\UrlMatcher($this->routes, $context);

        $controllerResolver = new Controller\ControllerResolver();
        $argumentResolver = new Controller\ArgumentResolver();

        try {
            $route = $matcher->match($request->getPathInfo());
            $request->attributes->add($route);
            $request->attributes->add(['twig' => $this->container->get('twig')]);

            $controller = $controllerResolver->getController($request);
            $arguments = $argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (Routing\Exception\ResourceNotFoundException $exception) {
            $response = new Response('Not Found', 404);
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $response = new Response('An error occurred: ' . $message, 500);
        }

        return $response;
    }
}
