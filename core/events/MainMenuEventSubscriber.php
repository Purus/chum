<?php

namespace Chum\Events;

use Chum\Events\NewMainMenuEvent;
use Chum\Events\EventSubscriber;

class MainMenuEventSubscriber extends EventSubscriber
{

  /**
   * @return array
   */
  public static function getSubscribedEvents()
  {
    return [NewMainMenuEvent::NAME => 'onMainMenuAdd'];
  }

  public function onMainMenuAdd(NewMainMenuEvent $event)
  {
    // echo $event->getMenu();
  }
}