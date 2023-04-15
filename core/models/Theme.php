<?php

namespace Chum\Core\Models;

use Chum\Core\Models\Entity;

class Theme extends Entity
{
    protected $table = 'themes';
    protected $fillable = ['name', 'key', 'description', 'isActive', 'version', 'devName', 'customCss', 'updated_at', 'created_at'];
}