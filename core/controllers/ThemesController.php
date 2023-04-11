<?php
declare(strict_types=1);

namespace Chum\Core\Controllers;

use Chum\ChumDb;
use Chum\Core\BaseController;
use Chum\Core\PluginService;
use Chum\Core\ThemeService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ThemesController extends BaseController
{

    public function themes(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $currentTheme = ThemeService::getInstance()->findCurrentTheme();
        $availableThemes = ThemeService::getInstance()->findAvailableThemes();
        dump($availableThemes);

        $this->setPageTitle("Themes");

        return $this->render(
            $request,
            $response,
            'admin/admin.themes.twig',
            array('availableThemes' => array_values($availableThemes), 'currentTheme' => $currentTheme)
        );
    }

    public function activate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $themeKey = $args['key'];

        ThemeService::getInstance()->activate($themeKey);

        return $this->redirectByName($response, "admin.themes");
    }

    public function deactivate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $themeKey = $args['key'];

        ThemeService::getInstance()->deactivate($themeKey);

        return $this->redirectByName($response, "admin.themes");
    }
}