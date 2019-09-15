<?php


namespace WebRover\Framework\Kernel\Controller\ArgumentResolver;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Kernel\Controller\ArgumentValueResolverInterface;
use WebRover\Framework\Kernel\ControllerMetadata\ArgumentMetadata;

/**
 * Yields the default value defined in the action signature when no value has been given.
 *
 * Class DefaultValueResolver
 * @package WebRover\Framework\Kernel\Controller\ArgumentResolver
 */
final class DefaultValueResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->hasDefaultValue() || (null !== $argument->getType() && $argument->isNullable() && !$argument->isVariadic());
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $argument->hasDefaultValue() ? $argument->getDefaultValue() : null;
    }
}
