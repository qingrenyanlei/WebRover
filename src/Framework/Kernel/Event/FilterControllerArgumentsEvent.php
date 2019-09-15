<?php


namespace WebRover\Framework\Kernel\Event;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Kernel\HttpKernelInterface;

/**
 * Allows filtering of controller arguments.
 *
 * You can call getController() to retrieve the controller and getArguments
 * to retrieve the current arguments. With setArguments() you can replace
 * arguments that are used to call the controller.
 *
 * Arguments set in the event must be compatible with the signature of the
 * controller.
 *
 * Class FilterControllerArgumentsEvent
 * @package WebRover\Framework\Kernel\Event
 */
class FilterControllerArgumentsEvent extends FilterControllerEvent
{
    private $arguments;

    public function __construct(HttpKernelInterface $kernel, callable $controller, array $arguments, Request $request, $requestType)
    {
        parent::__construct($kernel, $controller, $request, $requestType);

        $this->arguments = $arguments;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }
}
