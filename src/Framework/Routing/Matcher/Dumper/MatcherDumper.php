<?php


namespace WebRover\Framework\Routing\Matcher\Dumper;


use WebRover\Framework\Routing\RouteCollection;

/**
 * MatcherDumper is the abstract class for all built-in matcher dumpers.
 *
 * Class MatcherDumper
 * @package WebRover\Framework\Routing\Matcher\Dumper
 */
abstract class MatcherDumper implements MatcherDumperInterface
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