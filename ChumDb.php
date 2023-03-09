<?php

namespace Chum;

use Aura\Sql\ExtendedPdo;

final class ChumDb
{
    private ExtendedPdo $pdo;
    protected static $instance;

    public function __construct()
    {
        $this->pdo = new ExtendedPdo(
            'mysql:host=' . CHUM_DB_HOST . ';dbname=' . CHUM_DB_NAME,
            CHUM_DB_USER,
            CHUM_DB_PASSWORD,
            [],
            // driver attributes/options as key-value pairs
            [] // queries to execute after connection
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function queryForObjects(string $sql, $className, array $params = [])
    {
        return $this->pdo->fetchObjects($sql, $params, $className);
    }
}