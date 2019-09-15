<?php


namespace WebRover\Framework\View\Extension;


use Twig\Extension\AbstractExtension;
use WebRover\Framework\Foundation\RequestStack;

/**
 * Class AppExtension
 * @package WebRover\Framework\View\Extension
 */
class AppExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getGlobals()
    {
        return [
            'app' => [
                'request' => $this->requestStack->getCurrentRequest()
            ]
        ];
    }
}