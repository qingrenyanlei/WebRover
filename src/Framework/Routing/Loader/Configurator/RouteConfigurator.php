<?php


namespace WebRover\Framework\Routing\Loader\Configurator;


use WebRover\Framework\Routing\Route;
use WebRover\Framework\Routing\RouteCollection;

/**
 * Class RouteConfigurator
 * @package WebRover\Framework\Routing\Loader\Configurator
 */
class RouteConfigurator
{
    use Traits\AddTrait;
    use Traits\RouteTrait;

    private $parentConfigurator;

    public function __construct(RouteCollection $collection, Route $route, $name = '', CollectionConfigurator $parentConfigurator = null)
    {
        $this->collection = $collection;
        $this->route = $route;
        $this->name = $name;
        $this->parentConfigurator = $parentConfigurator; // for GC control
    }
}