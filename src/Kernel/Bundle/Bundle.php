<?php


namespace WebRover\Framework\Kernel\Bundle;


/**
 * Class Bundle
 * @package WebRover\Framework\Kernel\Bundle
 */
abstract class Bundle implements BundleInterface
{
    protected $name;

    protected $namespace;

    protected $path;

    protected $configPath;

    protected $resourcePath;

    final public function getNamespace()
    {
        if (null === $this->namespace) {
            $this->parseClassName();
        }

        return $this->namespace;
    }

    public function getConfigPath()
    {
        if (null === $this->configPath) {
            $this->configPath = $this->getPath() . DIRECTORY_SEPARATOR . 'config';
        }

        return $this->configPath;
    }

    public function getResourcePath()
    {
        if (null === $this->resourcePath) {
            $this->resourcePath = $this->getPath() . DIRECTORY_SEPARATOR . 'resource';
        }

        return $this->resourcePath;
    }

    final public function getPath()
    {
        if (null === $this->path) {
            $reflected = new \ReflectionObject($this);
            $this->path = \dirname($reflected->getFileName());
        }

        return $this->path;
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

    public function registerProviders()
    {
    }

    public function boot()
    {
    }

    public function shutdown()
    {
    }
}