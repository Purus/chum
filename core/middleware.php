<?php

declare(strict_types=1);

$routeParser = $app->getRouteCollector()->getRouteParser();

$container->set(Slim\Interfaces\RouteParserInterface::class, $routeParser);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(Chum\Middlewares\SessionMiddleware::class);
$app->add(Slim\Views\TwigMiddleware::createFromContainer($app, Slim\Views\Twig::class));

if (defined('CHUM_DEV_MODE') && CHUM_DEV_MODE) {
    $app->add(new Chum\Middlewares\WhoopsMiddleware());
} else {
    $app->add(Slim\Middleware\ErrorMiddleware::class);
}

// always last one
$errorMiddleware = $app->addErrorMiddleware(
    true,
    true,
    true
);
