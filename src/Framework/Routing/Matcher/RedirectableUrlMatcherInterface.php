<?php


namespace WebRover\Framework\Routing\Matcher;


/**
 * RedirectableUrlMatcherInterface knows how to redirect the user.
 *
 * Interface RedirectableUrlMatcherInterface
 * @package WebRover\Framework\Routing\Matcher
 */
interface RedirectableUrlMatcherInterface
{
    /**
     * Redirects the user to another URL.
     *
     * @param string $path The path info to redirect to
     * @param string $route The route name that matched
     * @param string|null $scheme The URL scheme (null to keep the current one)
     *
     * @return array An array of parameters
     */
    public function redirect($path, $route, $scheme = null);
}