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

    public function render(string $template, array $context): Response
    {
            $content = $this->twig->render($template, $context);
            return (new Response())->setContent($content);
    }
}
