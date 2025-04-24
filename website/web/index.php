<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/../vendor/autoload.php';

use tebe\zack\Config;
use tebe\zack\Zack;
use tebe\zack\event\ContainerEvent;
use tebe\zack\event\ResponseEvent;
use tebe\zack\event\RoutesEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;

$config = new Config([
    'rootPath' => dirname(__DIR__),
    'twigCachePath' => false,
]);

$dispatcher = new EventDispatcher();

$dispatcher->addListener('container', function (ContainerEvent $event): void {
    echo "<!-- Container -->";
});

$dispatcher->addListener('response', function (ResponseEvent $event): void {
    $response = $event->getResponse();
    if ($response->isRedirection()
        || ($response->headers->has('Content-Type') && false === strpos($response->headers->get('Content-Type'), 'html'))
        || 'html' !== $event->getRequest()->getRequestFormat()
    ) {
        return;
    }    
    $response->setContent($response->getContent().'<!-- created by Zack! -->');
});

$dispatcher->addListener('routes', function (RoutesEvent $event): void {
    echo "<!-- Routes -->";
});

(new Zack($config, $dispatcher))->run();

function isHtmlResponse($response): bool
{

    return true;
}
