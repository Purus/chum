<?php
declare(strict_types=1);

namespace Chum\Core\Controllers;

use Chum\ChumDb;
use Chum\Core\BaseController;
use Chum\Core\NewMainMenuEvent;
use Chum\Core\PluginService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\EventDispatcher\Event;

class PluginController extends BaseController
{

    public function plugins(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $activePlugins = PluginService::getInstance()->findActivePlugins();
        $inactivePlugins = PluginService::getInstance()->findInactivePlugins();
        $availablePlugins = PluginService::getInstance()->findAvailablePlugins();

        $this->setPageTitle("Plugins");

        return $this->render(
            $request,
            $response,
            'admin/admin.plugins.twig',
            array('activePlugins' => $activePlugins, 'inactivePlugins' => $inactivePlugins, 'availablePlugins' => $availablePlugins)
        );
    }

    public function install(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pluginKey = $args['key'];

        PluginService::getInstance()->install($pluginKey);

        return $this->redirectByName($response, "admin.plugins");
    }

    public function uninstall(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pluginKey = $args['key'];

        PluginService::getInstance()->uninstall($pluginKey);

        return $this->redirectByName($response, "admin.plugins");
    }   
    
    public function activate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pluginKey = $args['key'];

        PluginService::getInstance()->activate($pluginKey);

        return $this->redirectByName($response, "admin.plugins");
    }    
    
    public function deactivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $pluginKey = $args['key'];

        PluginService::getInstance()->deactivate($pluginKey);

        return $this->redirectByName($response, "admin.plugins");
    }
}