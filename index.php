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
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Slim\Views\TwigMiddleware;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use \Chum\Core\Controllers\TestController;
use \Chum\Core\Controllers\InstallController;
use Odan\Session\PhpSession;
use Odan\Session\SessionInterface;
use Odan\Session\SessionManagerInterface;
use Odan\Session\Middleware\SessionStartMiddleware;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\FormRenderer;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Monolog\Handler\RotatingFileHandler;
use Slim\Error\Renderers\HtmlErrorRenderer;
use Slim\Middleware\ErrorMiddleware;
use Psr\Http\Server\MiddlewareInterface;

require './vendor/autoload.php';
require './config/core.php';

require './config/db.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAttributes(true);

$containerBuilder->addDefinitions([
    Twig::class => function (ContainerInterface $c): Twig {
        $defaultFormTheme = 'tailwind_2_layout.html.twig';

        $appVariableReflection = new \ReflectionClass('\Symfony\Bridge\Twig\AppVariable');
        $vendorTwigBridgeDirectory = dirname($appVariableReflection->getFileName());

        $path = array(
            CHUM_TEMPLATE_PATH,
            $vendorTwigBridgeDirectory . DS . 'Resources' . DS . 'views' . DS . 'Form',
        );

        $translator = $c->get(Translator::class);

        $twig = Twig::create($path, [
            'cache' => CHUM_CACHE_PATH,
            'autoescape' => false,
            'debug' => true,
            'form_themes' => 'form_div_layout.html.twig'
        ]);

        $twig->getEnvironment()->addGlobal('locale', $translator->getLocale());
        $twig->addExtension(new TranslationExtension($translator));

        $formEngine = new TwigRendererEngine([$defaultFormTheme], $twig->getEnvironment());

        $csrfGenerator = new UriSafeTokenGenerator();
        $csrfManager = new CsrfTokenManager($csrfGenerator);

        $twig->getEnvironment()->addRuntimeLoader(new FactoryRuntimeLoader([
                FormRenderer::class => function () use ($formEngine, $csrfManager) {
                        return new FormRenderer($formEngine, $csrfManager);
                    }
        ]));

        $twig->addExtension(new FormExtension());

        // $flash = $c->get(SessionInterface::class)->getFlash();
        
        // $twig->getEnvironment()->addGlobal('flash', $flash);

        return $twig;

    },
    LoggerInterface::class => function (ContainerInterface $c): Logger {
        $logger = new Logger('ChumChum');

        $logger->pushProcessor(new IntrospectionProcessor());

        $handler = new RotatingFileHandler('logs' . DS . 'logs.log', 5, Level::Error);
        $logger->pushHandler($handler);

        return $logger;
    },
    Connection::class => function (ContainerInterface $container): Connection {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'driver' => 'pdo_mysql',
            'host' => 'localhost',
            'dbname' => 'chum',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'driverOptions' => array(
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
            )
        );

        return DriverManager::getConnection($connectionParams, $config);
    },
    PDO::class => function (ContainerInterface $container): PDO {
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
/*     SessionManagerInterface::class => function (ContainerInterface $c): SessionInterface {
        return $c->get(SessionInterface::class);
    },
    SessionInterface::class => function (ContainerInterface $c) {
        $options = [
            'name' => 'app',
            'lifetime' => 7200,
            'path' => null,
            'domain' => null,
            'secure' => false,
            'httponly' => true,
            'cache_limiter' => 'nocache',
        ];

        return new PhpSession($options);
    }, */
    FormFactoryInterface::class => function (ContainerInterface $c): FormFactoryInterface {

        $csrfGenerator = new UriSafeTokenGenerator();
        $csrfManager = new CsrfTokenManager($csrfGenerator);
        $validator = Validation::createValidator();

        return Forms::createFormFactoryBuilder()
            ->addExtension(new CsrfExtension($csrfManager))
            ->addExtension(new HttpFoundationExtension())
            ->addExtension(new ValidatorExtension($validator))
            ->getFormFactory();

    },
        // Psr\Http\Message\ResponseFactoryInterface::class => function (ContainerInterface $container): Psr\Http\Message\ResponseFactoryInterface {
        //     return $container->get(Slim\App::class)->getResponseFactory();
        // },
    Psr\Http\Message\ResponseFactoryInterface::class => function (ContainerInterface $container): Psr\Http\Message\ResponseFactoryInterface {
        return $container->get(Slim\App::class)->getResponseFactory();
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(Slim\App::class);

        $errorMiddleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            true,
            true,
            true
        );

        $errorMiddleware->setDefaultErrorHandler(Chum\Core\ChumErrorRenderer::class);

        return $errorMiddleware;
    },

]);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

$routeParser = $app->getRouteCollector()->getRouteParser();

$container->set(Slim\Interfaces\RouteParserInterface::class, $routeParser);

$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add(\Chum\Core\SessionMiddleware::class);
$app->add(TwigMiddleware::createFromContainer($app, Twig::class));

// $app->add(Chum\Core\HttpExceptionMiddleware::class);
// $app->add(ErrorMiddleware::class);

/* if (defined('CHUM_DEV_MODE') && CHUM_DEV_MODE) {
$app->add(new WhoopsMiddleware([
'enable' => true,
'title' => 'Chum Error',
]));
} else {
$errorMiddleware = $app->addErrorMiddleware(false, true, true);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->registerErrorRenderer('text/html', Chum\Core\ChumErrorRenderer::class);
}
$app->addErrorMiddleware(true, true, true); */

$app->get('/', [TestController::class, 'showBlank'])->setName("home");
$app->get('/email', [TestController::class, 'testEmail'])->setName("email");
$app->map(['GET', 'POST'], '/install/welcome', [InstallController::class, 'index'])->setName("install");
$app->map(['GET', 'POST'], '/install/database', [InstallController::class, 'database'])->setName("install.db");
$app->map(['GET', 'POST'], '/install/finish', [InstallController::class, 'finish'])->setName("install.finish");

$app->run();