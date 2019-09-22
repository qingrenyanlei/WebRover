<?php


namespace WebRover\Framework\Kernel\Controller;


use Symfony\Component\HttpFoundation\Request;

/**
 * Class TraceableControllerResolver
 * @package WebRover\Framework\Kernel\Controller
 */
class TraceableControllerResolver implements ControllerResolverInterface, ArgumentResolverInterface
{
    private $resolver;
    private $stopwatch;
    private $argumentResolver;

    public function __construct(ControllerResolverInterface $resolver, Stopwatch $stopwatch, ArgumentResolverInterface $argumentResolver = null)
    {
        $this->resolver = $resolver;
        $this->stopwatch = $stopwatch;
        $this->argumentResolver = $argumentResolver;

        // BC
        if (null === $this->argumentResolver) {
            $this->argumentResolver = $resolver;
        }

        if (!$this->argumentResolver instanceof TraceableArgumentResolver) {
            $this->argumentResolver = new TraceableArgumentResolver($this->argumentResolver, $this->stopwatch);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getController(Request $request)
    {
        $e = $this->stopwatch->start('controller.get_callable');

        $ret = $this->resolver->getController($request);

        $e->stop();

        return $ret;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated This method is deprecated as of 3.1 and will be removed in 4.0.
     */
    public function getArguments(Request $request, $controller)
    {
        @trigger_error(sprintf('The "%s()" method is deprecated as of 3.1 and will be removed in 4.0. Please use the %s instead.', __METHOD__, TraceableArgumentResolver::class), E_USER_DEPRECATED);

        $ret = $this->argumentResolver->getArguments($request, $controller);

        return $ret;
    }
}
