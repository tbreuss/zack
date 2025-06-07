<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhpRouteHandler
{
    private ?ContainerBuilder $container;

    public function __invoke(Request $request): Response
    {
        $this->container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        if (!file_exists($path)) {
            throw new \Exception('PHP file not found for path: ' . $path);
        }

        ob_start();
        $response = require $path;
        $contents = ob_get_clean();

        if ($response === 1 && is_string($contents)) {
            return new Response($contents, 200);
        } elseif (is_string($response) && is_string($contents) && strlen($contents) > 0) {
            throw new \Exception('In the PHP file the return value must be omitted if an output was made via echo: ' . $path);
        } elseif (is_string($response)) {
            return new Response($response, 200);
        } elseif (is_array($response)) {
            return $this->json($response);
        } elseif ($response instanceof Response) {
            return $response;
        } else {
            throw new \Exception('The PHP file must output something or return a string, an array or a response object: ' . $path);
        }
    }

    public function html(string $template, array $context = []): Response
    {
        $html = $this->render($template, $context);
        return new Response($html, 200);
    }

    public function json(array $context = []): Response
    {
        $json = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function render(string $template, array $context = []): string
    {
        $twig = $this->container->get('twig');
        return $twig->render($template, $context);
    }
}
