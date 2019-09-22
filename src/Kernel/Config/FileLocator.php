<?php


namespace WebRover\Framework\Kernel\Config;


use Symfony\Component\Config\FileLocator as BaseFileLocator;
use WebRover\Framework\Kernel\KernelInterface;

/**
 * Class FileLocator
 * @package WebRover\Framework\Kernel\Config
 */
class FileLocator extends BaseFileLocator
{
    private $kernel;
    private $path;

    /**
     * @param KernelInterface $kernel A KernelInterface instance
     * @param string|null $path The path the global resource directory
     * @param array $paths An array of paths where to look for resources
     */
    public function __construct(KernelInterface $kernel, $path = null, array $paths = [])
    {
        $this->kernel = $kernel;
        if (null !== $path) {
            $this->path = $path;
            $paths[] = $path;
        }

        parent::__construct($paths);
    }

    public function locate($file, $currentPath = null, $first = true)
    {
        if (isset($file[0]) && '@' === $file[0]) {
            return $this->doLocateResource($file, $this->path, $first);
        }

        return parent::locate($file, $currentPath, $first);
    }

    private function doLocateResource($name, $dir = null, $first = true)
    {
        if ('@' !== $name[0]) {
            throw new \InvalidArgumentException(sprintf('A resource name must start with @ ("%s" given).', $name));
        }

        if (false !== strpos($name, '..')) {
            throw new \RuntimeException(sprintf('File name "%s" contains invalid characters (..).', $name));
        }

        $bundleName = substr($name, 1);
        $path = '';
        if (false !== strpos($bundleName, '/')) {
            list($bundleName, $path) = explode('/', $bundleName, 2);
        }

        $isResource = 0 === strpos($path, 'resource') && null !== $dir;
        $overridePath = substr($path, 9);
        $resourceBundle = null;

        $bundle = $this->kernel->getBundle($bundleName);

        $files = [];
        if ($isResource && file_exists($file = $dir . '/' . $bundle->getName() . $overridePath)) {
            if (null !== $resourceBundle) {
                throw new \RuntimeException(sprintf('"%s" resource is hidden by a resource from the "%s" derived bundle. Create a "%s" file to override the bundle resource.', $file, $resourceBundle, $dir . '/' . $bundles[0]->getName() . $overridePath));
            }

            if ($first) {
                return $file;
            }
            $files[] = $file;
        }

        if (file_exists($file = $bundle->getPath() . '/' . $path)) {
            if ($first && !$isResource) {
                return $file;
            }
            $files[] = $file;
        }

        if (\count($files) > 0) {
            return $first && $isResource ? $files[0] : $files;
        }

        throw new \InvalidArgumentException(sprintf('Unable to find file "%s".', $name));
    }
}