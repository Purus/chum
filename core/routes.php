<?php

declare(strict_types=1);

use \Chum\Core\Controllers\TestController;
use \Chum\Core\Controllers\AdminController;
use \Chum\Core\Controllers\InstallController;

$app->get('/email', [TestController::class, 'testEmail'])->setName("email");
$app->map(['GET', 'POST'], '/install/welcome', [InstallController::class, 'index'])->setName("install");
$app->map(['GET', 'POST'], '/install/database', [InstallController::class, 'database'])->setName("install.db");
$app->map(['GET', 'POST'], '/install/finish', [InstallController::class, 'finish'])->setName("install.finish");
$app->any('/', [TestController::class, 'showBlank'])->setName("home");

$app->get('/admin/settings/dashboard', [AdminController::class, 'dashboard'])->setName("admin.home");
$app->get('/admin/settings/general', [AdminController::class, 'general'])->setName("admin.basic");
$app->get('/admin/settings/menus', [AdminController::class, 'menus'])->setName("admin.menus");
$app->get('/admin/settings/mail', [AdminController::class, 'mail'])->setName("admin.mail");
$app->get('/admin/users', [AdminController::class, 'users'])->setName("admin.users");
$app->get('/admin/plugins', [AdminController::class, 'plugins'])->setName("admin.plugins");
$app->get('/admin/themes', [AdminController::class, 'themes'])->setName("admin.themes");