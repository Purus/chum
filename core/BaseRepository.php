<?php

namespace Chum\Core;

use Chum\ChumDb;
use Doctrine\DBAL\Connection;
use stdClass;

abstract class BaseRepository
{
    public abstract function getTableName(): string;
    public abstract function getModel(): string;
    protected $db;

    public function __construct()
    {
        $this->db = ChumDb::getInstance();
    }

    public function findAll(): array
    {
        return $this->db->queryForObjects("SELECT * FROM " . $this->getTableName() . " ;", $this->getModel());
    }
}