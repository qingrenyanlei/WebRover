<?php


namespace WebRover\Framework\Event\Facade;


use Symfony\Component\EventDispatcher\Event as SymfonyEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Event
 * @package WebRover\Framework\Event\Facade
 * @mixin EventDispatcher
 * @method SymfonyEvent dispatch($eventName, SymfonyEvent $event = null) static
 * @method array getListeners($eventName = null) static
 * @method int|null getListenerPriority($eventName, $listener) static
 * @method bool hasListeners($eventName = null) static
 */
class Event extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'event';
    }
}