<?php

use SebastianBergmann\CodeCoverage\Filter;
use SebastianBergmann\CodeCoverage\Driver\Selector;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\PHP as PhpReport;

$dir = __DIR__ . '/.coverage/files/';

$files = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/src', RecursiveDirectoryIterator::SKIP_DOTS)) as $file ) {
    if ($file->getExtension() !== 'php') {
        continue;
    }
    $files[] = $file->getPathname();
}

$filter = new Filter();
$filter->includeFiles($files);

$coverage = new CodeCoverage(
    (new Selector())->forLineCoverage($filter),
    $filter
);

$coverage->start($_SERVER['REQUEST_URI']);

register_shutdown_function(function () use ($coverage, $dir) {
    $coverage->stop();
    $basename = $dir . bin2hex(random_bytes(16));
    (new PhpReport)->process($coverage, $basename . '.cov');
});
