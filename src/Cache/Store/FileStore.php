<?php


namespace WebRover\Framework\Cache\Store;


use Symfony\Component\Cache\Simple\FilesystemCache;

/**
 * Class FileStore
 * @package WebRover\Framework\Cache\Store
 */
class FileStore extends AbstractStore
{
    public function connect(array $params = [])
    {
        $path = isset($params['path']) ? $params['path'] : '';

        $this->instance = new FilesystemCache('', 0, $path);
    }
}