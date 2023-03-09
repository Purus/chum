<?php
namespace Chum\Core\Controllers;

class InstallAdminConfig
{
	protected string $firstName;
	protected string $lastName;
	protected string $email;
	protected string $username;
	protected string $password;

	/**
	 * @return mixed
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param string $firstName 
	 */
	public function setFirstName($firstName): void
	{
		$this->firstName = $firstName;
	}

	/**
	 * Get the value of username
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * Set the value of username
	 */
	public function setUsername($username): void
	{
		$this->username = $username;
	}

	/**
	 * Get the value of email
	 */
	public function getEmail(): string
	{
		return $this->email;
	}

	/**
	 * Set the value of email
	 */
	public function setEmail($email): void
	{
		$this->email = $email;
	}

	/**
	 * Get the value of lastName
	 */
	public function getLastName(): string
	{
		return $this->lastName;
	}

	/**
	 * Set the value of lastName
	 */
	public function setLastName($lastName): void
	{
		$this->lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param string $password 
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}
}