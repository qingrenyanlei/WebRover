<?php


namespace WebRover\Framework\Routing;


/**
 * RouteCompilerInterface is the interface that all RouteCompiler classes must implement.
 *
 * Interface RouteCompilerInterface
 * @package WebRover\Framework\Routing
 */
interface RouteCompilerInterface
{
    /**
     * Compiles the current route instance.
     *
     * @return CompiledRoute A CompiledRoute instance
     *
     * @throws \LogicException If the Route cannot be compiled because the
     *                         path or host pattern is invalid
     */
    public static function compile(Route $route);
}