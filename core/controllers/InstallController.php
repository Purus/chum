<?php
namespace Chum\Core\Controllers;

use Chum\ChumDb;
use Chum\Core\BaseController;
use Chum\UserRepository;
use Psr\Http\Message\ServerRequestInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Slim\Http\Interfaces\ResponseInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class InstallController extends BaseController
{
    //TODO check session values and redirect to appropriate install screen in case user startin from middle
    public function index(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        if (defined('CHUM_DB_INSTALLED') && CHUM_DB_INSTALLED == '1') {
            return $this->redirectByName($response, 'home');
        }

        $app = new InstallAppConfig();
        $app->setLanguage('en');
        $app->setRootPath(CHUM_DIR_ROOT);
        $app->setSiteName('My Network');
        $app->setSiteUrl($request->getUri()->getHost());
        $app->setTagLine('Lets Enjoy');

        $form = $this->createForm(InstallAppConfigType::class, $app);

        $req = Request::createFromGlobals();

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $app = $form->getData();

            $this->session->set('siteName', $app->getSiteName());
            $this->session->set('tagLine', $app->getTagLine());
            $this->session->set('rootPath', $app->getRootPath());
            $this->session->set('siteUrl', $app->getSiteUrl());

            return $this->redirectByName($response, "install.db");
        }

        return $this->render(
            $request,
            $response,
            'install.start.twig',
            array('form' => $form->createView())
        );
    }

    public function database(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
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

        return $this->render($request, $response, 'install.db.twig', array('form' => $form->createView()));
    }

    public function finish(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $admin = new InstallAdminConfig();
        $admin->setEmail('a@a.com');
        $admin->setFirstName('firstname');
        $admin->setLastName('secondname');
        $admin->setUsername('admin');
        $admin->setPassword('admin');

        $form = $this->createForm(InstallAdminConfigType::class, $admin);

        $req = Request::createFromGlobals();

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin = $form->getData();

            $data = $this->session->all();

            if(!file_exists(CHUM_DIR_ROOT . 'config'. DS. 'db.sample.php')){
                //TODO action if sample db file is not present
            }

            $str = file_get_contents(CHUM_DIR_ROOT . 'config'. DS. 'db.sample.php');

            $str = str_replace("<CHUM_DB_INSTALLED>", "1", $str);
            $str = str_replace("<CHUM_DB_HOST>", $data['dbHost'], $str);
            $str = str_replace("<CHUM_DB_USER>", $data['dbUser'], $str);
            $str = str_replace("<CHUM_DB_NAME>", $data['dbName'], $str);
            $str = str_replace("<CHUM_DB_PASSWORD>", $data['dbPassword'], $str);
            $str = str_replace("<CHUM_DB_PREFIX>", $data['dbPrefix'], $str);

            file_put_contents(CHUM_DIR_ROOT . 'config'. DS. 'db.php', $str);

            //TODO delete installation folder
            return $this->redirectByName($response, "home");
        }

        return $this->render($request, $response, 'install.finish.twig', array('form' => $form->createView()));
    }
}