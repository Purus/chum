<?php
namespace Chum\Events;

use Symfony\Contracts\EventDispatcher\Event;

class NewMainMenuEvent extends Event
{
    public const NAME = 'main.menu.add';
    protected $menu;

    public function __construct(string $menu)
    {
        $this->menu = $menu;
    }

    public function getMenu(){
        return $this->menu;
    }

}