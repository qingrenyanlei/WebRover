<?php


namespace WebRover\Framework\Kernel\Controller;


use WebRover\Framework\Kernel\KernelInterface;

/**
 * Class ControllerNameParser
 * @package WebRover\Framework\Kernel\Controller
 */
class ControllerNameParser
{
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function parse($controller)
    {
        $parts = explode(':', $controller);
        if (3 !== \count($parts) || \in_array('', $parts, true)) {
            throw new \InvalidArgumentException(sprintf('The "%s" controller is not a valid "a:b:c" controller string.', $controller));
        }

        $originalController = $controller;
        list($bundleName, $controller, $action) = $parts;
        $controller = str_replace('/', '\\', $controller);


        try {
            // this throws an exception if there is no such bundle
            $bundle = $this->kernel->getBundle($bundleName);
        } catch (\InvalidArgumentException $e) {
            $message = sprintf(
                'The "%s" (from the _controller value "%s") does not exist or is not enabled in your kernel!',
                $bundleName,
                $originalController
            );

            if ($alternative = $this->findAlternative($bundleName)) {
                $message .= sprintf(' Did you mean "%s:%s:%s"?', $alternative, $controller, $action);
            }

            throw new \InvalidArgumentException($message, 0, $e);
        }

        $try = $bundle->getNamespace() . '\\Controller\\' . $controller . 'Controller';

        if (class_exists($try)) {
            return $try . '::' . $action . 'Action';
        }

        $msg = sprintf('The _controller value "%s:%s:%s" maps to a "%s" class, but this class was not found. Create this class or check the spelling of the class and its namespace.', $bundleName, $controller, $action, $try);

        throw new \InvalidArgumentException($msg);
    }

    private function findAlternative($nonExistentBundleName)
    {
        $bundleNames = array_map(function ($b) {
            return $b->getName();
        }, $this->kernel->getBundles());

        $alternative = null;
        $shortest = null;
        foreach ($bundleNames as $bundleName) {
            // if there's a partial match, return it immediately
            if (false !== strpos($bundleName, $nonExistentBundleName)) {
                return $bundleName;
            }

            $lev = levenshtein($nonExistentBundleName, $bundleName);
            if ($lev <= \strlen($nonExistentBundleName) / 3 && (null === $alternative || $lev < $shortest)) {
                $alternative = $bundleName;
                $shortest = $lev;
            }
        }

        return $alternative;
    }
}