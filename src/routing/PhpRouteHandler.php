<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhpRouteHandler
{
    private ?ContainerBuilder $container;

    public function __invoke(Request $request): Response|array
    {
        $this->container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new Exception('Attribute _path not found in request attributes');
        }

        if (!file_exists($path)) {
            throw new Exception('PHP file not found for path: ' . $path);
        }

        $response = require $path;

        if (!$response instanceof Response && !is_array($response)) {
            throw new Exception('PHP file must return a response object or an array: ' . $path);
        }

        return $response;
    }

    public function html(string $template, array $context = []): Response
    {
        $twig = $this->container->get('twig');
        $html = $twig->render($template, $context);
        return new Response($html, 200);
    }

    public function json(array $context = []): Response
    {
        $json = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
