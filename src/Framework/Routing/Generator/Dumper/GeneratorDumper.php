<?php


namespace WebRover\Framework\Routing\Generator\Dumper;


use WebRover\Framework\Routing\RouteCollection;

/**
 * GeneratorDumper is the base class for all built-in generator dumpers.
 *
 * Class GeneratorDumper
 * @package WebRover\Framework\Routing\Generator\Dumper
 */
abstract class GeneratorDumper implements GeneratorDumperInterface
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}