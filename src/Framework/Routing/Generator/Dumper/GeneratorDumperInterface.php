<?php


namespace WebRover\Framework\Routing\Generator\Dumper;


use WebRover\Framework\Routing\RouteCollection;

/**
 * GeneratorDumperInterface is the interface that all generator dumper classes must implement.
 *
 * Interface GeneratorDumperInterface
 * @package WebRover\Framework\Routing\Generator\Dumper
 */
interface GeneratorDumperInterface
{
    /**
     * Dumps a set of routes to a string representation of executable code
     * that can then be used to generate a URL of such a route.
     *
     * @param array $options An array of options
     *
     * @return string Executable code
     */
    public function dump(array $options = []);

    /**
     * Gets the routes to dump.
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function getRoutes();
}