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

class AdminController extends BaseController
{
    public function dashboard(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Dashboard");

        return $this->render($request, $response, 'admin/admin.dashboard.twig', array());
    }
    public function general(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("General Settings");

        return $this->render($request, $response, 'admin/admin.general.twig', array());
    }
    public function mail(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Mail Settings");

        return $this->render($request, $response, 'admin/admin.mail.twig', array());
    }
    public function users(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Users");

        return $this->render($request, $response, 'admin/admin.users.twig', array());
    }

    public function themes(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Themes");

        return $this->render($request, $response, 'admin/admin.themes.twig', array());
    }
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

    public function menus(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Menu Configurations");

        return $this->render($request, $response, 'admin/admin.menu.twig', array());
    }
}