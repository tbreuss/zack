<?php declare(strict_types=1);

namespace tebe\zack\routing;

use League\CommonMark\CommonMarkConverter;
use Michelf\MarkdownExtra;
use Parsedown;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Extra\Markdown\LeagueMarkdown;
use Twig\Extra\Markdown\MichelfMarkdown;
use Twig\Extra\Markdown\ErusevMarkdown;

use function tebe\zack\file_read;
use function tebe\zack\html_extract_title;

class MarkdownRouteHandler
{
    public function __invoke(Request $request): Response
    {
        $container = $request->attributes->get('_container');
        $path = $request->attributes->get('_path');

        $markdown = file_read($path);

        if (class_exists(CommonMarkConverter::class)) {
            $converter = new LeagueMarkdown();
        } elseif (class_exists(MarkdownExtra::class)) {
            $converter = new MichelfMarkdown();
        } elseif (class_exists(Parsedown::class)) {
            $converter = new ErusevMarkdown();
        } else {
            throw new \LogicException('No Markdown library is available; try running "composer require league/commonmark".');
        }

        $html = (string) $converter->convert($markdown);

        $title = html_extract_title($html, basename($path));

        $content = $container->get('twig')->render('route-handler.html.twig', [
            'title' => $title,
            'html' => $html,
        ]);

        return new Response($content);
    }
}
