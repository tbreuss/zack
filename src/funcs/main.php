<?php declare(strict_types=1);

namespace tebe\zack;

/**
 * Fixes PHP 8.2 mb_convert_encoding(): Handling HTML entities via mbstring is deprecated
 * 
 * @see https://github.com/php/php-src/pull/7177
 * @see https://github.com/symfony/symfony/issues/44281
 */
function convert_encoding(string $string): string
{
    return mb_encode_numericentity(
        $string, 
        [0x80, 0x10fffff, 0, 0x1fffff], // [0x80, 0x10FFFF, 0, ~0],
        mb_internal_encoding()
    );
}

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

    $document = load_html($html);

    foreach (['h1', 'h2', 'h3'] as $tagName) {
        foreach ($document->getElementsByTagName($tagName) as $item) {
            return mb_trim($item->textContent, mb_internal_encoding());
        }
    }

    return $default;
}

function load_html(string $html): \DOMDocument
{
    libxml_use_internal_errors(true);
    libxml_clear_errors();

    $d = new \DOMDocument();
    $d->loadHTML(convert_encoding($html));
    
    foreach (libxml_get_errors() as $error) {
        error_log('libxml internal error: ' . json_encode($error));
    }

    libxml_clear_errors();

    return $d;
}
