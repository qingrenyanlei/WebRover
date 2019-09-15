<?php


namespace WebRover\Framework\Routing\Facade;


use WebRover\Framework\Foundation\Request;
use WebRover\Framework\Kernel\Facade\AbstractFacade;
use WebRover\Framework\Routing\Generator\UrlGeneratorInterface;
use WebRover\Framework\Routing\Matcher\UrlMatcherInterface;
use WebRover\Framework\Routing\RequestContext;
use WebRover\Framework\Routing\RouteCollection;

/**
 * Class Router
 * @package WebRover\Framework\Routing\Facade
 * @mixin \WebRover\Framework\Routing\Router
 * @method mixed getOption($key) static
 * @method RouteCollection getRouteCollection() static
 * @method RequestContext getContext() static
 * @method string generate($name, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH) static
 * @method array match($pathinfo) static
 * @method array matchRequest(Request $request) static
 * @method UrlMatcherInterface getMatcher() static
 * @method UrlGeneratorInterface getGenerator() static
 */
class Router extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'router';
    }
}