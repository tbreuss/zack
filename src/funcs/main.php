<?php declare(strict_types=1);

namespace tebe\zack;

function file_read(string $path): string
{
    if (!file_exists($path)) {
        throw new \Exception('File not found: ' . $path);
    }

    $contents = file_get_contents($path);
    if ($contents === false) {
        throw new \Exception('Failed to read file: ' . $path);
    }

    return $contents;
}

function html_contains_full_html(string $html): bool
{
    return stripos($html, '<html') !== false || stripos($html, '<!doctype') !== false;
}

function html_extract_layout(string $html): string
{
    $status = preg_match('/<!--\s*layout:\s*(.+?)\s*-->/', $html, $matches);

    if ($status !== false && isset($matches[1])) {
        return trim($matches[1]);
    }

    return 'default.html.twig';
}

function html_extract_title(string $html, string $default): string
{
    if ($html === '') {
        return $default;
    }

    $d = new \DOMDocument();
    $d->loadHTML($html);

    foreach (['h1', 'h2', 'h3'] as $tagName) {
        foreach ($d->getElementsByTagName($tagName) as $item) {
            return trim($item->textContent);
        }
    }

    return $default;
}
