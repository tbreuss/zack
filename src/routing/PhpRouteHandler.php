<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\html_contains_full_html;
use function tebe\zack\html_extract_layout;
use function tebe\zack\html_extract_title;

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
        $returnValue = require $path;
        $outputValue = ob_get_clean();

        if ($returnValue === 1 && is_string($outputValue)) {
            if (html_contains_full_html($outputValue)) {
                return new Response($outputValue, 200);
            } else {
                $layout = html_extract_layout($outputValue);
                $title = html_extract_title($outputValue, basename($path));
                return $this->html($layout, ['title' => $title, 'html' => $outputValue]);
            }
        } elseif (is_string($returnValue)) {
            if (is_string($outputValue) && strlen($outputValue) > 0) {
                throw new \Exception('In the PHP file the return value must be omitted if an output was made via echo: ' . $path);
            }
            return new Response($returnValue, 200);
        } elseif (is_array($returnValue)) {
            return $this->json($returnValue);
        } elseif ($returnValue instanceof Response) {
            return $returnValue;
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

    public function redirect(string $url, int $status = 302): Response
    {
        return new RedirectResponse($url, $status);
    }

    public function render(string $template, array $context = []): string
    {
        /** @var \Twig\Environment $twig */
        $twig = $this->container->get('twig');
        return $twig->render($template, $context);
    }
}
