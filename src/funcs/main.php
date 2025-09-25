<?php declare(strict_types=1);

namespace tebe\zack;

function contains_full_html(string $html): bool
{
    return stripos($html, '<html') !== false || stripos($html, '<!doctype') !== false;
}

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
        mb_internal_encoding(),
    );
}

function create_dom_document(string $html): \DOMDocument
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

function extract_layout_from_html(string $html): string
{
    $status = preg_match('/<!--\s*layout:\s*(.+?)\s*-->/', $html, $matches);

    if ($status !== false && isset($matches[1])) {
        return trim($matches[1]);
    }

    return 'default.html.twig';
}

function extract_title_from_html(string $html, string $default): string
{
    if ($html === '') {
        return $default;
    }

    $document = create_dom_document($html);

    foreach (['h1', 'h2', 'h3'] as $tagName) {
        foreach ($document->getElementsByTagName($tagName) as $item) {
            return mb_trim($item->textContent, mb_internal_encoding());
        }
    }

    return $default;
}

function read_file(string $path): string
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
