<?php


namespace WebRover\Framework\Kernel\Controller;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Kernel\ControllerMetadata\ArgumentMetadata;

/**
 * Responsible for resolving the value of an argument based on its metadata.
 *
 * Interface ArgumentValueResolverInterface
 * @package WebRover\Framework\Kernel\Controller
 */
interface ArgumentValueResolverInterface
{
    /**
     * Whether this resolver can resolve the value for the given ArgumentMetadata.
     *
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument);

    /**
     * Returns the possible value(s).
     *
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument);
}