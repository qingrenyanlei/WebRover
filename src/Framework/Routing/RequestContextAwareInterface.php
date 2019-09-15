<?php


namespace WebRover\Framework\Routing;


/**
 * Interface RequestContextAwareInterface
 * @package WebRover\Framework\Routing
 */
interface RequestContextAwareInterface
{
    /**
     * Sets the request context.
     */
    public function setContext(RequestContext $context);

    /**
     * Gets the request context.
     *
     * @return RequestContext The context
     */
    public function getContext();
}