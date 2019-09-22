<?php


namespace WebRover\Framework\Container;


use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Class ContainerParametersResource
 * @package WebRover\Framework\Container
 */
class ContainerParametersResource implements ResourceInterface, \Serializable
{
    private $parameters;

    /**
     * @param array $parameters The container parameters to track
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return 'container_parameters_' . md5(serialize($this->parameters));
    }

    /**
     * @internal
     */
    public function serialize()
    {
        return serialize($this->parameters);
    }

    /**
     * @internal
     */
    public function unserialize($serialized)
    {
        $this->parameters = unserialize($serialized);
    }

    /**
     * @return array Tracked parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}