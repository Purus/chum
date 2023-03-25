<?php

use Chum\ChumEvent;
use Chum\Events\NewMainMenuEvent;

$event = new NewMainMenuEvent("blogs");
ChumEvent::getInstance()->dispatch($event, NewMainMenuEvent::NAME);
