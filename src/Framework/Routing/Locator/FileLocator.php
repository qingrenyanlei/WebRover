<?php


namespace WebRover\Framework\Routing\Locator;


class FileLocator extends \Symfony\Component\Config\FileLocator
{
    private $modules = [];

    private $path;

    public function __construct(array $modules, $path = null, array $paths = [])
    {
        $this->modules = $modules;
        if (null !== $path) {
            $this->path = $path;
            $paths[] = $path;
        }

        parent::__construct($paths);
    }

    /**
     * {@inheritdoc}
     */
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

        $moduleName = substr($name, 1);
        $path = '';
        if (false !== strpos($moduleName, '/')) {
            list($moduleName, $path) = explode('/', $moduleName, 2);
        }

        $isResource = 0 === strpos($path, 'Resources') && null !== $dir;
        $overridePath = substr($path, 9);
        $resourceBundle = null;

        $module = $this->modules[$moduleName];

        $files = [];
        if ($isResource && file_exists($file = $dir . '/' . $module->getName() . $overridePath)) {
            if (null !== $resourceBundle) {
                throw new \RuntimeException(sprintf('"%s" resource is hidden by a resource from the "%s" derived bundle. Create a "%s" file to override the bundle resource.', $file, $resourceBundle, $dir . '/' . $bundles[0]->getName() . $overridePath));
            }

            if ($first) {
                return $file;
            }
            $files[] = $file;
        }

        if (file_exists($file = $module->getPath() . '/' . $path)) {
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