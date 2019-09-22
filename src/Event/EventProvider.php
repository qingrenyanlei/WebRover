<?php


namespace WebRover\Framework\Event;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebRover\Framework\Container\ServiceProvider;
use WebRover\Framework\Kernel\Application;

/**
 * Class EventProvider
 * @package WebRover\Framework\Event
 */
class EventProvider extends ServiceProvider
{
    protected $listen = [];

    protected $subscribe = [];

    public function register()
    {
        $this->app->singleton('event', function (Application $app) {

            $dispatcher = new EventDispatcher();

            foreach ($this->listen as $event => $listeners) {
                $listeners = array_reverse($listeners);

                foreach ($listeners as $k => $listener) {
                    if ($app->offsetExists($listener)) {
                        $listener = $app->offsetGet($listener);
                    } else {
                        $listener = new $listener;
                    }

                    if (!method_exists($listener, 'handle')) continue;

                    $dispatcher->addListener($event, [$listener, 'handle'], $k);
                }
            }

            foreach ($this->subscribe as $subscriber) {
                if ($app->offsetExists($subscriber)) {
                    $subscriber = $app->make($subscriber);
                } else {
                    $subscriber = new $subscriber;
                }

                if (!($subscriber instanceof EventSubscriberInterface)) {
                    throw new \InvalidArgumentException('event subscriber ' . $subscriber . ' must is an instance of EventSubscriberInterface');
                }

                $dispatcher->addSubscriber($subscriber);
            }


            return $dispatcher;
        });
    }
}