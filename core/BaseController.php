<?php

namespace Chum\Core;

use League\Flysystem\Filesystem;
use Odan\Session\SessionInterface;
use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Interfaces\RouteParserInterface;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Form\Extension\Core\Type\FormType;

//TODO refer more methods from https://symfony.com/doc/current/controller.html#the-base-controller-class-services
abstract class BaseController
{
    protected Twig $twig;
    protected LoggerInterface $logger;
    protected MailerInterface $mailer;
    protected Filesystem $filesystem;
    protected Translator $translator;
    // protected SessionInterface $session;
    protected Session $session;
    protected FormFactoryInterface $form;
    protected RouteParserInterface $routeParser;
    protected EventDispatcher $events;

    protected string $title = "";

    public function __construct(
        Twig $twig, EventDispatcher $events, RouteParserInterface $routeParser, FormFactoryInterface $form, Translator $translator, LoggerInterface $logger, MailerInterface $mailer,
        // SessionInterface $session, 
        Session $session,
        Filesystem $filesystem
    )
    {
        $this->events = $events;
        $this->twig = $twig;
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->filesystem = $filesystem;
        $this->translator = $translator;
        $this->session = $session;
        $this->form = $form;
        $this->routeParser = $routeParser;
    }

    protected function redirectByName(ResponseInterface $response, string $routeName)
    {
        $url = $this->routeParser->urlFor($routeName);
        //TODO look to avoid getting response as paramter
        return $response->withHeader('Location', $url)->withStatus(302);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     */
    protected function createForm(string $type, mixed $data = null, array $options = []): FormInterface
    {
        return $this->form->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     */
    protected function createFormBuilder(mixed $data = null, array $options = []): FormBuilderInterface
    {
        return $this->form->createBuilder(FormType::class, $data, $options);
    }

    /*     protected function getUrlByRoute(ServerRequestInterface $request, $route)
    {
    $routeParser = RouteContext::fromRequest($request)->getRouteParser();
    return $routeParser->urlFor($route);
    } */

    /*     protected function redirect(string $url, int $status = 302): RedirectResponse
    {
    return new RedirectResponse($url, $status);
    } */

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param int $status The HTTP status code (302 "Found" by default)
     */
    /*     protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
    return $this->redirect($this->generateUrl($route, $parameters), $status);
    }
    */
    protected function generateUrl(string $route, array $parameters = []): string
    {
        return $this->routeParser->urlFor($route, $parameters);
    }

    protected function setPageTitle(string $title)
    {
        $this->title = $title;
    }

    protected function render(ServerRequestInterface $request, ResponseInterface $response, string $templateName, array $data): ResponseInterface
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
        return $this->twig->render($response, $templateName, array_merge(array("title" => $this->title), $data));
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