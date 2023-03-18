<?php

declare(strict_types=1);

use Chum\Core\PluginService;
use Chum\Events\NewMainMenuEvent;
use Chum\ChumEvent;
use Chum\Events\MainMenuEventSubscriber;

$dispacter = ChumEvent::getInstance();

$dispacter->addSubscriber(new MainMenuEventSubscriber());

/* $dispacter->addListener(NewMainMenuEvent::NAME, function (NewMainMenuEvent $event) {
// dump($event);
echo $event->getMenu();
}); */

$lang = $app->getContainer()->get(Symfony\Component\Translation\Translator::class);
$twig = $app->getContainer()->get(Slim\Views\Twig::class);

$plugins = PluginService::getInstance()->findActivePlugins();

foreach ($plugins as $plugin) {
    $pluginDir = CHUM_PLUGIN_ROOT . DS . $plugin->getKey() . DS;

    if (file_exists($pluginDir . 'init.php')) {
        include_once $pluginDir . 'init.php';
    }

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
        $twig->getLoader()->addPath($pluginDir . 'template', $plugin->getKey());
    }
}

$themesDir = CHUM_DIR_ROOT . DS . 'themes' . DS . 'chum-chum' . DS;

if (is_dir($themesDir)) {
    $twig->getLoader()->addPath($themesDir);
}