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

function html_extract_title(string $html, string $default): string
{
    if ($html === '') {
        return $default;
    }

    $d = new \DOMDocument();
    $d->loadHTML($html);

    foreach ($d->getElementsByTagName('h1') as $item) {
        return trim($item->textContent);
    }

    foreach ($d->getElementsByTagName('h2') as $item) {
        return trim($item->textContent);
    }

    return $default;
}
