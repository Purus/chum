<?php

namespace Chum\Core;

use Chum\ChumDb;

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

    public function save( $entity )
    {
        if ( $entity === null)
        {
            throw new \InvalidArgumentException('Argument must be instance of Entity and cannot be null');
        }

        $entity->id = (int) $entity->id;

        if ( $entity->id > 0 )
        {
            $this->db->updateObject($this->getTableName(), $entity);
        }
        else
        {
            // $entity->setId (NULL);
            $entity->id =  $this->db->insertObject($this->getTableName(), $entity);
        }
    }

    public function deleteById(string $id): void
    {
        $id = (int) $id;
        if ($id > 0) {
            $sql = 'DELETE FROM ' . $this->getTableName() . ' WHERE `id` = ?';
            $result = $this->db->run($sql, array($id));
        }
    }
}