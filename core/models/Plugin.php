<?php

namespace Chum\Core\Models;

use Chum\Core\Models\Entity;

class Plugin extends Entity
{
    public string $name;
    public string $key;
    public string $description;
    public bool $isActive;
    public string $version;
    public string $devName;
    public string $settingsRouteName;

    public function getRootDir()
    {
        return CHUM_PLUGIN_ROOT . DS. strtolower($this->key) . DS;
    }

}