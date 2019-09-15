<?php


namespace WebRover\Framework\Routing\Matcher;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Routing\Exception\MethodNotAllowedException;
use WebRover\Framework\Routing\Exception\NoConfigurationException;
use WebRover\Framework\Routing\Exception\ResourceNotFoundException;

/**
 * RequestMatcherInterface is the interface that all request matcher classes must implement.
 *
 * Interface RequestMatcherInterface
 * @package WebRover\Framework\Routing\Matcher
 */
interface RequestMatcherInterface
{
    /**
     * Tries to match a request with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @return array An array of parameters
     *
     * @throws NoConfigurationException  If no routing configuration could be found
     * @throws ResourceNotFoundException If no matching resource could be found
     * @throws MethodNotAllowedException If a matching resource was found but the request method is not allowed
     */
    public function matchRequest(Request $request);
}