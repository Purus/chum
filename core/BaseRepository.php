<?php

namespace Chum\Core;

use Chum\ChumDb;
use Chum\Core\Models\Entity;
use PDO;

abstract class BaseRepository
{
    public abstract function getTableName(): string;
    public abstract function getModel(): string;
    protected ChumDb $db;

    public function __construct()
    {
        $this->db = ChumDb::getInstance();
    }

    public function findAll(): array
    {
        return $this->db->select($this->getTableName())->select("*")->execute()->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $entity)
    {
        if ($entity === null || empty(($entity))) {
            throw new \InvalidArgumentException('Argument must be instance of Entity and cannot be null');
        }

        $entityId = $entity['id'] ?? 0;

        if ($entityId > 0) {
            $this->db->update($this->getTableName(), $entity)->execute();
        } else {
            $this->db->insert($this->getTableName(), $entity)->execute();
        }
    }

    public function deleteById(string $id): void
    {
        $id = (int) $id;
        if ($id > 0) {
            $this->db->delete($this->getTableName())->andWhere(["id" => $id])->execute();
        }
    }

    public static function toObject(array $array, $object)
    {
        $class = get_class($object);
        $methods = get_class_methods($class);
        foreach ($methods as $method) {
            preg_match(' /^(set)(.*?)$/i', $method, $results);
            $pre = $results[1] ?? '';
            $k = $results[2] ?? '';
            $k = strtolower(substr($k, 0, 1)) . substr($k, 1);
            if ($pre == 'set' && !empty($array[$k])) {
                $object->$method($array[$k]);
            }
        }
        return $object;
    }
}