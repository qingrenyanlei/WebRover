<?php


namespace WebRover\Framework\Kernel\Controller;


use WebRover\Framework\Foundation\Request;

/**
 * Class TraceableArgumentResolver
 * @package WebRover\Framework\Kernel\Controller
 */
class TraceableArgumentResolver implements ArgumentResolverInterface
{
    private $resolver;
    private $stopwatch;

    public function __construct(ArgumentResolverInterface $resolver, Stopwatch $stopwatch)
    {
        $this->resolver = $resolver;
        $this->stopwatch = $stopwatch;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments(Request $request, $controller)
    {
        $e = $this->stopwatch->start('controller.get_arguments');

        $ret = $this->resolver->getArguments($request, $controller);

        $e->stop();

        return $ret;
    }
}
