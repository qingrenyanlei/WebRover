<?php


namespace WebRover\Framework\Config;


use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use WebRover\Framework\Kernel\Bundle\BundleInterface;
use WebRover\Framework\Support\Arr;

/**
 * Class Config
 * @package WebRover\Framework\Config
 */
class Config
{
    private $rootPath;

    /**
     * @var BundleInterface[]
     */
    private $bundles = [];

    private $bags = [];

    public function __construct($rootPath, array $bundles = [])
    {
        $this->rootPath = $rootPath;
        $this->bundles = $bundles;
    }

    public function set(array $config)
    {
        foreach ($config as $file => $value) {

            list($filePath, $key) = $this->parseConfig($file);

            $identifier = md5($filePath);

            $instance = $this->instance($identifier, $filePath);

            $all = $instance->all();

            Arr::set($all, $key, $value);

            $instance->replace($all);

            $this->bags[$identifier] = $instance;
        }
    }

    public function get($config, $default = null)
    {
        list($all, $key) = $this->parse($config);

        return $key ? Arr::get($all, $key, $default) : $default;
    }

    public function has($config)
    {
        list($all, $key) = $this->parse($config);

        return $key ? Arr::has($all, $key) : true;
    }


    private function parse($config)
    {
        list($filePath, $key) = $this->parseConfig($config);

        $identifier = md5($filePath);

        $instance = $this->instance($identifier, $filePath);

        return [$instance->all(), $key];
    }

    private function instance($identifier, $filePath)
    {
        if (!isset($this->bags[$identifier])) {
            $file = include $filePath;

            if (!is_array($file)) {
                throw new \InvalidArgumentException(sprintf('Invalid parameters in file "%s"', $filePath));
            }

            $this->bags[$identifier] = new ParameterBag($file);
        }

        return $this->bags[$identifier];
    }

    private function parseConfig($config)
    {
        if (!strpos($config, '.')) {
            throw new \InvalidArgumentException(sprintf('Unavailable parameter "%s"', $config));
        }

        list($fileName, $key) = explode('.', $config, 2);

        $filePath = $this->rootPath . DIRECTORY_SEPARATOR . $fileName;

        if (0 === strpos($fileName, '@')) {
            list($bundleName, $fileName) = explode('/', substr($fileName, 1));
            if (!isset($this->bundles[$bundleName])) {
                throw new \InvalidArgumentException(sprintf('The "%s" bundle does not exist', $bundleName));
            }

            $bundle = $this->bundles[$bundleName];

            $filePath = $bundle->getConfigPath() . $fileName;
        }
        $filePath .= '.php';

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($filePath);
        }

        return [$filePath, $key];
    }
}