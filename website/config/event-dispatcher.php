<?php declare(strict_types=1);

use Symfony\Component\EventDispatcher\EventDispatcher;

$dispatcher = new EventDispatcher();

$dispatcher->addListener('zack.container', function (): void {
    error_log('Event zack.container received');
});

$dispatcher->addListener('zack.controller', function ($event): void {
    if ($event->getExtension() === 'md') {
        $event->setResolvedController(tebe\zack\routing\HtmlRouteHandler::class);
    }
    error_log('Event zack.controller received');
});

$dispatcher->addListener('zack.routes', function (): void {
    error_log('Event zack.routes received');
});

$dispatcher->addListener('kernel.request', function (): void {
    error_log('Event kernel.request received');
});

$dispatcher->addListener('kernel.controller', function (): void {
    error_log('Event kernel.controller received');
});

$dispatcher->addListener('kernel.controller_arguments', function (): void {
    error_log('Event kernel.controller_arguments received');
});

$dispatcher->addListener('kernel.view', function (): void {
    error_log('Event kernel.view received');
});

$dispatcher->addListener('kernel.response', function (): void {
    error_log('Event kernel.response received');
});

$dispatcher->addListener('kernel.finish_request', function (): void {
    error_log('Event kernel.finish_request received');
});

$dispatcher->addListener('kernel.terminate', function (): void {
    error_log('Event kernel.terminate received');
});

$dispatcher->addListener('kernel.exception', function (): void {
    error_log('Event kernel.exception received');
});

return $dispatcher;
