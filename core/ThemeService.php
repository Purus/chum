<?php
namespace Chum\Core;

use Chum\ChumFiles;
use Chum\Core\Models\Config;
use Chum\Core\Models\Theme;
use Symfony\Component\Yaml\Yaml;

class ThemeService
{
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
     * Returns currently active theme.
     *
     * @return string|null
     */
    public function getCurrentThemeName()
    {
        $themName = ConfigService::getInstance()->findCoreByKey("current_theme");
        return $themName;
    }

    /**
     * Returns currently active theme.
     *
     * @return Theme|null
     */
    public function getCurrentTheme()
    {
        $currentThemeName = $this->getCurrentThemeName();

        if ($currentThemeName == null) {
            return null;
        }

        $allThemes = $this->getAvailableThemes();

        $theme = $allThemes[$currentThemeName];
        if ($theme == null) {
            return null;
        }

        $theme->isActive = 1;

        return $theme;
    }

    public function getAvailableThemes()
    {
        $availThemes = array();
        $currentThemeName = $this->getCurrentThemeName();

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
                    $theme->customCss = '';
                    $theme->name = $values['name'];
                    $theme->description = $values['description'];
                    $theme->devName = $values['developer']['name'];

                    if ($currentThemeName == $values['key']) {
                        $theme->isActive = 1;
                    } else {
                        $theme->isActive = 0;
                    }

                    $availThemes[$values['key']] = $theme;

                }
            }
        }

        uasort($availThemes, fn(Theme $a, Theme $b): int => $b->isActive <=> $a->isActive);

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
        $themesList = $this->getAvailableThemes();

        if (!array_key_exists($key, $themesList)) {
            return null;
        }

        return $themesList[$key];
    }

    public function activate($themeKey)
    {
        if (empty($themeKey)) {
            throw new \LogicException("Empty plugin key provided");
        }

        $currentTheme = $this->getCurrentTheme();
        $currentValues = $currentTheme->toArray();
        $currentValues['isActive'] = 0;
        Theme::updateOrCreate(['key' => $currentTheme['key']], $currentValues);

        $newTheme = $this->findThemeByKey($themeKey);

        if ($newTheme == null) {
            throw new \LogicException("theme with name - `{$themeKey['name']}` is invalid");
        }
        $newValues = $newTheme->toArray();
        $newValues['isActive'] = 1;

        Theme::updateOrCreate(['key' => $themeKey], $newValues);

        ConfigService::getInstance()->update('base', 'current_theme', $newTheme['key']);
    }
}