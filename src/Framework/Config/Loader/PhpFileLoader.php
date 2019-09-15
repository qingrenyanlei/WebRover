<?php


namespace WebRover\Framework\Config\Loader;


use WebRover\Framework\Config\ParameterBag;
use WebRover\Framework\Foundation\File\Exception\FileNotFoundException;

/**
 * Class PhpFileLoader
 * @package WebRover\Framework\Config\Loader
 */
class PhpFileLoader implements LoaderInterface
{
    public function parse($filePath)
    {
        if (!$this->supports($filePath)) {
            throw new \InvalidArgumentException(sprintf('Only php format be supported, file "%s" can not be used', $filePath));
        }

        if (!file_exists($filePath)) {
            throw new FileNotFoundException($filePath);
        }

        $parsedConfig = include $filePath;

        if (!\is_array($parsedConfig)) {
            throw new \InvalidArgumentException(sprintf('The file "%s" must contain a php array.', $filePath));
        }

        return new ParameterBag($parsedConfig);
    }

    public function supports($resource, $type = null)
    {
        return \is_string($resource) && \in_array(pathinfo($resource, PATHINFO_EXTENSION), array('php'), true) && (!$type || 'php' === $type);
    }

    public function extension()
    {
        return 'php';
    }
}