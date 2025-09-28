<?php

namespace tebe\zack\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\read_file;

class GenericRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $path = $request->attributes->get('_path');
        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        $contentType = $request->attributes->get('_contentType');
        if ($contentType === null) {
            throw new \Exception('Attribute _contentType not found in request attributes');
        }

        $content = read_file($path);

        return new Response($content, 200, [
            'Content-Type' => $contentType,
        ]);
    }
}
