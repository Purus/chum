<?php

namespace Chum\Core\Models;

use Chum\Core\Models\Entity;

class Plugin extends Entity
{
    protected $table = 'plugins';
    protected $fillable = ['name', 'key', 'description', 'isActive', 'version', 'devName', 'settingsRouteName', 'updated_at', 'created_at'];

    public function getRootDir()
    {
        return CHUM_PLUGIN_ROOT . DS . strtolower($this->key) . DS;
    }

}