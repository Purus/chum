<?php
namespace Chum\Core;

use Chum\ChumFiles;
use Chum\Core\Models\Theme;
use Chum\Core\ThemeRepository;
use Symfony\Component\Yaml\Yaml;

class ThemeService
{

    /**
     * @var ThemeRepository
     */
    private $themeDao;

    /**
     * @var array
     */
    private $themeListCache;

    private static $classInstance;

    public static function getInstance()
    {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        $this->themeDao = ThemeRepository::getInstance();
    }

    /**
     * Returns currently active theme.
     *
     * @return Theme|null
     */
    public function findCurrentTheme()
    {
        $theme = $this->findThemeByKey("chum-chum");

        return $theme;
    }

    public function findAvailableThemes()
    {
        $availThemes = array();

        $listing = ChumFiles::getInstance()->getFiles("themes");

        foreach ($listing as $item) {
            $path = $item->path();

            if ($item instanceof \League\Flysystem\DirectoryAttributes) {
                $filename = $path . DS . "chum-theme.yml";

                if (file_exists($filename)) {
                    $values = Yaml::parseFile($filename);
                    $theme = new Theme();

                    $theme->version = $values['version'];
                    $theme->key = $values['key'];
                    $theme->id = '0';
                    $theme->customCss = '';
                    $theme->name = $values['name'];
                    $theme->description = $values['description'];
                    $theme->devName = $values['developer']['name'];

                    // if ($this->findCurrentTheme() == $values['key']) {
                        $theme->isActive = 1;
                    // } else {
                    //     $theme->isActive = 0;
                    // }

                    $availThemes[$values['key']] = $theme;

                }
            }
        }

        return $availThemes;
    }

    /**
     * Finds plugin item for provided key.
     *
     * @param string $key
     * @return Theme|null
     */
    public function findThemeByKey($key)
    {
        $key = strtolower($key);
        $themesList = $this->findAvailableThemes();
        dump($themesList);

        if (!array_key_exists($key, $themesList)) {
            return null;
        }

        return $themesList[$key];
    }

    public function activate($themeKey)
    {
        if (empty($themeKey)) {
            throw new \LogicException("Empty plugin key provided for uninstall");
        }

        $theme = $this->findThemeByKey(trim($themeKey));

        if ($theme === null) {
            throw new \LogicException("Invalid theme key - `{$themeKey}` provided to activate");
        }

        if($theme->isActive == 1){
            throw new \LogicException("theme key - `{$themeKey}` already active");
        }

        $theme->isActive = 1;

        $this->saveTheme($theme);
    }

    public function deactivate($themeKey)
    {
        if (empty($themeKey)) {
            throw new \LogicException("Empty theme key provided for uninstall");
        }

        $theme = $this->findThemeByKey(trim($themeKey));

        if ($theme === null) {
            throw new \LogicException("Invalid theme key - `{$themeKey}` provided to activate");
        }

        if($theme->isActive == 0){
            throw new \LogicException("theme key - `{$themeKey}` not active");
        }

        $theme->isActive = 0;

        $this->saveTheme($theme);
    }

    public function saveTheme(Theme $theme)
    {
        $this->themeDao->save($theme);
    }
}