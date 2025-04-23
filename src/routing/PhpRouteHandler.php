<?php

namespace tebe\zack\routing;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhpRouteHandler
{
    private ?\Twig\Environment $twig;

    public function __invoke(Request $request, \Twig\Environment $twig): Response
    {
        $path = $request->attributes->get('_path');

        if (!file_exists($path)) {
            throw new Exception('PHP file not found: ' . $path);    
        }

        $this->twig = $twig;
    
        $response = require $path;

        if (!$response instanceof Response) {
            throw new Exception('PHP file must return a Response object: ' . $path);
        }

        return $response;
    }

    public function html(string $template, array $context): Response
    {
        $html = $this->twig->render($template, $context);
        return new Response($html, 200);
    }

    public function json(array $context): Response
    {
        $json = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);        
    }
}
