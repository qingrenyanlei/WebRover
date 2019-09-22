<?php


namespace WebRover\Framework\Kernel\Controller;



use Symfony\Component\HttpFoundation\Request;

/**
 * A ControllerResolverInterface implementation knows how to determine the
 * controller to execute based on a Request object.
 *
 * It can also determine the arguments to pass to the Controller.
 *
 * A Controller can be any valid PHP callable.
 *
 * Interface ControllerResolverInterface
 * @package WebRover\Framework\Kernel\Controller
 */
interface ControllerResolverInterface
{
    /**
     * Returns the Controller instance associated with a Request.
     *
     * As several resolvers can exist for a single application, a resolver must
     * return false when it is not able to determine the controller.
     *
     * The resolver must only throw an exception when it should be able to load a
     * controller but cannot because of some errors made by the developer.
     *
     * @return callable|false A PHP callable representing the Controller,
     *                        or false if this resolver is not able to determine the controller
     *
     * @throws \LogicException If the controller can't be found
     */
    public function getController(Request $request);
}