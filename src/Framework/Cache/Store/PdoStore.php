<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Adapter\PdoAdapter;

/**
 * Class PdoStore
 * @package WebRover\Framework\Cache\Store
 */
class PdoStore extends AbstractStore
{
    public function connect(array $params)
    {
        $connection = $params['connection'];

        $options = isset($params['options']) ? $params['options'] : [];

        $this->instance = new PdoAdapter($connection, '', 0, $options);

        return $this;
    }
}