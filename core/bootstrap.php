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

$plugins = PluginService::getInstance()->findActivePlugins();

foreach ($plugins as $plugin) {
    $pluginDir = CHUM_PLUGIN_ROOT . DS . $plugin->getKey() . DS;

    if (file_exists($pluginDir . 'init.php')) {
        include_once $pluginDir . 'init.php';
        $routes = require $pluginDir . 'routes.php';

        $t = $app->getContainer()->get(Symfony\Component\Translation\Translator::class);

        /** @var string $locale */
        $locale = $_SESSION['locale'] ?? "en";

        $t->addResource(
            'yml',
            $pluginDir . 'translations' . DS . $locale . '.yml',
            $locale
        );

        foreach ($routes as $route) {
            $app->{$route->getMethod()}($route->getRoutePath(), $route->getController() . ':' . $route->getAction())->setName($route->getRouteName());
        }
    }
}