<?php


namespace WebRover\Framework\Kernel\Event;


use Symfony\Component\HttpFoundation\Request;
use WebRover\Framework\Kernel\HttpKernelInterface;

/**
 * Allows to create a response for the return value of a controller.
 *
 * Call setResponse() to set the response that will be returned for the
 * current request. The propagation of this event is stopped as soon as a
 * response is set.
 *
 * Class GetResponseForControllerResultEvent
 * @package WebRover\Framework\Kernel\Event
 */
class GetResponseForControllerResultEvent extends GetResponseEvent
{
    /**
     * The return value of the controller.
     *
     * @var mixed
     */
    private $controllerResult;

    public function __construct(HttpKernelInterface $kernel, Request $request, $requestType, $controllerResult)
    {
        parent::__construct($kernel, $request, $requestType);

        $this->controllerResult = $controllerResult;
    }

    /**
     * Returns the return value of the controller.
     *
     * @return mixed The controller return value
     */
    public function getControllerResult()
    {
        return $this->controllerResult;
    }

    /**
     * Assigns the return value of the controller.
     *
     * @param mixed $controllerResult The controller return value
     */
    public function setControllerResult($controllerResult)
    {
        $this->controllerResult = $controllerResult;
    }
}