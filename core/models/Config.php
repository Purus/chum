<?php

namespace Chum\Core\Models;

use Chum\Core\Models\Entity;

class Config extends Entity
{
    public string $key;
    public string $plugin;
    public string $value;
    public string $description;
}