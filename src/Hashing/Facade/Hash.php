<?php


namespace WebRover\Framework\Hashing\Facade;


use WebRover\Framework\Hashing\BcryptHasher;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Hash
 * @package WebRover\Framework\Hashing\Facade
 * @mixin BcryptHasher
 * @method string make($value, array $options = []) static
 * @method bool check($value, $hashedValue, array $options = []) static
 * @method bool needsRehash($hashedValue, array $options = []) static
 */
class Hash extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'hash';
    }
}