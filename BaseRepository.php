<?php

namespace Chum;

use Doctrine\DBAL\Connection;

abstract class BaseRepository
{
    public abstract function getTableName(): string;
    public abstract function getModel(): string;

    /**
     * @var Connection The database connection
     */
    protected Connection $connection;

    /**
     * The constructor.
     *
     * @param Connection $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll()
    {
        $query = $this->connection->createQueryBuilder();
        $rows = $query->select("*")->from($this->getTableName())->executeQuery()->fetchAllAssociative();

        foreach($rows as $row){
            $user = json_decode(json_encode($row));
            dump($user);
        }

        return $rows;
    }
}