<?php

namespace Chum;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\Filesystem;

final class ChumFiles
{
    private Filesystem $fs;
    protected static $instance;

    public function __construct()
    {
        $adapter = new LocalFilesystemAdapter(CHUM_DIR_ROOT);

        $this->fs =  new Filesystem($adapter);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function getFiles(string $path)
    {
        return $this->fs->listContents($path);
    }    
    
    public function read(string $path)
    {
        return $this->fs->read($path);
    }
}