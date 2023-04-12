<?php
namespace Chum\Core;

use Chum\ChumFiles;
use Chum\Core\Models\Plugin;
use Chum\Core\PluginRepository;
use Symfony\Component\Yaml\Yaml;

class PluginService
{

    /**
     * @var PluginRepository
     */
    private $pluginDao;

    /**
     * @var array
     */
    private $pluginListCache;

    private static $classInstance;

    const SCRIPT_INIT = "init.php";
    const SCRIPT_INSTALL = "install.php";
    const SCRIPT_UNINSTALL = "uninstall.php";
    const SCRIPT_ACTIVATE = "activate.php";
    const SCRIPT_DEACTIVATE = "deactivate.php";

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
        $this->pluginDao = PluginRepository::getInstance();
    }

    private function getPluginListCache()
    {
        if (!$this->pluginListCache) {
            $this->updatePluginListCache();
        }

        return $this->pluginListCache;
    }

    private function updatePluginListCache()
    {
        $this->pluginListCache = array();
        $dbData = $this->pluginDao->findAll();

        foreach ($dbData as $plugin) {
            $this->pluginListCache[strtolower($plugin['key'])] = $plugin;
        }
    }

    /**
     * Returns all installed plugins.
     *
     * @return array
     */
    public function findAllPlugins()
    {
        return array_values($this->getPluginListCache());
    }

    /**
     * Returns list of active plugins.
     *
     * @return array
     */
    public function findActivePlugins()
    {
        $activePlugins = array();
        $pluginList = $this->findAllPlugins();

        /* @var $plugin Plugin */
        foreach ($pluginList as $plugin) {

            if ($plugin['isActive']) {
                $activePlugins[] = $plugin;
            }
        }

        return $activePlugins;
    }
    public function findInactivePlugins()
    {
        $inactivePlugins = array();
        $pluginList = $this->findAllPlugins();

        /* @var $plugin Plugin */
        foreach ($pluginList as $plugin) {

            if (!$plugin['isActive']) {
                $inactivePlugins[] = $plugin;
            }
        }

        return $inactivePlugins;
    }
    public function findAvailablePlugins()
    {
        $availPlugins = array();
        $dbPluginsArray = array_keys($this->getPluginListCache());

        $listing = ChumFiles::getInstance()->getFiles("plugins");

        foreach ($listing as $item) {
            $path = $item->path();

            if ($item instanceof \League\Flysystem\DirectoryAttributes) {
                $filename = $path . DS . "chum-plugin.yml";

                if (file_exists($filename)) {
                    $values = Yaml::parseFile($filename);
                    // $plugin = new Plugin();

                    // $plugin->version = $values['version'];
                    // $plugin->key = $values['key'];
                    // $plugin->id = '0';
                    // $plugin->name = $values['name'];
                    // $plugin->description = $values['description'];
                    // $plugin->settingsRouteName = $values['settingsRouteName'];
                    // $plugin->devName = $values['developer']['name'];
                    $values['devName'] = $values['developer']['name'];
                    unset($values['developer']);
                    unset($values['tags']);

                    if (!in_array(strtolower($values['key']), $dbPluginsArray)) {
                        $availPlugins[] = $values;
                    }
                }
            }
        }

        return $availPlugins;
    }

    /**
     * Finds plugin item for provided key.
     *
     * @param string $key
     * @return array
     */
    public function findPluginByKey($key): array
    {
        $key = strtolower($key);
        $pluginList = $this->getPluginListCache();

        if (!array_key_exists($key, $pluginList)) {
            return array();
        }

        return $pluginList[$key];
    }

    public function install($key)
    {
        $availablePlugins = array();

        foreach ($this->findAvailablePlugins() as $plugin) {
            $availablePlugins[strtolower($plugin['key'])] = $plugin;
        }

        if (empty($key) || !array_key_exists(strtolower($key), $availablePlugins)) {
            throw new \LogicException("Invalid plugin key - `{$key}` provided for install!");
        }

        $this->includeScript($this->getRootDir($plugin['key']) . PluginService::SCRIPT_INSTALL);
        $this->includeScript($this->getRootDir($plugin['key']) . PluginService::SCRIPT_ACTIVATE);

        $plugin = $availablePlugins[$key];
        $plugin['isActive'] = 1;

        $this->savePlugin($plugin);
    }

    public function getRootDir(string $key)
    {
        return CHUM_PLUGIN_ROOT . DS . strtolower($key) . DS;
    }

    public function activate($pluginKey)
    {
        if (empty($pluginKey)) {
            throw new \LogicException("Empty plugin key provided for uninstall");
        }

        $plugin = $this->findPluginByKey(trim($pluginKey));

        if ($plugin === null) {
            throw new \LogicException("Invalid plugin key - `{$pluginKey}` provided to activate");
        }

        $this->includeScript($this->getRootDir($plugin['key']) . PluginService::SCRIPT_ACTIVATE);

        $plugin['isActive'] = 1;

        $this->savePlugin($plugin);
    }

    public function deactivate($pluginKey)
    {
        if (empty($pluginKey)) {
            throw new \LogicException("Empty plugin key provided for uninstall");
        }

        $plugin = $this->findPluginByKey(trim($pluginKey));

        if ($plugin === null) {
            throw new \LogicException("Invalid plugin key - `{$pluginKey}` provided to activate");
        }

        $this->includeScript($this->getRootDir($plugin['key']) . PluginService::SCRIPT_DEACTIVATE);

        $plugin['isActive'] = 0;

        $this->savePlugin($plugin);
    }

    public function uninstall($pluginKey)
    {
        if (empty($pluginKey)) {
            throw new \LogicException("Empty plugin key provided for uninstall");
        }

        $plugin = $this->findPluginByKey(trim($pluginKey));

        if ($plugin === null) {
            throw new \LogicException("Invalid plugin key - `{$pluginKey}` provided for uninstall!");
        }

        // trigger event
/*         OW::getEventManager()->trigger(
            new OW_Event(
                OW_EventManager::ON_BEFORE_PLUGIN_UNINSTALL,
                array("pluginKey" => $pluginDto->getKey())
            )
        ); */

        $this->includeScript($this->getRootDir($plugin['key']) . PluginService::SCRIPT_DEACTIVATE);
        $this->includeScript($this->getRootDir($plugin['key']) . PluginService::SCRIPT_UNINSTALL);

        // delete plugin work dirs
        // $dirsToRemove = array(
        //     $plugin->getPluginFilesDir(),
        //     $plugin->getUserFilesDir(),
        //     $plugin->getPublicStaticDir()
        // );

        // foreach ($dirsToRemove as $dir) {
        //     if (file_exists($dir)) {
        //         UTIL_File::removeDir($dir);
        //     }
        // }

        // remove plugin configs
        // OW::getConfig()->deletePluginConfigs($pluginDto->getKey());

        //delete authorization stuff
        // BOL_AuthorizationService::getInstance()->deleteGroup($pluginDto->getKey());

        //remove entry in DB
        $this->deletePluginById($plugin['id']);

        // trigger event
        // OW::getEventManager()->trigger(
        //     new OW_Event(
        //         OW_EventManager::ON_AFTER_PLUGIN_UNINSTALL,
        //         array("pluginKey" => $pluginDto->getKey())
        //     )
        // );
    }

    public function savePlugin(array $plugin)
    {
        $this->pluginDao->save($plugin);
        $this->updatePluginListCache();
    }

    public function deletePluginById($id)
    {
        $this->pluginDao->deleteById($id);
        $this->updatePluginListCache();
    }

    public function includeScript($scriptPath)
    {
        if (file_exists($scriptPath)) {
            include_once $scriptPath;
        }
    }
}