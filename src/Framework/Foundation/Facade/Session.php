<?php


namespace WebRover\Framework\Foundation\Facade;


use WebRover\Framework\Foundation\Session\Flash\FlashBag;
use WebRover\Framework\Foundation\Session\SessionBagInterface;
use WebRover\Framework\Foundation\Session\Storage\MetadataBag;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Session
 * @package WebRover\Framework\Foundation\Facade
 * @mixin \WebRover\Framework\Foundation\Session\Session
 * @method bool start() static
 * @method bool has($name) static
 * @method mixed get($name, $default = null) static
 * @method void set($name, $value) static
 * @method array all() static
 * @method void replace(array $attributes) static
 * @method mixed remove($name) static
 * @method void clear() static
 * @method bool isStarted() static
 * @method int count() static
 * @method int getUsageIndex() static
 * @method bool isEmpty() static
 * @method bool invalidate($lifetime = null) static
 * @method bool migrate($destroy = false, $lifetime = null) static
 * @method void save() static
 * @method string getId() static
 * @method void setId($id) static
 * @method mixed getName() static
 * @method void setName($name) static
 * @method MetadataBag getMetadataBag() static
 * @method SessionBagInterface getBag($name) static
 * @method FlashBag getFlashBag() static
 */
class Session extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'session';
    }
}