<?php

namespace Chum;

use Chum\Events\EventSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Contracts\EventDispatcher\Event;

final class ChumEvent
{
    private EventDispatcher $dispacter;
    protected static $instance;

    public function __construct()
    {
        $this->dispacter = new EventDispatcher();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function addSubscriber(EventSubscriber $subscriber)
    {
        return $this->dispacter->addSubscriber($subscriber);
    }

    public function dispatch(Event $event, string $eventName)
    {
        return $this->dispacter->dispatch($event, $eventName);
    }

    public function addListener(string $eventName, callable |array $listener, int $priority = 0)
    {
        return $this->dispacter->addListener($eventName, $listener, $priority);
    }
}