<?php


namespace WebRover\Framework\View;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use WebRover\Framework\Kernel\Application;

class GlobalVariables
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return Request|null
     */
    public function getRequest()
    {
        if ($this->app->has('request_stack')) {
            return $this->app->make('request_stack')->getCurrentRequest();
        }
    }

    /**
     * @return SessionInterface|null
     */
    public function getSession()
    {
        if ($request = $this->getRequest()) {
            return $request->getSession();
        }
    }

    public function getEnvironment()
    {
        return $this->app->getEnvironment();
    }

    public function isDebug()
    {
        return $this->app->isDebug();
    }
}