<?php
declare(strict_types=1);

namespace Chum;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class TestController extends BaseController
{
    public function showBlank(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->logger->info("Hello");

        if (!defined('CHUM_DB_INSTALLED') || (defined('CHUM_DB_INSTALLED') && !CHUM_DB_INSTALLED)) {
            return $this->redirectByName($request, $response, 'install');

        }

        return $this->render($request, $response, 'blank.twig', array());
    }
}