<?php


namespace WebRover\Framework\Routing\Matcher\Dumper;


use WebRover\Framework\Routing\Route;

/**
 * Container for a Route.
 *
 * Class DumperRoute
 * @package WebRover\Framework\Routing\Matcher\Dumper
 */
class DumperRoute
{
    private $name;
    private $route;

    /**
     * @param string $name The route name
     * @param Route $route The route
     */
    public function __construct($name, Route $route)
    {
        $this->name = $name;
        $this->route = $route;
    }

    /**
     * Returns the route name.
     *
     * @return string The route name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the route.
     *
     * @return Route The route
     */
    public function getRoute()
    {
        return $this->route;
    }
}