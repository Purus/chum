<?php

namespace Chum;

use Aura\Sql\ExtendedPdo;
use PDO;

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

    public function run($sql, array $params = null)
    {
        $stmt = $this->pdo->prepare($sql);

        if ($params !== null) {
            foreach ($params as $key => $value) {
                $paramType = PDO::PARAM_STR;
                if (is_int($value))
                    $paramType = PDO::PARAM_INT;
                elseif (is_bool($value))
                    $paramType = PDO::PARAM_BOOL;

                $stmt->bindValue(is_int($key) ? $key + 1 : $key, $value, $paramType);
            }
        }
        $stmt->execute();

        return $stmt;
    }

    public function insertObject( $tableName, $obj, $delayed = false )
    {
        if ( $obj != null && is_object($obj) )
        {
            $params = get_object_vars($obj);
            $paramNames = array_keys($params);
            $columns = $this->arrayToDelimitedString($paramNames, ',', '`', '`');
            $values = $this->arrayToDelimitedString($paramNames, ',', ':');
            $sql = "INSERT" . ($delayed ? " DELAYED" : "") . " INTO `{$tableName}` ({$columns}) VALUES ({$values})";
            return $this->insert($sql, $params);
        }
        else
        {
            throw new \InvalidArgumentException('object expected');
        }
    }

    public function updateObject( $tableName, $obj, $primaryKeyName = 'id', $lowPriority = false )
    {
        if ( $obj != null && is_object($obj) )
        {
            $params = get_object_vars($obj);

            if ( !array_key_exists($primaryKeyName, $params) )
            {
                throw new \InvalidArgumentException('object property not found');
            }

            $fieldsToUpdate = $obj->getEntinyUpdatedFields();

            if ( empty($fieldsToUpdate) )
            {
                return true;
            }

            $updateArray = array();
            foreach ( $params as $key => $value )
            {
                if ( $key !== $primaryKeyName )
                {
                    if ( in_array($key, $fieldsToUpdate) )
                    {
                        $updateArray[] = '`' . $key . '`=:' . $key;
                    }
                    else
                    {
                        unset($params[$key]);
                    }
                }
            }

            $updateStmt = $this->arrayToDelimitedString($updateArray);
            $sql = "UPDATE" . ($lowPriority ? " LOW_PRIORITY" : "") . " `{$tableName}` SET {$updateStmt} WHERE {$primaryKeyName}=:{$primaryKeyName}";
            return $this->run($sql, $params);
        }
        else
        {
            throw new \InvalidArgumentException('object expected');
        }
    }
    public function insert( $sql, array $params = null )
    {
        $stmt = $this->run($sql, $params);
        $lastInsertId = $this->pdo->lastInsertId();
        $stmt->closeCursor();
        return $lastInsertId;
    }

    public static function arrayToDelimitedString( array $array, $delimiter = ',', $left = '', $right = '' )
    {
        $result = '';
        foreach ( $array as $value )
        {
            $result .= ( $left . $value . $right . $delimiter);
        }
        $length = mb_strlen($result);
        if ( $length > 0 )
        {
            $result = mb_substr($result, 0, $length - 1);
        }
        return $result;
    }

}