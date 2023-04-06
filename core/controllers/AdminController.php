<?php
declare(strict_types=1);

namespace Chum\Core\Controllers;

use Chum\ChumDb;
use Chum\Core\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends BaseController
{
    public function dashboard(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Dashboard");

        return $this->render($request, $response, 'admin/admin.dashboard.twig', array());
    }
    public function general(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $db = new InstallDbConfig();
        $db->setDbHost('localhost');
        $db->setDbUser('root');
        $db->setDbName('chum');
        $db->setDbPassword('root');
        $db->setDbPrefix('chum_');

        $form = $this->createForm(InstallDbConfigType::class, $db);

        $req = Request::createFromGlobals();

        $form->handleRequest($req);

        //TODO Validate database configs before goign to next step using Symfony forms event handler
        if ($form->isSubmitted() && $form->isValid()) {
            $db = $form->getData();

            $this->session->set('dbName', $db->getDbName());
            $this->session->set('dbHost', $db->getDbHost());
            $this->session->set('dbUser', $db->getDbUser());
            $this->session->set('dbPassword', $db->getDbPassword());
            $this->session->set('dbPrefix', $db->getDbPrefix());

            return $this->redirectByName($response, "install.finish");
        }

        $this->setPageTitle("General Settings");

        return $this->render($request, $response, 'admin/admin.general.twig',  array('form' => $form->createView()));
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
        $this->setPageTitle("Menu Settings");

        return $this->render($request, $response, 'admin/admin.menus.twig', array());
    }
}