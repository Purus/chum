<?php
namespace Chum\Core;

use Chum\Core\Models\Plugin;
use Chum\Core\PluginRepository;

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

        /* @var $plugin Plugin */
        foreach ($dbData as $plugin) {
            $this->pluginListCache[$plugin->getKey()] = $plugin;
        }
    }

    /**
     * Returns all installed plugins.
     *
     * @return array<Plugin>
     */
    public function findAllPlugins()
    {
        return array_values($this->getPluginListCache());
    }

    /**
     * Returns list of active plugins.
     *
     * @return array<Plugin>
     */
    public function findActivePlugins()
    {
        $activePlugins = array();
        $pluginList = $this->findAllPlugins();

        /* @var $plugin Plugin */
        foreach ($pluginList as $plugin) {

            if ($plugin->isActive()) {
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

            if (!$plugin->isActive()) {
                $inactivePlugins[] = $plugin;
            }
        }

        return $inactivePlugins;
    }
    public function findAvailablePlugins()
    {
        $inactivePlugins = array();

        //TODO
        return $inactivePlugins;
    }

    /**
     * Finds plugin item for provided key.
     *
     * @param string $key
     * @return Plugin|null
     */
    public function findPluginByKey($key)
    {
        $key = strtolower($key);
        $pluginList = $this->getPluginListCache();

        if (!array_key_exists($key, $pluginList)) {
            return null;
        }

        return $pluginList[$key];
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

        $this->includeScript($plugin->getRootDir() . BOL_PluginService::SCRIPT_DEACTIVATE);
        $this->includeScript($plugin->getRootDir() . BOL_PluginService::SCRIPT_UNINSTALL);

        // delete plugin work dirs
        $dirsToRemove = array(
            $plugin->getPluginFilesDir(),
            $plugin->getUserFilesDir(),
            $plugin->getPublicStaticDir()
        );

        foreach ($dirsToRemove as $dir) {
            if (file_exists($dir)) {
                UTIL_File::removeDir($dir);
            }
        }

        // remove plugin configs
        // OW::getConfig()->deletePluginConfigs($pluginDto->getKey());

        //delete authorization stuff
        // BOL_AuthorizationService::getInstance()->deleteGroup($pluginDto->getKey());

        //remove entry in DB
        $this->deletePluginById($plugin->getId());

        // trigger event
        // OW::getEventManager()->trigger(
        //     new OW_Event(
        //         OW_EventManager::ON_AFTER_PLUGIN_UNINSTALL,
        //         array("pluginKey" => $pluginDto->getKey())
        //     )
        // );
    }

    public function deletePluginById($id)
    {
        $this->pluginDao->deleteById($id);
        $this->updatePluginListCache();
    }
}