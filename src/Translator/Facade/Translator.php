<?php


namespace WebRover\Framework\Translator\Facade;


use Symfony\Component\Translation\MessageCatalogueInterface;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Translator
 * @package WebRover\Framework\Translator\Facade
 * @mixin \WebRover\Framework\Translator\Translator
 * @method string trans($id, array $parameters = [], $domain = null, $locale = null) static
 * @method string transChoice($id, $number, array $parameters = [], $domain = null, $locale = null) static
 * @method MessageCatalogueInterface getCatalogue($locale = null) static
 * @method array getFallbackLocales() static
 * @method string getLocale() static
 * @method void setLocale($locale) static
 */
class Translator extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'translator';
    }
}