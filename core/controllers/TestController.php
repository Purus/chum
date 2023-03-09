<?php
declare(strict_types=1);

namespace Chum\Core\Controllers;

use Chum\Core\BaseController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Mime\Email;

class TestController extends BaseController
{
    public function showBlank(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (!defined('CHUM_DB_INSTALLED') || (defined('CHUM_DB_INSTALLED') && CHUM_DB_INSTALLED != '1')) {
            return $this->redirectByName($response, 'install');
        }

        // echo $this->translator->trans("core.hello");

        return $this->render($request, $response, 'blank.twig', array());
    }

    public function testEmail(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $email = new Email();
        $email->from('sender@example.test')
            ->to('your-email@here.test')
            ->priority(Email::PRIORITY_HIGHEST)
            ->subject('My first mail')
            ->text('This is an important message!')
            ->html('<strong>This is an important message!</strong>');

        $this->sendEmail($email);

        // $m = new ChumMailer();
        // $m->sendEmail(($email));

        echo "Email Sent";

        return $response;
    }

    public function writeFile(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $this->getFileSystem()->write("test.txt", "test");

        echo "File written";

        return $response;
    }
}