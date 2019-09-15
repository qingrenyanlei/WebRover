<?php


namespace WebRover\Framework\Routing\Matcher;


use WebRover\Framework\Routing\Exception\MethodNotAllowedException;
use WebRover\Framework\Routing\Exception\NoConfigurationException;
use WebRover\Framework\Routing\Exception\ResourceNotFoundException;
use WebRover\Framework\Routing\RequestContextAwareInterface;

/**
 * UrlMatcherInterface is the interface that all URL matcher classes must implement.
 *
 * Interface UrlMatcherInterface
 * @package WebRover\Framework\Routing\Matcher
 */
interface UrlMatcherInterface extends RequestContextAwareInterface
{
    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param string $pathinfo The path info to be parsed (raw format, i.e. not urldecoded)
     *
     * @return array An array of parameters
     *
     * @throws NoConfigurationException  If no routing configuration could be found
     * @throws ResourceNotFoundException If the resource could not be found
     * @throws MethodNotAllowedException If the resource was found but the request method is not allowed
     */
    public function match($pathinfo);
}