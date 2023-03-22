<?php
declare(strict_types=1);

namespace Chum\Core\Controllers;

use Chum\ChumDb;
use Chum\Core\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

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

    public function menus(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Menu Configurations");

        return $this->render($request, $response, 'admin/admin.menu.twig', array());
    }
}