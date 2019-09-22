<?php


namespace WebRover\Framework\Encryption\Facade;


use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Encrypter
 * @package WebRover\Framework\Encryption\Facade
 * @mixin \WebRover\Framework\Encryption\Encrypter
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