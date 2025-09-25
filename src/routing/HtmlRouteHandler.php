<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\read_file;
use function tebe\zack\extract_layout_from_html;
use function tebe\zack\extract_title_from_html;

class HtmlRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        $html = read_file($path);
        $layout = extract_layout_from_html($html);
        $title = extract_title_from_html($html, basename($path));

        $content = $container->get('twig')->render($layout, [
            'title' => $title,
            'html' => $html,
        ]);

        return new Response($content, 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}
