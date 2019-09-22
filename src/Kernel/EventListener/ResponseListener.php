<?php


namespace WebRover\Framework\Kernel\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WebRover\Framework\Kernel\Event\FilterResponseEvent;
use WebRover\Framework\Kernel\KernelEvents;

/**
 * ResponseListener fixes the Response headers based on the Request.
 *
 * Class ResponseListener
 * @package WebRover\Framework\Kernel\EventListener
 */
class ResponseListener implements EventSubscriberInterface
{
    private $charset;

    public function __construct($charset)
    {
        $this->charset = $charset;
    }

    /**
     * Filters the Response.
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $response = $event->getResponse();

        if (null === $response->getCharset()) {
            $response->setCharset($this->charset);
        }

        $response->prepare($event->getRequest());
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
