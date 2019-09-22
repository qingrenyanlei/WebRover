<?php


namespace WebRover\Framework\Asset\Facade;


use Symfony\Component\Asset\Packages;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Asset
 * @package WebRover\Framework\Asset\Facade
 * @mixin Packages
 * @method string getVersion($path, $packageName = null) static
 * @method string getUrl($path, $packageName = null) static
 */
class Asset extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'asset';
    }
}