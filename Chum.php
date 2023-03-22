<?php

namespace Chum;

use Chum\ChumDb;
use Chum\ChumEvent;
use Chum\ChumFiles;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;

final class Chum{
    public static function getDbo() : ChumDb
    {
        return ChumDb::getInstance();
    }    
    public static function getStorage() : ChumFiles
    {
        return ChumFiles::getInstance();
    }    
    
    public static function getEventManager() : ChumEvent
    {
        return ChumEvent::getInstance();
    }        
    
    public static function getLogger() : Logger
    {
        $logger = new Logger('ChumChum');

        $logger->pushProcessor(new IntrospectionProcessor());

        $handler = new RotatingFileHandler('logs' . DS . 'logs.log', 5, \Monolog\Level::Error);
        $logger->pushHandler($handler);

        return $logger;
    }    

}