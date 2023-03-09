<?php
namespace Chum\Core\Controllers;

class InstallAppConfig
{
    protected $language;
    protected $siteUrl;
    protected $tagLine;
    protected $siteName;
    protected $rootPath;

    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    public function setRootPath(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }    
    
    public function getSiteName(): string
    {
        return $this->siteName;
    }

    public function setSiteName(string $siteName): void
    {
        $this->siteName = $siteName;
    }      
    
    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }   
    
    public function getTagLine(): string
    {
        return $this->tagLine;
    }

    public function setTagLine(string $tagLine): void
    {
        $this->tagLine = $tagLine;
    }

    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(string $siteUrl): void
    {
        $this->siteUrl = $siteUrl;
    }
}