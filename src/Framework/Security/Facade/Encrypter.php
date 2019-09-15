<?php


namespace WebRover\Framework\Security\Facade;


use WebRover\Framework\Kernel\Facade\AbstractFacade;
use WebRover\Framework\Security\Encryption;

/**
 * Class Encrypter
 * @package WebRover\Framework\Security\Facade
 * @mixin Encryption
 * @method string encrypt($value, $serialize = true) static
 * @method string encryptString($value) static
 * @method mixed decrypt($payload, $unserialize = true) static
 * @method mixed decryptString($payload) static
 */
class Encrypter extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'encrypter';
    }
}