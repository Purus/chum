<?php

namespace Chum\Core\Models;

use Chum\Core\Models\Entity;

class Theme extends Entity
{
    public string $name;
    public string $key;
    public string $description;
    public bool $isActive;
    public string $version;
    public string $devName;
    public string $customCss;
}