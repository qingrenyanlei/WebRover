<?php


namespace WebRover\Framework\Kernel\Controller\ArgumentResolver;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WebRover\Framework\Kernel\Controller\ArgumentValueResolverInterface;
use WebRover\Framework\Kernel\ControllerMetadata\ArgumentMetadata;

/**
 * Yields the Session.
 *
 * Class SessionValueResolver
 * @package WebRover\Framework\Kernel\Controller\ArgumentResolver
 */
final class SessionValueResolver implements ArgumentValueResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $type = $argument->getType();
        if (SessionInterface::class !== $type && !is_subclass_of($type, SessionInterface::class)) {
            return false;
        }

        return $request->getSession() instanceof $type;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        yield $request->getSession();
    }
}