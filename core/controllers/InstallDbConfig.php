<?php
namespace Chum\Core\Controllers;

class InstallDbConfig
{
    protected $dbName;
    protected $dbHost;
    protected $dbUser;
    protected $dbPassword;
    protected $dbPrefix;

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }    

        public function getDbHost(): string
    {
        return $this->dbHost;
    }

    public function setDbHost(string $dbHost): void
    {
        $this->dbHost = $dbHost;
    }    
    
    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    public function setDbUser(string $dbUser): void
    {
        $this->dbUser = $dbUser;
    }      
    
    public function getDbPassword(): string
    {
        return $this->dbPassword;
    }

    public function setDbPassword(string $dbPassword): void
    {
        $this->dbPassword = $dbPassword;
    }   
    
    public function getDbPrefix(): string
    {
        return $this->dbPrefix;
    }

    public function setDbPrefix(string $dbPrefix): void
    {
        $this->dbPrefix = $dbPrefix;
    }
}