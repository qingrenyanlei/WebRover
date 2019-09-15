<?php


namespace WebRover\Framework\Cache\Facade;


use WebRover\Framework\Cache\Builder\TagBuilder;
use WebRover\Framework\Cache\Tag;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Cache
 * @package WebRover\Framework\Cache\Facade
 * @mixin \WebRover\Framework\Cache\Cache
 * @method TagBuilder store($name = null) static
 * @method Tag tag($tags) static
 * @method bool has($key) static
 * @method bool set($key, $value, $ttl = 0) static
 * @method mixed get($key, $default = null) static
 * @method bool delete($key) static
 * @method bool flush() static
 */
class Cache extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'cache';
    }
}