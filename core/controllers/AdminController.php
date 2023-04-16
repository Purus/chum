<?php
declare(strict_types=1);

namespace Chum\Core\Controllers;

use Chum\ChumDb;
use Chum\Core\BaseController;
use Chum\Core\ConfigService;
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
        $siteTagline = ConfigService::getInstance()->findCoreByKey("site_tagline");
        $siteEmail = ConfigService::getInstance()->findCoreByKey("site_email");
        $siteUrl = ConfigService::getInstance()->findCoreByKey("site_url");


        $config = new AdminSettingsGeneralConfig();
        $config->setSiteEmail($siteEmail);
        $config->setSiteTagline($siteTagline);
        $config->setSiteUrl($siteUrl);

        $form = $this->createForm(AdminSettingsGeneralConfigType::class, $config);

        $req = Request::createFromGlobals();

        $form->handleRequest($req);

        //TODO Validate database configs before goign to next step using Symfony forms event handler
        if ($form->isSubmitted() && $form->isValid()) {
            $config = $form->getData();

            $config->getSiteEmail();
            $config->getSiteTagline();
            $config->getSiteUrl();

            return $this->redirectByName($response, "admin.basic");
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

    public function menus(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle("Menu Settings");

        return $this->render($request, $response, 'admin/admin.menus.twig', array());
    }
}