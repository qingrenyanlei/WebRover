<?php


namespace WebRover\Framework\Routing;


use WebRover\Framework\Routing\Generator\UrlGeneratorInterface;
use WebRover\Framework\Routing\Matcher\UrlMatcherInterface;

/**
 * RouterInterface is the interface that all Router classes must implement.
 *
 * This interface is the concatenation of UrlMatcherInterface and UrlGeneratorInterface.
 *
 * Interface RouterInterface
 * @package WebRover\Framework\Routing
 */
interface RouterInterface extends UrlMatcherInterface, UrlGeneratorInterface
{
    /**
     * Gets the RouteCollection instance associated with this Router.
     *
     * WARNING: This method should never be used at runtime as it is SLOW.
     *          You might use it in a cache warmer though.
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function getRouteCollection();
}