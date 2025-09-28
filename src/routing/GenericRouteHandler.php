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

        $extensionContentTypeMapping = $request->attributes->get('_contentTypes');
        if ($extensionContentTypeMapping === null) {
            throw new \Exception('Attribute _contentTypes not found in request attributes');
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $contentType = $extensionContentTypeMapping[$extension] ?? throw new \Exception('Unsupported file type: ' . $extension);

        $content = read_file($path);

        return new Response($content, 200, [
            'Content-Type' => $contentType,
        ]);
    }
}
