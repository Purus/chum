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


    /**
     * Returns all installed plugins.
     *
     * @return array<Plugin>
     */
    public function findAllPlugins()
    {
        return $this->pluginDao->findAll();
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


}