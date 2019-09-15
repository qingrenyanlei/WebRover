<?php


namespace WebRover\Framework\Asset\Context;


use Symfony\Component\Asset\Context\ContextInterface;
use WebRover\Framework\Foundation\RequestStack;

/**
 * Class RequestStackContext
 * @package WebRover\Framework\Asset\Context
 */
class RequestStackContext implements ContextInterface
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getBasePath()
    {
        if (!$request = $this->requestStack->getMasterRequest()) {
            return '';
        }

        return $request->getBasePath();
    }

    /**
     * {@inheritdoc}
     */
    public function isSecure()
    {
        if (!$request = $this->requestStack->getMasterRequest()) {
            return false;
        }

        return $request->isSecure();
    }
}