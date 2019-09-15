<?php


namespace WebRover\Framework\Routing\Loader;


use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use WebRover\Framework\Routing\Loader\Configurator\RoutingConfigurator;
use WebRover\Framework\Routing\RouteCollection;

/**
 * PhpFileLoader loads routes from a PHP file.
 *
 * The file must return a RouteCollection instance.
 *
 * Class PhpFileLoader
 * @package WebRover\Framework\Routing\Loader
 */
class PhpFileLoader1 extends FileLoader
{
    /**
     * Loads a PHP file.
     *
     * @param string $file A PHP file path
     * @param string|null $type The resource type
     *
     * @return RouteCollection A RouteCollection instance
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);
        $this->setCurrentDir(\dirname($path));

        // the closure forbids access to the private scope in the included file
        $loader = $this;
        $load = \Closure::bind(static function ($file) use ($loader) {
            return include $file;
        }, null, ProtectedPhpFileLoader::class);

        $result = $load($path);

        if ($result instanceof \Closure) {
            $collection = new RouteCollection();
            $result(new RoutingConfigurator($collection, $this, $path, $file));
        } else {
            $collection = $result;
        }

        var_dump($path);
        exit();

        $collection->addResource(new FileResource($path));

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return \is_string($resource) && 'php' === pathinfo($resource, PATHINFO_EXTENSION) && (!$type || 'php' === $type);
    }
}

/**
 * @internal
 */
final class ProtectedPhpFileLoader extends PhpFileLoader1
{
}