<?php


namespace WebRover\Framework\Kernel\Container;


/**
 * Class Container
 * @package WebRover\Framework\Kernel\Container
 */
class Container extends \Pimple\Container
{
    protected static $instance;

    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    public function bind($abstract, $concrete = null, $singleton = false)
    {
        $this->offsetUnset($abstract);

        if (is_null($concrete)) {
            $concrete = $abstract;
        }


        if ($singleton || !$concrete instanceof \Closure) {
            $this->offsetSet($abstract, $concrete);

        } else {
            $this->offsetSet($abstract, $this->factory($concrete));
        }
    }

    public function make($abstract)
    {
        return $this->offsetGet($abstract);
    }

    public static function setInstance($instance)
    {
        static::$instance = $instance;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        return self::$instance;
    }
}