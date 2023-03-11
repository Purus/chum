<?php 

declare(strict_types=1);

use Monolog\Level;
use Monolog\Logger;
use Slim\Views\Twig;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use League\Flysystem\Filesystem;
use Symfony\Component\Form\Forms;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\Mailer\Mailer;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\Transport;
use Monolog\Handler\RotatingFileHandler;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Validator\Validation;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Monolog\Processor\IntrospectionProcessor;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormFactoryInterface;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;

return function (ContainerBuilder $containerBuilder) {

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
            $adapter = new LocalFilesystemAdapter(CHUM_DIR_ROOT);
    
            return new Filesystem($adapter);
        },
        Translator::class => function (ContainerInterface $c): Translator {
            $translator = new Translator("en");
    
            /** @var string $locale */
            $locale = $_SESSION['locale'] ?? "en";
    
            $translator->addLoader("yml", new YamlFileLoader());
            $translator->addResource(
                'yml',
               CHUM_DIR_ROOT  . DS . 'translations' . DS . $locale . '.yml',
                $locale
            );
            return $translator;
        },
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
};
