<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

/**
 * Class FileStore
 * @package WebRover\Framework\Cache\Store
 */
class FileStore extends AbstractStore
{
    public function connect(array $params)
    {
        $path = isset($params['path']) ? str_replace('/', DIRECTORY_SEPARATOR, $params['path']) : null;

        $adapter = new FilesystemAdapter('', 0, $path);

        $this->instance = $adapter;

        return $this;
    }
}