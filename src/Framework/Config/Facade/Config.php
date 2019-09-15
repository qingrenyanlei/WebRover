<?php


namespace WebRover\Framework\Config\Facade;


use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Config
 * @package WebRover\Framework\Config\Facade
 * @mixin \WebRover\Framework\Config\Config
 * @method void set(array $config) static
 * @method mixed get($config, $default = null) static
 * @method bool has($config) static
 */
class Config extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'config';
    }
}