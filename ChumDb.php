<?php

namespace Chum;

use Cake\Database\Connection;
use PDO;
use Cake\Database\Query;

final class ChumDb
{
    private Connection $connection;
    protected static $instance;

    public function __construct()
    {
        $this->connection = new Connection(
            array(
                'driver' => \Cake\Database\Driver\Mysql::class,
                'host' => CHUM_DB_HOST,
                'database' => CHUM_DB_NAME,
                'username' => CHUM_DB_USER,
                'password' => CHUM_DB_PASSWORD,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                // Enable identifier quoting
                'quoteIdentifiers' => true,
                // Set to null to use MySQL servers timezone
                'timezone' => null,
                // PDO options
                'flags' => [
                        // Turn off persistent connections
                    PDO::ATTR_PERSISTENT => false,
                        // Enable exceptions
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        // Emulate prepared statements
                    PDO::ATTR_EMULATE_PREPARES => true,
                        // Set default fetch mode to array
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        // Set character set
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
                ],
            )
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function pdo(): PDO
    {
        return $this->connection->getDriver()->getConnection();
    }

    public function newQuery(): Query
    {
        return $this->connection->newQuery();
    }

    public function select(string $table): Query
    {
        $query = $this->newQuery()->from($table);

        if (!$query instanceof Query) {
            throw new \UnexpectedValueException('Failed to create query');
        }

        return $query;
    }

    public function update(string $table, array $data): Query
    {
        return $this->newQuery()->update($table)->set($data);
    }

    /**
     * Create an 'update' statement for the given table.
     *
     * @param string $table The table to update rows from
     * @param array $data The values to be updated
     *
     * @return Query The new insert query
     */
    public function insert(string $table, array $data): Query
    {
        return $this->newQuery()
            ->insert(array_keys($data))
            ->into($table)
            ->values($data);
    }

    /**
     * Create a 'delete' query for the given table.
     *
     * @param string $table The table to delete from
     *
     * @return Query A new delete query
     */
    public function delete(string $table): Query
    {
        return $this->newQuery()->delete($table);
    }  
    
    /**
     * Run a sql query.
     *
     * @param string $sql The sql to run
     *
     */
    public function run(string $sql)
    {
        $this->newQuery()->getConnection()->execute($sql);
    }
}