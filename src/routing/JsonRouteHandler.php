<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JsonRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        if (!file_exists($path)) {
            throw new \Exception('JSON file not found: ' . $path);
        }

        $json = file_get_contents($path);
        if ($json === false) {
            throw new \Exception('Failed to read JSON file: ' . $path);
        }

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
