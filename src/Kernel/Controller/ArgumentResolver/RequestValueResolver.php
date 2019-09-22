<?php


namespace WebRover\Framework\Kernel\Controller\ArgumentResolver;


use Symfony\Component\HttpFoundation\Request;
use WebRover\Framework\Kernel\Controller\ArgumentValueResolverInterface;
use WebRover\Framework\Kernel\ControllerMetadata\ArgumentMetadata;

/**
 * Yields the same instance as the request object passed along.
 *
 * Class RequestValueResolver
 * @package WebRover\Framework\Kernel\Controller\ArgumentResolver
 */
final class RequestValueResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return Request::class === $argument->getType() || is_subclass_of($argument->getType(), Request::class);
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $request;
    }
}
