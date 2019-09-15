<?php


namespace WebRover\Framework\Routing\Loader;


use Symfony\Component\Config\Loader\Loader;
use WebRover\Framework\Routing\RouteCollection;

/**
 * ClosureLoader loads routes from a PHP closure.
 *
 * The Closure must return a RouteCollection instance.
 *
 * Class ClosureLoader
 * @package WebRover\Framework\Routing\Loader
 */
class ClosureLoader extends Loader
{
    /**
     * Loads a Closure.
     *
     * @param \Closure $closure A Closure
     * @param string|null $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function load($closure, $type = null)
    {
        return $closure();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $resource instanceof \Closure && (!$type || 'closure' === $type);
    }
}