<?php

namespace Chum;

use League\Flysystem\Filesystem;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Translation\Translator;

abstract class BaseController
{
    protected UserRepository $userRepo;
    protected Twig $twig;
    protected LoggerInterface $logger;
    protected MailerInterface $mailer;
    protected Filesystem $filesystem;
    protected Translator $translator;

    public function __construct(Twig $twig, Translator $translator, UserRepository $userRepo, LoggerInterface $logger, MailerInterface $mailer, Filesystem $filesystem)
    {
        $this->twig = $twig;
        $this->logger = $logger;
        $this->userRepo = $userRepo;
        $this->mailer = $mailer;
        $this->filesystem = $filesystem;
        $this->translator = $translator;
    }

    protected function redirectByName(ServerRequestInterface $request, $response, $route)
    {
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor($route);
        return $response->withHeader('Location', $url)->withStatus(302);
    }

    protected function render(ServerRequestInterface $request, $response, $route, $data): ResponseInterface
    {
        $response = $response->withoutHeader('Server');
        $response = $response->withAddedHeader('X-Powered-By', 'ChumChum');

        if (defined('CHUM_SECURE_HEADERS') && CHUM_SECURE_HEADERS) {
            $response = $response->withAddedHeader('X-Content-Type-Options', 'nosniff');
            $response = $response->withAddedHeader('X-Frame-Options', 'SAMEORIGIN');
            $response = $response->withAddedHeader('X-XSS-Protection', '1;mode=block');
            $response = $response->withAddedHeader('Referrer-Policy', 'no-referrer-when-downgrade');

            if ($request->getUri()->getScheme() == 'https') {
                $response = $response->withAddedHeader('Strict-Transport-Security', 'max-age=63072000');
            }
        }

        return $this->twig->render($response, $route, $data);
    }

    public function sendEmail(Email $email): void
    {
        $this->mailer->send($email);
    }

    public function getFileSystem(): Filesystem
    {
        return $this->filesystem;
    }
}