<?php

namespace Chum\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class EventSubscriber implements EventSubscriberInterface
{
    public abstract static function getSubscribedEvents();
}