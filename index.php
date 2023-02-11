<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Monolog\Processor\IntrospectionProcessor;

require './vendor/autoload.php';
require './config/db.php';

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    Twig::class => function (ContainerInterface $c): Twig {
        $path = array(CHUM_TEMPLATE_PATH);

        return Twig::create($path, [
            'cache' => CHUM_CACHE_PATH,
            'autoescape' => false,
            'debug' => true
        ]);
    },
    LoggerInterface::class => function (ContainerInterface $c): Logger {
        $logger = new Logger('ChumChum');

        $logger->pushProcessor(new IntrospectionProcessor());

        $handler = new StreamHandler('logs' . DS . 'logs.log', Level::Debug);
        $logger->pushHandler($handler);

        return $logger;
    },
]);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->get('/', [\Chum\TestController::class, 'showBlank'])->setName("home");
$app->get('/install', [\Chum\InstallController::class, 'index'])->setName("install");

$app->run();