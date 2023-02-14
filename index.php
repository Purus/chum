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
use Zeuxisoo\Whoops\Slim\WhoopsMiddleware;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\MailerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\Translation\Loader\FileLoader;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Slim\Views\TwigMiddleware;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

require './vendor/autoload.php';
require './config/db.php';

$containerBuilder = new ContainerBuilder();
// $containerBuilder->useAttributes(true);

$containerBuilder->addDefinitions([
    Twig::class => function (ContainerInterface $c): Twig {
        $path = array(CHUM_TEMPLATE_PATH);

        $translator = $c->get(Translator::class);

        $twig = Twig::create($path, [
            'cache' => CHUM_CACHE_PATH,
            'autoescape' => false,
            'debug' => true
        ]);
        $twig->getEnvironment()->addGlobal('locale', $translator->getLocale());
        $twig->addExtension(new TranslationExtension($translator));
        return $twig;

    },
    LoggerInterface::class => function (ContainerInterface $c): Logger {
        $logger = new Logger('ChumChum');

        $logger->pushProcessor(new IntrospectionProcessor());

        $handler = new StreamHandler('logs' . DS . 'logs.log', Level::Error);
        $logger->pushHandler($handler);

        return $logger;
    },
    Connection::class => function (ContainerInterface $container): Connection {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = [
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'chum',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'driverOptions' => [
                // Turn on persistent connections
                PDO::ATTR_PERSISTENT => true,
                // Enable exceptions
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                // Emulate prepared statements
                PDO::ATTR_EMULATE_PREPARES => true,
                // Set default fetch mode to array
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Set character set
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
            ],
        ];

        return DriverManager::getConnection($connectionParams, $config);
    },
    PDO::class => function (ContainerInterface $container) {
        return $container->get(Connection::class)->getWrappedConnection();
    },
    MailerInterface::class => function (ContainerInterface $container) {
        $dsn = sprintf(
            '%s://%s:%s@%s:%s',
            "smtp",
            "9ff0b802d1ed1a",
            "59fb1c9e8dab52",
            "sandbox.smtp.mailtrap.io",
            "587"
        );

        return new Mailer(Transport::fromDsn($dsn));
    },
    Filesystem::class => function (ContainerInterface $container) {
        $adapter = new LocalFilesystemAdapter(
            'cache' . DS
        );

        return new Filesystem($adapter);
    },
    Translator::class => function (ContainerInterface $c): Translator {
        $translator = new Translator("en");

        /** @var string $locale */
        $locale = $_SESSION['locale'] ?? "en";

        $translator->addLoader("yml", new YamlFileLoader());
        $translator->addResource(
            'yml',
            __DIR__ . DS . 'translations' . DS . $locale . '.yml',
            $locale
        );
        return $translator;
    },
]);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

if (defined('CHUM_DEV_MODE') && CHUM_DEV_MODE) {
    $app->add(new WhoopsMiddleware(['enable' => true]));
} else {
    $errorMiddleware = $app->addErrorMiddleware(false, true, true);
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('text/html', HtmlErrorRenderer::class);
}

$app->get('/', [\Chum\TestController::class, 'showBlank'])->setName("home");
$app->get('/email', [\Chum\TestController::class, 'testEmail'])->setName("email");
$app->get('/install', [\Chum\InstallController::class, 'index'])->setName("install");

$app->run();