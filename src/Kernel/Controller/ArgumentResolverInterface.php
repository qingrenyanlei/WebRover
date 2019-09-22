<?php


namespace WebRover\Framework\Kernel\Controller;



use Symfony\Component\HttpFoundation\Request;

/**
 * An ArgumentResolverInterface instance knows how to determine the
 * arguments for a specific action.
 *
 * Interface ArgumentResolverInterface
 * @package WebRover\Framework\Kernel\Controller
 */
interface ArgumentResolverInterface
{
    /**
     * Returns the arguments to pass to the controller.
     *
     * @param callable $controller
     *
     * @return array An array of arguments to pass to the controller
     *
     * @throws \RuntimeException When no value could be provided for a required argument
     */
    public function getArguments(Request $request, $controller);
}