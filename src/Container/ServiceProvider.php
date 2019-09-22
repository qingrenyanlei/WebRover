<?php


namespace WebRover\Framework\Container;


use WebRover\Framework\Kernel\Application;

/**
 * Class ServiceProvider
 * @package WebRover\Framework\Container
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @var Application
     */
    protected $app;

    protected $namespace;

    protected $name;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function register()
    {
    }

    public function boot()
    {
    }

    final public function getNamespace()
    {
        if (null === $this->namespace) {
            $this->parseClassName();
        }

        return $this->namespace;
    }

    final public function getName()
    {
        if (null === $this->name) {
            $this->parseClassName();
        }

        return $this->name;
    }

    private function parseClassName()
    {
        $pos = strrpos(static::class, '\\');
        $this->namespace = false === $pos ? '' : substr(static::class, 0, $pos);
        if (null === $this->name) {
            $this->name = false === $pos ? static::class : substr(static::class, $pos + 1);
        }
    }
}