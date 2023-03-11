<?php

namespace Chum\Core;
use Chum\Core\BaseRepository;

class PluginRepository extends BaseRepository
{
    private static $classInstance;

    public static function getInstance(): self
    {
        if ( self::$classInstance === null )
        {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return "chum_plugins";
    }
    
	/**
	 * @return string
	 */
	public function getModel(): string {
        return "Chum\Core\Models\Plugin";
	}
}