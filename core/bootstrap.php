<?php

declare(strict_types=1);

use Chum\Core\PluginService;
use Chum\Events\NewMainMenuEvent;
use Chum\ChumEvent;
use Chum\Events\MainMenuEventSubscriber;

$manager = new Illuminate\Database\Capsule\Manager();

$dbSettings = [
    'driver' => 'mysql',
    'host' => CHUM_DB_HOST,
    'database' => CHUM_DB_NAME,
    'username' => CHUM_DB_USER,
    'password' => CHUM_DB_PASSWORD,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => CHUM_DB_PREFIX
];

$manager->addConnection($dbSettings);

$manager->getConnection()->enableQueryLog();

$manager->setAsGlobal();
$manager->bootEloquent();

$dispacter = ChumEvent::getInstance();

$dispacter->addSubscriber(new MainMenuEventSubscriber());

/* $dispacter->addListener(NewMainMenuEvent::NAME, function (NewMainMenuEvent $event) {
// dump($event);
echo $event->getMenu();
}); */

$lang = $app->getContainer()->get(Symfony\Component\Translation\Translator::class);
$twig = $app->getContainer()->get(Slim\Views\Twig::class);

if (!defined('CHUM_DB_INSTALLED') || (defined('CHUM_DB_INSTALLED') && CHUM_DB_INSTALLED != '1')) {
    $app->redirect("/",'/install');
}

$plugins = PluginService::getInstance()->findActivePlugins();

foreach ($plugins as $plugin) {
    $pluginDir = CHUM_PLUGIN_ROOT . DS. strtolower($plugin['key']) . DS;
    PluginService::getInstance()->includeScript($pluginDir . PluginService::SCRIPT_INIT);

    if (file_exists($pluginDir . 'routes.php')) {
        $routes = require $pluginDir . 'routes.php';

        foreach ($routes as $route) {
            $app->{$route->getMethod()}($route->getRoutePath(), $route->getController() . ':' . $route->getAction())->setName($route->getRouteName());
        }
    }

    /** @var string $locale */
    $locale = $_SESSION['locale'] ?? "en";

    if (file_exists($pluginDir . 'translations' . DS . $locale . '.yml')) {
        $lang->addResource(
            'yml',
            $pluginDir . 'translations' . DS . $locale . '.yml',
            $locale
        );
    }

    if (is_dir($pluginDir . 'template')) {
        $twig->getLoader()->addPath($pluginDir . 'template', strtolower($plugin['key']));
    }
}

$themesDir = CHUM_DIR_ROOT . DS . 'themes' . DS . 'chum-chum' . DS;

if (is_dir($themesDir)) {
    $twig->getLoader()->addPath($themesDir);
}