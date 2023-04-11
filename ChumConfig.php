<?php

namespace Chum;

use Chum\Core\ConfigRepository;

final class ChumConfig
{
    private ConfigRepository $config;
    private ChumDb $db;
    protected static $instance;

    public function __construct()
    {
        $this->config = ConfigRepository::getInstance();
        $this->db = ChumDb::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getConfig(string $key)
    {        
        return $this->config->findAll();
    }

}