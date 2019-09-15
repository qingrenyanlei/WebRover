<?php


namespace WebRover\Framework\View\Facade;


use Twig\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\ExtensionInterface;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class View
 * @package WebRover\Framework\View\Facade
 * @mixin Environment
 * @method string getCacheFilename($name) static
 * @method CacheInterface|string|false getCache($original = true) static
 * @method string render($name, array $context = []) static
 * @method void display($name, array $context = []) static
 * @method bool isTemplateFresh($name, $time) static
 * @method bool hasExtension($class) static
 * @method string getExtension($class) static
 * @method ExtensionInterface getExtensions() static
 */
class View extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'view';
    }
}