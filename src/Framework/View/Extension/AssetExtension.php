<?php


namespace WebRover\Framework\View\Extension;


use Symfony\Component\Asset\Packages;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AssetExtension
 * @package WebRover\Framework\View\Extension
 */
class AssetExtension extends AbstractExtension
{
    private $packages;

    public function __construct(Packages $packages)
    {
        $this->packages = $packages;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset', [$this, 'getUrl'])
        ];
    }

    public function getUrl($path, $packageName = null)
    {
        return $this->packages->getUrl($path, $packageName);
    }

    public function getPackage($name = null)
    {
        return $this->packages->getPackage($name);
    }

    public function getVersion($path, $packageName = null)
    {
        return $this->packages->getVersion($path, $packageName);
    }
}