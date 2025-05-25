<?php declare(strict_types=1);

namespace tebe\zack\routing;

use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\file_read;
use function tebe\zack\html_extract_title;

class MarkdownRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        $markdown = file_read($path);

        $converter = new CommonMarkConverter();
        $html = (string) $converter->convert($markdown);

        $title = html_extract_title($html, basename($path));

        $content = $container->get('twig')->render('route-handler.html.twig', [
            'title' => $title,
            'html' => $html,
        ]);

        return new Response($content);
    }
}
