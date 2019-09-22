<?php


namespace WebRover\Framework\Kernel\Controller\ArgumentResolver;


use Symfony\Component\HttpFoundation\Request;
use WebRover\Framework\Kernel\Controller\ArgumentValueResolverInterface;
use WebRover\Framework\Kernel\ControllerMetadata\ArgumentMetadata;

/**
 * Yields a variadic argument's values from the request attributes.
 *
 * Class VariadicValueResolver
 * @package WebRover\Framework\Kernel\Controller\ArgumentResolver
 */
final class VariadicValueResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->isVariadic() && $request->attributes->has($argument->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $values = $request->attributes->get($argument->getName());

        if (!\is_array($values)) {
            throw new \InvalidArgumentException(sprintf('The action argument "...$%1$s" is required to be an array, the request attribute "%1$s" contains a type of "%2$s" instead.', $argument->getName(), \gettype($values)));
        }

        foreach ($values as $value) {
            yield $value;
        }
    }
}