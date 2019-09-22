<?php


namespace WebRover\Framework\Kernel;


use WebRover\Framework\Kernel\Bundle\BundleInterface;

/**
 * The Kernel is the heart of the WebRover system.
 *
 * It manages an environment made of modules.
 *
 * Interface KernelInterface
 * @package WebRover\Framework\Kernel
 */
interface KernelInterface extends HttpKernelInterface, \Serializable
{
    public function registerBundles();

    public function resetContainer();

    /**
     * Boots the current kernel.
     */
    public function boot();

    /**
     * Shutdowns the kernel.
     *
     * This method is mainly useful when doing functional testing.
     */
    public function shutdown();

    /**
     * Gets the registered module instances.
     *
     * @return BundleInterface[] An array of registered module instances
     */
    public function getBundles();

    /**
     * Returns a module and optionally its descendants by its name.
     *
     * The second argument is deprecated as of 3.4 and will be removed in 4.0. This method
     * will always return an instance of ModuleInterface in 4.0.
     *
     * @param string $name Module name
     *
     * @return BundleInterface A ModuleInterface instance
     *
     * @throws \InvalidArgumentException when the module is not enabled
     */
    public function getBundle($name);

    /**
     * Gets the name of the kernel.
     *
     * @return string The kernel name
     */
    public function getName();

    /**
     * Gets the environment.
     *
     * @return string The current environment
     */
    public function getEnvironment();

    /**
     * Checks if debug mode is enabled.
     *
     * @return bool true if debug mode is enabled, false otherwise
     */
    public function isDebug();

    /**
     * Gets the charset of the application.
     *
     * @return string The charset
     */
    public function getCharset();

    public function getPublicPath();

    public function getRootPath();

    public function getAppPath();

    public function getConfigPath();

    public function getStoragePath();

    public function getResourcePath();
}