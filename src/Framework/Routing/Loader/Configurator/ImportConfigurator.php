<?php


namespace WebRover\Framework\Routing\Loader\Configurator;


use WebRover\Framework\Routing\RouteCollection;

/**
 * Class ImportConfigurator
 * @package WebRover\Framework\Routing\Loader\Configurator
 */
class ImportConfigurator
{
    use Traits\RouteTrait;

    private $parent;

    public function __construct(RouteCollection $parent, RouteCollection $route)
    {
        $this->parent = $parent;
        $this->route = $route;
    }

    public function __destruct()
    {
        $this->parent->addCollection($this->route);
    }

    /**
     * Sets the prefix to add to the path of all child routes.
     *
     * @param string $prefix
     *
     * @return $this
     */
    final public function prefix($prefix)
    {
        $this->route->addPrefix($prefix);

        return $this;
    }
}