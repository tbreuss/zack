<?php declare(strict_types=1);

use Symfony\Component\EventDispatcher\EventDispatcher;
use tebe\zack\event\ContainerEvent;
use tebe\zack\event\ResponseEvent;
use tebe\zack\event\RoutesEvent;

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

return $dispatcher;
