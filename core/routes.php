<?php

declare(strict_types=1);

use \Chum\Core\Controllers\TestController;
use \Chum\Core\Controllers\AdminController;
use \Chum\Core\Controllers\InstallController;
use \Chum\Core\Controllers\PluginController;
use \Chum\Core\Controllers\ThemesController;

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
$app->get('/admin/plugins', [PluginController::class, 'plugins'])->setName("admin.plugins");
$app->get('/admin/plugins/install/{key}', [PluginController::class, 'install'])->setName("admin.plugin.install");
$app->get('/admin/plugins/activate/{key}', [PluginController::class, 'activate'])->setName("admin.plugin.activate");
$app->get('/admin/plugins/deactivate/{key}', [PluginController::class, 'deactivate'])->setName("admin.plugin.deactivate");
$app->get('/admin/plugins/uninstall/{key}', [PluginController::class, 'uninstall'])->setName("admin.plugin.uninstall");
$app->get('/admin/themes', [ThemesController::class, 'themes'])->setName("admin.themes");
$app->get('/admin/themes/activate/{key}', [ThemesController::class, 'activate'])->setName("admin.themes.activate");
