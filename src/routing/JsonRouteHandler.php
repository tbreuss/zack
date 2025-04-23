<?php

namespace tebe\zack\routing;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRouteHandler
{
    public function __invoke(Request $request, \Twig\Environment $twig)
    {
        $path = $request->attributes->get('_path');

        if (!file_exists($path)) {
            throw new Exception('JSON file not found: ' . $path);    
        }

        $json = file_get_contents($path);
        if ($json === false) {
            throw new Exception('Failed to read JSON file: ' . $path);
        }

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
