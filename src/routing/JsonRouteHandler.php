<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\file_read;

class JsonRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        $json = file_read($path);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
