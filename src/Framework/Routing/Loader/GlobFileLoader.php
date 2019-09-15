<?php


namespace WebRover\Framework\Routing\Loader;


use Symfony\Component\Config\Loader\FileLoader;
use WebRover\Framework\Routing\RouteCollection;

/**
 * GlobFileLoader loads files from a glob pattern.
 *
 * Class GlobFileLoader
 * @package WebRover\Framework\Routing\Loader
 */
class GlobFileLoader extends FileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        foreach ($this->glob($resource, false, $globResource) as $path => $info) {
            $collection->addCollection($this->import($path));
        }

        $collection->addResource($globResource);

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'glob' === $type;
    }
}