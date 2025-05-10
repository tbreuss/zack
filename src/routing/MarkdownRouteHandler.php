<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Exception;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkdownRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new Exception('Attribute _path not found in request attributes');
        }

        if (!file_exists($path)) {
            throw new Exception('HTML file not found: ' . $path);
        }

        $markdown = file_get_contents($path);
        if ($markdown === false) {
            throw new Exception('Failed to read Markdown file: ' . $path);
        }

        $converter = new CommonMarkConverter();
        $html = (string) $converter->convert($markdown);

        $content = $container->get('twig')->render('route-handler.html.twig', [
            'title' => $this->extractTitle($html, $path),
            'html' => $html,
        ]);

        return new Response($content);
    }

    private function extractTitle(string $html, string $path): string
    {
        $d = new \DOMDocument();
        $d->loadHTML($html);

        foreach ($d->getElementsByTagName('h1') as $item) {
            return trim($item->textContent);
        }

        foreach ($d->getElementsByTagName('h2') as $item) {
            return trim($item->textContent);
        }

        return basename($path);
    }
}
