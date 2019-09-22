<?php


namespace WebRover\Framework\Filesystem\Facade;


use WebRover\Framework\Kernel\Facade\AbstractFacade;

/**
 * Class Filesystem
 * @package WebRover\Framework\Filesystem\Facade
 * @mixin \Symfony\Component\Filesystem\Filesystem
 * @method void copy($originFile, $targetFile, $overwriteNewerFiles = false) static
 * @method void mkdir($dirs, $mode = 0777) static
 * @method bool exists($files) static
 * @method void touch($files, $time = null, $atime = null) static
 * @method void remove($files) static
 * @method void chmod($files, $mode, $umask = 0000, $recursive = false) static
 * @method void chown($files, $user, $recursive = false) static
 * @method void chgrp($files, $group, $recursive = false) static
 * @method void rename($origin, $target, $overwrite = false) static
 * @method void symlink($originDir, $targetDir, $copyOnWindows = false) static
 * @method string makePathRelative($endPath, $startPath) static
 * @method void mirror($originDir, $targetDir, \Traversable $iterator = null, $options = array()) static
 * @method bool isAbsolutePath($file) static
 * @method string tempnam($dir, $prefix) static
 * @method void dumpFile($filename, $content, $mode = 0666) static
 */
class Filesystem extends AbstractFacade
{
    public static function getFacadeClass()
    {
        return 'filesystem';
    }
}