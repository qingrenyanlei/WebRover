<?php


namespace WebRover\Framework\Routing\Loader;


use Symfony\Component\Routing\Loader\AnnotationClassLoader;
use Symfony\Component\Routing\Route;

/**
 * Class AnnotatedRouteControllerLoader
 * @package WebRover\Framework\Routing\Loader
 */
class AnnotatedRouteControllerLoader extends AnnotationClassLoader
{
    /**
     * Configures the _controller default parameter of a given Route instance.
     *
     * @param mixed $annot The annotation class instance
     */
    protected function configureRoute(Route $route, \ReflectionClass $class, \ReflectionMethod $method, $annot)
    {
        if ('__invoke' === $method->getName()) {
            $route->setDefault('_controller', $class->getName());
        } else {
            $route->setDefault('_controller', $class->getName() . '::' . $method->getName());
        }
    }

    /**
     * Makes the default route name more sane by removing common keywords.
     *
     * @return string
     */
    protected function getDefaultRouteName(\ReflectionClass $class, \ReflectionMethod $method)
    {
        $name =  preg_replace([
            '/(bundle|controller)_/',
            '/action(_\d+)?$/',
            '/__/',
        ], [
            '_',
            '\\1',
            '_',
        ], parent::getDefaultRouteName($class, $method));

        return explode('_',$name,2)[1];
    }
}