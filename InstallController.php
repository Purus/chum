<?php
namespace Chum;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Interfaces\ResponseInterface;

class InstallController extends BaseController
{
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (defined('CHUM_DB_INSTALLED') && CHUM_DB_INSTALLED) {
            return $this->redirectByName($request, $response, 'home');
        }

        return $this->render($request, $response, 'install.twig', array());
    }
}