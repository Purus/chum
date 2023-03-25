<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

require './vendor/autoload.php';

require './config/core.php';
require './config/db.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAttributes(true);

$dependencies = require CHUM_DIR_ROOT . DS . 'core'. DS . 'dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();
AppFactory::setContainer($container);

$app = AppFactory::create();

require CHUM_DIR_ROOT . DS . 'core' . DS . 'middleware.php';
require CHUM_DIR_ROOT . DS . 'core' . DS . 'routes.php';

// if (!defined('CHUM_DB_INSTALLED') || (defined('CHUM_DB_INSTALLED') && CHUM_DB_INSTALLED != '1')) {
//     $app->redirect("home",'install');
// }

require CHUM_DIR_ROOT . DS . 'core' . DS . 'bootstrap.php';

$app->run();

// if (!defined('CHUM_DB_INSTALLED') || (defined('CHUM_DB_INSTALLED') && CHUM_DB_INSTALLED != '1')) {
//     $app->redirect("/",'/install/welcome');
// }
