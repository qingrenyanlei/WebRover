<?php


namespace WebRover\Framework\Kernel\EventListener;


use Psr\Container\ContainerInterface;

/**
 * Sets the session in the request.
 *
 * Class SessionListener
 * @package WebRover\Framework\Kernel\EventListener
 */
class SessionListener extends AbstractSessionListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    protected function getSession()
    {
        if (!$this->container->has('session')) {
            return null;
        }

        return $this->container->get('session');
    }
}