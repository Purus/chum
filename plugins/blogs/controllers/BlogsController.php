<?php

namespace Plugins\blogs\controllers;

use Chum\Core\BaseController;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Interfaces\ResponseInterface;

class BlogsController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->setPageTitle($this->translator->trans("blogs.home.page.title"));

        return $this->render($request, $response, 'blank.twig', array());
    }
}