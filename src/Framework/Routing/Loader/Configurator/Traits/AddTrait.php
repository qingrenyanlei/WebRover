<?php


namespace WebRover\Framework\Routing\Loader\Configurator\Traits;


use WebRover\Framework\Routing\Loader\Configurator\RouteConfigurator;
use WebRover\Framework\Routing\Route;
use WebRover\Framework\Routing\RouteCollection;

trait AddTrait
{
    /**
     * @var RouteCollection
     */
    private $collection;

    private $name = '';

    /**
     * Adds a route.
     *
     * @param string $name
     * @param string $path
     *
     * @return RouteConfigurator
     */
    final public function add($name, $path)
    {
        $parentConfigurator = $this instanceof RouteConfigurator ? $this->parentConfigurator : null;
        $this->collection->add($this->name.$name, $route = new Route($path));

        return new RouteConfigurator($this->collection, $route, '', $parentConfigurator);
    }

    /**
     * Adds a route.
     *
     * @param string $name
     * @param string $path
     *
     * @return RouteConfigurator
     */
    final public function __invoke($name, $path)
    {
        return $this->add($name, $path);
    }
}