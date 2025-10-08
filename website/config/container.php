<?php declare(strict_types=1);

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Twig\Extra\Markdown\LeagueMarkdown;

$container = new ContainerBuilder();

$converter = new CommonMarkConverter([
    'heading_permalink' => [
        'min_heading_level' => 3,
        'id_prefix' => '',
        'fragment_prefix' => '',
        'apply_id_to_heading' => true,
    ],
    'table_of_contents' => [
        'position' => 'placeholder',
        'min_heading_level' => 3,
        'max_heading_level' => 6,
        'placeholder' => '[TOC]',
    ],
]);

$converter->getEnvironment()->addExtension(new TableExtension());
$converter->getEnvironment()->addExtension(new HeadingPermalinkExtension());
$converter->getEnvironment()->addExtension(new TableOfContentsExtension());

$markdown = new LeagueMarkdown($converter);

$container->set('markdown', $markdown);

return $container;
