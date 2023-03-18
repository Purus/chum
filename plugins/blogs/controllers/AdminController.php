<?php

namespace Plugins\blogs\controllers;

use Chum\Core\BaseController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Interfaces\ResponseInterface;

class AdminController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle($this->translator->trans("blogs.admin.settings.page.title"));

        
        return $this->render($request, $response, '@blogs/blogs.admin.twig', array());
    }
}