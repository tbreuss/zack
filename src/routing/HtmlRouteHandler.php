<?php

namespace tebe\zack\routing;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HtmlRouteHandler
{
    public function __invoke(Request $request)
    {
        $path = $request->attributes->get('_path');

        if (!file_exists($path)) {
            throw new Exception('HTML file not found: ' . $path);    
        }

        $content = file_get_contents($path);
        if ($content === false) {
            throw new Exception('Failed to read HTML file: ' . $path);
        }

        return new Response($content);
    }
}
