<?php


namespace WebRover\Framework\Kernel\Event;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Foundation\Response;
use WebRover\Framework\Kernel\HttpKernelInterface;

/**
 * Allows to filter a Response object.
 *
 * You can call getResponse() to retrieve the current response. With
 * setResponse() you can set a new response that will be returned to the
 * browser.
 *
 * Class FilterResponseEvent
 * @package WebRover\Framework\Kernel\Event
 */
class FilterResponseEvent extends KernelEvent
{
    private $response;

    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType, Response $response)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->setResponse($response);
    }

    /**
     * Returns the current response object.
     *
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Sets a new response object.
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}
