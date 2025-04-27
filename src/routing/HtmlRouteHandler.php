<?php

namespace tebe\zack\routing;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HtmlRouteHandler
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

        $html = file_get_contents($path);
        if ($html === false) {
            throw new Exception('Failed to read HTML file: ' . $path);
        }

        $content = $container->get('twig')->render('html-route-handler.html.twig', [
            'html' => $html,
        ]);

        return new Response($content);
    }
}
