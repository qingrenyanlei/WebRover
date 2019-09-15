<?php


namespace WebRover\Framework\Routing\Loader;


use Symfony\Component\Config\Loader\Loader;
use WebRover\Framework\Routing\Route;
use WebRover\Framework\Routing\RouteCollection;
use WebRover\Framework\Support\Str;

/**
 * Class RestfulLoader
 * @package WebRover\Framework\Routing\Loader
 */
class RestfulLoader extends Loader
{
    public function load($resource, $type = null)
    {
        list($module, $controller) = explode(':', $resource, 2);
        $controller = str_replace('/', '\\', $controller);

        $namePrefix = Str::snake(strtolower($module) . str_replace('\\', '', $controller)) . '_';

        $_controllerPrefix = $module . ':' . $controller . ':';
        $actions = [
            'index' => [
                'path' => '/',
                'methods' => ['GET'],
                'defaults' => []
            ],
            'create' => [
                'path' => '/create',
                'methods' => ['GET'],
            ],
            'store' => [
                'path' => '/',
                'methods' => ['POST']
            ],
            'edit' => [
                'path' => '/edit/{id}',
                '_methods' => ['GET']
            ],
            'update' => [
                'path' => '/{id}',
                'methods' => ['PUT', 'PATCH']
            ],
            'delete' => [
                'path' => '/{id}',
                'methods' => ['DELETE']
            ]
        ];

        $collection = new RouteCollection();
        foreach ($actions as $action => $config) {
            $name = $namePrefix . $action;
            $route = new Route($config['path'], [
                '_controller' => $_controllerPrefix . $action
            ], [], [], '', [], $config['methods']);
            $collection->add($name, $route);
        }

        $controllerSplit = array_reverse(explode('\\', $controller));

        $prefix = $controllerSplit[0];

        if (strpos($prefix, 'Controller')) {
            $prefix = substr($prefix, 0, -10);
        }

        $collection->addPrefix('/' . Str::snake(lcfirst($prefix), '-'));

        $collection->addCollection($collection);
        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return $type == 'restful';
    }
}