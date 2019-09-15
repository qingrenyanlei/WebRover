<?php


namespace WebRover\Framework\Event;


use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebRover\Framework\Kernel\Application;
use WebRover\Framework\Kernel\Container\ServiceProvider;

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

            $modules = $app->getModules();

            foreach ($modules as $moduleName => $module) {

                $configFile = $module->getConfigPath() . DIRECTORY_SEPARATOR . 'service.php';

                if (!file_exists($configFile)) continue;

                $moduleConfig = include $configFile;

                if (!is_array($moduleConfig) || isset($moduleConfig['event']) || !is_array($moduleConfig['event'])) continue;

                $eventConfig = $moduleConfig['event'];

                if (isset($eventConfig['listen']) && is_array($eventConfig['listen'])) {

                    $listen = $eventConfig['listen'];

                    foreach ($listen as $event => $listeners) {

                        if (!isset($this->listen[$event])) $this->listen[$event] = [];

                        if (!trim($event) || !is_array($listeners)) continue;

                        $listeners = array_map('trim', $listeners);

                        $this->listen[$event] = array_unique(array_merge($this->listen[$event], $listeners));
                    }

                }

                if (!isset($eventConfig['subscribe']) || !is_array($eventConfig['subscribe'])) continue;

                $subscribe = array_map('trim', $eventConfig['subscribe']);

                $this->subscribe = array_unique(array_merge($this->subscribe, $subscribe));
            }

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
                    $subscriber = $app->offsetGet($subscriber);
                } else {
                    $subscriber = new $subscriber;
                }

                if (!($subscriber instanceof EventSubscriberInterface)) {
                    throw new \InvalidArgumentException('event subscriber ' . $subscriber . ' in module ' . $moduleName . ' must is an instance of EventSubscriberInterface');
                }

                $dispatcher->addSubscriber($subscriber);
            }

            return $dispatcher;
        });
    }
}