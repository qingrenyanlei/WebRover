<?php


namespace WebRover\Framework\Kernel\Event;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WebRover\Framework\Kernel\HttpKernelInterface;

/**
 * Allows to execute logic after a response was sent.
 *
 * Since it's only triggered on master requests, the `getRequestType()` method
 * will always return the value of `HttpKernelInterface::MASTER_REQUEST`.
 *
 * Class PostResponseEvent
 * @package WebRover\Framework\Kernel\Event
 */
class PostResponseEvent extends KernelEvent
{
    private $response;

    public function __construct(HttpKernelInterface $kernel, Request $request, Response $response)
    {
        parent::__construct($kernel, $request, HttpKernelInterface::MASTER_REQUEST);

        $this->response = $response;
    }

    /**
     * Returns the response for which this event was thrown.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
