<?php


namespace WebRover\Framework\Routing\Facade;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Router
 * @package WebRover\Framework\Routing\Facade
 * @mixin \Symfony\Component\Routing\Router
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