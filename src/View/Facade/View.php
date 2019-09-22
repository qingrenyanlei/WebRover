<?php


namespace WebRover\Framework\View\Facade;


use Symfony\Component\Templating\EngineInterface;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class View
 * @package WebRover\Framework\View\Facade
 * @mixin \WebRover\Framework\View\View
 * @method string render($name, array $parameters = []) static
 * @method void stream($name, array $parameters = []) static
 * @method bool exists($name) static
 * @method EngineInterface getEngine($name) static
 */
class View extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'view';
    }
}