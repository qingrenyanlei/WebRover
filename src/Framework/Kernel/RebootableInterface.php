<?php


namespace WebRover\Framework\Kernel;


/**
 * Allows the Kernel to be rebooted using a temporary cache directory.
 *
 * Interface RebootableInterface
 * @package WebRover\Framework\Kernel
 */
interface RebootableInterface
{
    /**
     * Reboots a kernel.
     *
     * The getCacheDir() method of a rebootable kernel should not be called
     * while building the container. Use the %kernel.cache_dir% parameter instead.
     */
    public function reboot();
}