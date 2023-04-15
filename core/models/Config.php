<?php

namespace Chum\Core\Models;

use Chum\Core\Models\Entity;

class Config extends Entity
{
    protected $table = 'configs';
    public $timestamps = false;
    protected $fillable = ['key', 'plugin', 'value', 'description'];
}