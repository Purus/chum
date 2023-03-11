<?php

declare(strict_types=1);

use \Chum\Core\Controllers\TestController;
use \Chum\Core\Controllers\InstallController;

$app->get('/email', [TestController::class, 'testEmail'])->setName("email");
$app->map(['GET', 'POST'], '/install/welcome', [InstallController::class, 'index'])->setName("install");
$app->map(['GET', 'POST'], '/install/database', [InstallController::class, 'database'])->setName("install.db");
$app->map(['GET', 'POST'], '/install/finish', [InstallController::class, 'finish'])->setName("install.finish");
$app->any('/', [TestController::class, 'showBlank'])->setName("home");