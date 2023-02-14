<?php

namespace Chum;
use Chum\Core\BaseRepository;

class UserRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function getTableName(): string
    {
        return "chum_users";
    }
	/**
	 * @return string
	 */
	public function getModel(): string {
        return "Chum\UserModel";
	}
}