<?php
namespace Chum\Core;

use Chum\ChumFiles;
use Chum\Core\Models\Config;
use Chum\Core\Models\Theme;
use Symfony\Component\Yaml\Yaml;

class ConfigService
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
     * Finds plugin item for provided key.
     *
     * @param string $key
     * @return string|null
     */
    public function findByKey(string $plugin, string $key)
    {
        return Config::query()->where("plugin", "=", $plugin)->where("key", "=", $key)->get(["value"])->first();
    }

    /**
     * Finds core plugin item for provided key.
     *
     * @param string $key
     * @return string|null
     */
    public function findCoreByKey(string $key)
    {
        $record =  $this->findByKey('base', $key);
        return $record["value"];
    }

    public function update(string $plugin, string $key, string $value)
    {
        return Config::updateOrCreate(['plugin' => $plugin, 'key' => $key], ['value' => $value]);
    }
}