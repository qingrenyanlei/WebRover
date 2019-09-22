<?php


namespace WebRover\Framework\Routing\Loader;


use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use WebRover\Framework\Support\Str;

/**
 * Class RestfulLoader
 * @package WebRover\Framework\Routing\Loader
 */
class RestfulLoader extends Loader
{
    public function load($resource, $type = null)
    {
        if (!isset($resource[0]) || $resource[0] != '@') {
            throw new \InvalidArgumentException(sprintf('The resource "%s" must start with @', $resource));
        }
        $resource = substr($resource, 1);

        list($module, $controller) = explode(':', $resource, 2);
        $controller = str_replace('/', '\\', $controller);

        $namePrefix = Str::snake(strtolower($module) . str_replace('\\', '', $controller)) . '_';

        $_controllerPrefix = $module . ':' . $controller . ':';

        $controllerSplit = array_reverse(explode('\\', $controller));
        $pathPrefix = $controllerSplit[0];
        if (strpos($pathPrefix, 'Controller')) {
            $pathPrefix = substr($pathPrefix, 0, -10);
        }

        $pathPrefix = '/' . Str::snake(lcfirst($pathPrefix), '-');

        $actions = [
            'index' => [
                'path' => $pathPrefix,
                'methods' => ['GET'],
            ],
            'new' => [
                'path' => $pathPrefix . '/new',
                'methods' => ['GET'],
            ],
            'store' => [
                'path' => $pathPrefix,
                'methods' => ['POST']
            ],
            'edit' => [
                'path' => $pathPrefix . '/edit/{id}',
                'methods' => ['GET']
            ],
            'update' => [
                'path' => $pathPrefix . '{id}',
                'methods' => ['PUT', 'PATCH']
            ],
            'delete' => [
                'path' => $pathPrefix . '{id}',
                'methods' => ['DELETE']
            ]
        ];

        $collection = new RouteCollection();
        foreach ($actions as $action => $config) {
            $name = $this->getName($namePrefix . $action);

            $route = new Route($config['path'], [
                '_controller' => $_controllerPrefix . $action
            ], [], [], '', [], $config['methods']);

            $collection->add($name, $route);
        }

        $collection->addCollection($collection);
        return $collection;
    }

    private function getName($name)
    {
        return preg_replace([
            '/(bundle|controller)_/',
            '/action(_\d+)?$/',
            '/__/',
        ], [
            '_',
            '\\1',
            '_',
        ], strtolower($name));
    }

    public function supports($resource, $type = null)
    {
        return $type == 'restful';
    }
}