<?php declare(strict_types=1);

namespace tebe\zack\routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function tebe\zack\read_file;

class TextRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $path = $request->attributes->get('_path');

        if ($path === null) {
            throw new \Exception('Attribute _path not found in request attributes');
        }

        $text = read_file($path);

        return new Response($text, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
