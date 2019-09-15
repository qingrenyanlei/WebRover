<?php


namespace WebRover\Framework\Config;


use WebRover\Framework\Foundation\File\Exception\FileNotFoundException;
use WebRover\Framework\Kernel\Module\ModuleInterface;
use WebRover\Framework\Support\Arr;

/**
 * Class Config
 * @package WebRover\Framework\Config
 */
class Config
{
    private $rootPath;

    /**
     * @var ModuleInterface[]
     */
    private $modules = [];

    private $bags = [];

    public function __construct($rootPath, array $modules = [])
    {
        $this->rootPath = $rootPath;
        $this->modules = $modules;
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
            $this->bags[$identifier] = new ParameterBag(include $filePath);
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
            list($moduleName, $fileName) = explode('/', substr($fileName, 1));
            if (!isset($this->modules[$moduleName])) {
                throw new \InvalidArgumentException(sprintf('The "%s" module does not exist', $moduleName));
            }

            $module = $this->modules[$moduleName];

            $filePath = $module->getConfigPath() . $fileName;
        }
        $filePath .= '.php';

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($filePath);
        }

        return [$filePath, $key];
    }
}