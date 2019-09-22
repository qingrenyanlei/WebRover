<?php


namespace WebRover\Framework\Kernel\EventListener;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Sets the session in the request.
 *
 * Class SessionListener
 * @package WebRover\Framework\Kernel\EventListener
 */
class SessionListener extends AbstractSessionListener
{
    private $session;

    public function __construct(SessionInterface $session = null)
    {
        $this->session = $session;
    }

    protected function getSession()
    {
        return $this->session;
    }
}