<?php


namespace WebRover\Framework\Kernel\Controller\ArgumentResolver;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Kernel\Controller\ArgumentValueResolverInterface;
use WebRover\Framework\Kernel\ControllerMetadata\ArgumentMetadata;

/**
 * Yields a non-variadic argument's value from the request attributes.
 *
 * Class RequestAttributeValueResolver
 * @package WebRover\Framework\Kernel\Controller\ArgumentResolver
 */
final class RequestAttributeValueResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return !$argument->isVariadic() && $request->attributes->has($argument->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $request->attributes->get($argument->getName());
    }
}