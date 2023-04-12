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

    // public function getRootDir()
    // {
    //     return CHUM_PLUGIN_ROOT . DS. strtolower($this->key) . DS;
    // }


    /**
     * Get the value of settingsRouteName
     *
     * @return string
     */
    public function getSettingsRouteName(): string
    {
        return $this->settingsRouteName;
    }

    /**
     * Set the value of settingsRouteName
     *
     * @param string $settingsRouteName
     *
     * @return self
     */
    public function setSettingsRouteName(string $settingsRouteName): self
    {
        $this->settingsRouteName = $settingsRouteName;

        return $this;
    }

    /**
     * Get the value of devName
     *
     * @return string
     */
    public function getDevName(): string
    {
        return $this->devName;
    }

    /**
     * Set the value of devName
     *
     * @param string $devName
     *
     * @return self
     */
    public function setDevName(string $devName): self
    {
        $this->devName = $devName;

        return $this;
    }

    /**
     * Get the value of version
     *
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Set the value of version
     *
     * @param string $version
     *
     * @return self
     */
    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the value of isActive
     *
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    /**
     * Set the value of isActive
     *
     * @param bool $isActive
     *
     * @return self
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get the value of description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @param string $description
     *
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Set the value of key
     *
     * @param string $key
     *
     * @return self
     */
    public function setKey(string $key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}