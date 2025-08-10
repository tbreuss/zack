<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\file_read;
use function tebe\zack\html_extract_layout;
use function tebe\zack\html_extract_title;

class HtmlRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        $html = file_read($path);
        $layout = html_extract_layout($html);
        $title = html_extract_title($html, basename($path));

        $content = $container->get('twig')->render($layout, [
            'title' => $title,
            'html' => $html,
        ]);

        return new Response($content, 200, [
            'Content-Type' => 'text/html',
        ]);
    }
}
