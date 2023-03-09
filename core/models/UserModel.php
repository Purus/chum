<?php

namespace Chum\Core\Models;

class UserModel
{
    protected string $first_name;
    protected string $last_name;

	/**
	 * @return string
	 */
	public function getFirstName(): string {
		return $this->first_name;
	}
	
	/**
	 * @param string $first_name 
	 * @return self
	 */
	public function setFirstName(string $first_name): self {
		$this->first_name = $first_name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName(): string {
		return $this->last_name;
	}
	
	/**
	 * @param string $last_name 
	 * @return self
	 */
	public function setLastName(string $last_name): self {
		$this->last_name = $last_name;
		return $this;
	}
}